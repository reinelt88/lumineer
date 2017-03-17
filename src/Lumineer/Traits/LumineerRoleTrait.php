<?php

/**
 * This file is part of the Lumineer role & 
 * permission management solution for Lumen.
 *
 * @author Vince Kronlein <vince@19peaches.com>
 * @license https://github.com/19peaches/lumineer/blob/master/LICENSE
 * @copyright 19 Peaches, LLC. All Rights Reserved.
 */

namespace Peaches\Lumineer\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

/**
 * Lumineer role trait.
 */
trait LumineerRoleTrait
{
    /**
     * Big block of caching functionality
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function cachedPermissions()
    {
        $cacheKey = 'lumineer_permissions_for_role_' . $this->getKey();

        return Cache::remember($cacheKey, Config::get('cache.ttl', 60), function () {
            return $this->perms()->get();
        });
    }

    /**
     * Many-to-Many relations with the user model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(
            Config::get('lumineer.users.model'),
            Config::get('lumineer.role_user_table'),
            Config::get('lumineer.role_foreign_key'),
            Config::get('lumineer.user_foreign_key')
        );
    }

    /**
     * Many-to-Many relations with the permission model.
     * Named "perms" for backwards compatibility. Also because "perms" is short and sweet.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function perms()
    {
        return $this->belongsToMany(
            Config::get('lumineer.permission'),
            Config::get('lumineer.permission_role_table'),
            Config::get('lumineer.role_foreign_key'),
            Config::get('lumineer.permission_foreign_key')
        );
    }

    /**
     * Boot the role model
     * Attach event listener to remove the many-to-many records when trying to delete
     * Will NOT delete any records if the role model uses soft deletes.
     *
     * @return void|bool
     */
    public static function boot()
    {
        parent::boot();

        $flushCache = function ($role) {
            $role->flushCache();
            return true;
        };
        
        // If the role doesn't use SoftDeletes
        if (method_exists(Config::get('lumineer.role'), 'restored')) {
            static::restored($flushCache);
        }

        static::deleted($flushCache);
        static::saved($flushCache);

        static::deleting(function ($role) {
            if (!method_exists(Config::get('lumineer.role'), 'bootSoftDeletes')) {
                $role->users()->sync([]);
                $role->perms()->sync([]);
            }

            return true;
        });
    }
    
    /**
     * Checks if the role has a permission by its name.
     *
     * @param string|array $name       Permission name or array of permission names.
     * @param bool         $requireAll All permissions in the array are required.
     *
     * @return bool
     */
    public function hasPermission($name, $requireAll = false)
    {
        if (is_array($name)) {
            foreach ($name as $permissionName) {
                $hasPermission = $this->hasPermission($permissionName);

                if ($hasPermission && !$requireAll) {
                    return true;
                } elseif (!$hasPermission && $requireAll) {
                    return false;
                }
            }

            // If we've made it this far and $requireAll is FALSE, then NONE of the permissions were found
            // If we've made it this far and $requireAll is TRUE, then ALL of the permissions were found.
            // Return the value of $requireAll;
            return $requireAll;
        }

        foreach ($this->cachedPermissions() as $permission) {
            if ($permission->name == $name) {
                return true;
            }
        }

        return false;
    }

    /**
     * Save the inputted permissions.
     *
     * @param mixed $inputPermissions
     *
     * @return array
     */
    public function savePermissions($inputPermissions)
    {
        // If the inputPermissions ist empty it will delete all associations
        $changes = $this->perms()->sync($inputPermissions);
        $this->flushCache();

        return $changes;
    }

    /**
     * Attach permission to current role.
     *
     * @param object|array $permission
     *
     * @return void
     */
    public function attachPermission($permission)
    {
        if (is_object($permission)) {
            $permission = $permission->getKey();
        }

        if (is_array($permission)) {
            $permission = $permission['id'];
        }

        $this->perms()->attach($permission);
        $this->flushCache();
    }

    /**
     * Detach permission from current role.
     *
     * @param object|array $permission
     *
     * @return void
     */
    public function detachPermission($permission)
    {
        if (is_object($permission)) {
            $permission = $permission->getKey();
        }

        if (is_array($permission)) {
            $permission = $permission['id'];
        }

        $this->perms()->detach($permission);
        $this->flushCache();
    }

    /**
     * Attach multiple permissions to current role.
     *
     * @param mixed $permissions
     *
     * @return void
     */
    public function attachPermissions($permissions)
    {
        foreach ($permissions as $permission) {
            $this->attachPermission($permission);
        }
    }

    /**
     * Detach multiple permissions from current role
     *
     * @param mixed $permissions
     *
     * @return void
     */
    public function detachPermissions($permissions)
    {
        foreach ($permissions as $permission) {
            $this->detachPermission($permission);
        }
    }

    /**
     * Flush the role's cache
     * @return void
     */
    public function flushCache()
    {
        Cache::forget('lumineer_permissions_for_role_' . $this->getKey());
    }
}
