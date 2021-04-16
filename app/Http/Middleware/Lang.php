<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Lang
{
    
    public function handle(Request $request, Closure $next)
    {
        app()->setLocale(lang());
        return $next($request);
    }
}
