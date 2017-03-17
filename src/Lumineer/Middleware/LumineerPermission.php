<?php

/**
 * This file is part of the Lumineer role & 
 * permission management solution for Lumen.
 *
 * @author Vince Kronlein <vince@19peaches.com>
 * @license https://github.com/19peaches/lumineer/blob/master/LICENSE
 * @copyright 19 Peaches, LLC. All Rights Reserved.
 */

namespace Peaches\Lumineer\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

/**
 * Lumineer permission middleware.
 */
class LumineerPermission
{
    const DELIMITER = '|';

    /**
     * @var \Illuminate\Contracts\Auth\Guard
     */
    protected $auth;

    /**
     * Creates a new instance of the middleware.
     *
     * @param Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Closure $next
     * @param  $permissions
     * @return mixed
     */
    public function handle($request, Closure $next, $permissions)
    {
        if (!is_array($permissions)) {
            $permissions = explode(self::DELIMITER, $permissions);
        }

        if ($this->auth->guest() || !$request->user()->can($permissions)) {
            abort(403);
        }

        return $next($request);
    }
}
