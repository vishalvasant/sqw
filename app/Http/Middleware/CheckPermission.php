<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $permission)
    {
        if (!auth()->user()->can($permission)) {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}

