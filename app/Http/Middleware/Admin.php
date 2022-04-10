<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Admin
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guest() || !Auth::user()->role->is_admin) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Only admin access']);
            }

            return redirect()->route('login');
        }

        return $next($request);
    }
}
