<?php

namespace App\Http\Middleware;

use Closure;

class CheckAdmin
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
        if (\Auth::guest ()) {
            if (\Request::ajax ()) {
                return Response::make ( 'Unauthorized', 401 );
            } else {
                return \Redirect::to ( route('admin:login') );
            }
        }
        return $next($request);
    }
}
