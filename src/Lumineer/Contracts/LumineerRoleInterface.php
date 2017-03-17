<?php

/**
 * This file is part of the Lumineer role & 
 * permission management solution for Lumen.
 *
 * @author Vince Kronlein <vince@19peaches.com>
 * @license https://github.com/19peaches/lumineer/blob/master/LICENSE
 * @copyright 19 Peaches, LLC. All Rights Reserved.
 */

namespace Peaches\Lumineer\Contracts;

/**
 * Lumineer role contract.
 */
interface LumineerRoleInterface
{
    /**
     * Many-to-Many relations with the user model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users();
    
    /**
     * Many-to-Many relations with the permission model.
     * Named "perms" for backwards compatibility. Also because "perms" is short and sweet.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function perms();
    
    /**
     * Save the inputted permissions.
     *
     * @param mixed $inputPermissions
     *
     * @return void
     */
    public function savePermissions($inputPermissions);
    
     /**
     * Attach permission to current role.
     *
     * @param object|array $permission
     *
     * @return void
     */
    public function attachPermission($permission);
    
    /**
     * Detach permission form current role.
     *
     * @param object|array $permission
     *
     * @return void
     */
    public function detachPermission($permission);
    
    /**
     * Attach multiple permissions to current role.
     *
     * @param mixed $permissions
     *
     * @return void
     */
    public function attachPermissions($permissions);
    
    /**
     * Detach multiple permissions from current role
     *
     * @param mixed $permissions
     *
     * @return void
     */
    public function detachPermissions($permissions);
}
