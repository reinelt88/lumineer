<?php

/**
 * This file is part of the Lumineer role & 
 * permission management solution for Lumen.
 *
 * @author Vince Kronlein <vince@19peaches.com>
 * @license https://github.com/19peaches/lumineer/blob/master/LICENSE
 * @copyright 19 Peaches, LLC. All Rights Reserved.
 */

use Mockery as m;
use Peaches\Lumineer\Middleware\LumineerRole;

class LumineerRoleTest extends MiddlewareTest
{
    public function testHandle_IsGuestWithMismatchingRole_ShouldAbort403()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        $guard = m::mock('Illuminate\Contracts\Auth\Guard[guest]');
        $request = $this->mockRequest();

        $middleware = new LumineerRole($guard);

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */
        $guard->shouldReceive('guest')->andReturn(true);
        $request->user()->shouldReceive('hasRole')->andReturn(false);

        $middleware->handle($request, function () {}, null, null, true);

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */
        $this->assertAbortCode(403);
    }

    public function testHandle_IsGuestWithMatchingRole_ShouldAbort403()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        $guard = m::mock('Illuminate\Contracts\Auth\Guard');
        $request = $this->mockRequest();

        $middleware = new LumineerRole($guard);

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */
        $guard->shouldReceive('guest')->andReturn(true);
        $request->user()->shouldReceive('hasRole')->andReturn(true);

        $middleware->handle($request, function () {}, null, null);

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */
        $this->assertAbortCode(403);
    }

    public function testHandle_IsLoggedInWithMismatchRole_ShouldAbort403()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        $guard = m::mock('Illuminate\Contracts\Auth\Guard');
        $request = $this->mockRequest();

        $middleware = new LumineerRole($guard);

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */
        $guard->shouldReceive('guest')->andReturn(false);
        $request->user()->shouldReceive('hasRole')->andReturn(false);

        $middleware->handle($request, function () {}, null, null);

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */
        $this->assertAbortCode(403);
    }

    public function testHandle_IsLoggedInWithMatchingRole_ShouldNotAbort()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        $guard = m::mock('Illuminate\Contracts\Auth\Guard');
        $request = $this->mockRequest();

        $middleware = new LumineerRole($guard);

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */
        $guard->shouldReceive('guest')->andReturn(false);
        $request->user()->shouldReceive('hasRole')->andReturn(true);

        $middleware->handle($request, function () {}, null, null);

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */
        $this->assertDidNotAbort();
    }
}
