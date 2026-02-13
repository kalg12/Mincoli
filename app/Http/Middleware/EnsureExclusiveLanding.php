<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureExclusiveLanding
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->session()->get('exclusive_landing_validated')) {
            return redirect()->route('exclusive-landing.gate');
        }

        $request->session()->put('exclusive_flow', true);

        return $next($request);
    }
}
