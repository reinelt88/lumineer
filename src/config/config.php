<?php

/**
 * This file is part of the Lumineer role & 
 * permission management solution for Lumen.
 *
 * @author Vince Kronlein <vince@19peaches.com>
 * @license https://github.com/19peaches/lumineer/blob/master/LICENSE
 * @copyright 19 Peaches, LLC. All Rights Reserved.
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Lumen User Model
    |--------------------------------------------------------------------------
    |
    | Since Lumen by default has no auth.providers configuration we'll set
    | a configuration value here. Make sure this matches the namespace
    | for your User model and table name.
    |
    */
    'users' => [
        'model' => 'App\Auth\Usuario',
        'table' => 'usuarios',
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Lumineer Role Model
    |--------------------------------------------------------------------------
    |
    | This is the Role model used by Lumineer to create correct relations.  Update
    | the role if it is in a different namespace.
    |
    */
    'role' => 'App\Models\Role',

    /*
    |--------------------------------------------------------------------------
    | Lumineer Roles Table
    |--------------------------------------------------------------------------
    |
    | This is the roles table used by Lumineer to save roles to the database.
    |
    */
    'roles_table' => 'lumineer_roles',

    /*
    |--------------------------------------------------------------------------
    | Lumineer Permission Model
    |--------------------------------------------------------------------------
    |
    | This is the Permission model used by Lumineer to create correct relations.
    | Update the permission if it is in a different namespace.
    |
    */
    'permission' => 'App\Models\Permiso',

    /*
    |--------------------------------------------------------------------------
    | Lumineer Permissions Table
    |--------------------------------------------------------------------------
    |
    | This is the permissions table used by Lumineer to save permissions to the
    | database.
    |
    */
    'permissions_table' => 'permiso',

    /*
    |--------------------------------------------------------------------------
    | Lumineer permission_role Table
    |--------------------------------------------------------------------------
    |
    | This is the permission_role table used by Lumineer to save relationship
    | between permissions and roles to the database.
    |
    */
    'permission_role_table' => 'permiso_role',

    /*
    |--------------------------------------------------------------------------
    | Lumineer role_user Table
    |--------------------------------------------------------------------------
    |
    | This is the role_user table used by Lumineer to save assigned roles to the
    | database.
    |
    */
    'role_user_table' => 'role_usuario',

    /*
    |--------------------------------------------------------------------------
    | User Foreign key on Lumineer's role_user Table (Pivot)
    |--------------------------------------------------------------------------
    */
    'user_foreign_key' => 'usuario_id',

    /*
    |--------------------------------------------------------------------------
    | Role Foreign key on Lumineer's role_user and permission_role Tables (Pivot)
    |--------------------------------------------------------------------------
    */
    'role_foreign_key' => 'role_id',

    /*
    |--------------------------------------------------------------------------
    | Permission Foreign key on Lumineer's permission_role Table (Pivot)
    |--------------------------------------------------------------------------
    */
    'permission_foreign_key' => 'permiso_id',
];
