<?php

namespace Tagd\Core\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class LogRequest
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
        Log::withContext([
            'api_version' => Str::of($request->path())->after('api/v')->before('/'),
            'request' => [
                'ip' => $request->ip(),
                'method' => Str::of($request->getMethod())->upper(),
                'uri' => $request->getPathInfo(),
                'body' => $request->all(),
                'headers' => $request->headers->all(), // todo: confirm if log all headers
                'browser' => (new \WhichBrowser\Parser($request->header('User-Agent')))->toArray(),
            ],
        ]);

        return $next($request);
    }
}
