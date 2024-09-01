<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DebugHeaders
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $startTime = microtime(true);
        $startMemory = memory_get_usage();

        $response = $next($request);

        $endTime = microtime(true);
        $endMemory = memory_get_usage();

        $duration = round(($endTime - $startTime) * 1000, 2);
        $memoryUsage = round(($endMemory - $startMemory) / 1024, 2);

        $response->headers->set('X-Debug-Time', $duration . ' ms');
        $response->headers->set('X-Debug-Memory', $memoryUsage . ' KB');

        return $response;
    }
}
