<?php

namespace Tagd\Core\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class LogUser
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
            'user' => [
                'id' => $request->user()->id,
                'email' => $request->user()->email,
                'model' => $request->user()->model,
            ],
        ]);

        return $next($request);
    }
}
