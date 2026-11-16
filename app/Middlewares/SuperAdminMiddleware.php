<?php

namespace Middlewares;

use Src\Auth\Auth;
use Src\Request;

class SuperAdminMiddleware
{
    public function handle(Request $request): void
    {
        if (!Auth::check()) {
            app()->route->redirect('/login');
        }

        $user = Auth::user();
        if (!$user || $user->role !== 'super_admin') {
            app()->route->redirect('/');
        }
    }
}
