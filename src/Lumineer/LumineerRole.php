<?php

/**
 * This file is part of the Lumineer role & 
 * permission management solution for Lumen.
 *
 * @author Vince Kronlein <vince@19peaches.com>
 * @license https://github.com/19peaches/lumineer/blob/master/LICENSE
 * @copyright 19 Peaches, LLC. All Rights Reserved.
 */

namespace Reinelt\Lumineer;

use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;
use Reinelt\Lumineer\Traits\LumineerRoleTrait;
use Reinelt\Lumineer\Contracts\LumineerRoleInterface;

/**
 * Lumineer base model for roles.
 */
class LumineerRole extends Model implements LumineerRoleInterface
{
    use LumineerRoleTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table;

    /**
     * Creates a new instance of the model.
     *
     * @param array $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = Config::get('lumineer.roles_table');
    }
}
