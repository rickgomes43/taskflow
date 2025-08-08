<?php

namespace Tests\Unit\Auth;

use Tests\TestCase;
use App\Http\Controllers\Auth\LogoutController;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Mockery;

class LogoutControllerTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_destroy_method_returns_redirect_response()
    {
        // Mock facades
        Auth::shouldReceive('logout')->once();
        
        $request = Mockery::mock(Request::class);
        $session = Mockery::mock();
        
        $request->shouldReceive('session')->andReturn($session);
        $session->shouldReceive('invalidate')->once();
        $session->shouldReceive('regenerateToken')->once();
        
        $controller = new LogoutController();
        $response = $controller->destroy($request);
        
        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    public function test_destroy_method_calls_auth_logout()
    {
        Auth::shouldReceive('logout')->once();
        
        $request = Mockery::mock(Request::class);
        $session = Mockery::mock();
        
        $request->shouldReceive('session')->andReturn($session);
        $session->shouldReceive('invalidate')->once();
        $session->shouldReceive('regenerateToken')->once();
        
        $controller = new LogoutController();
        $controller->destroy($request);
        
        // If we get here without exception, Auth::logout() was called
        $this->assertTrue(true);
    }
}