<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class FilterResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //Log::info(app('Illuminate\Http\Response')->status());
        return $next($request);
    }
}
