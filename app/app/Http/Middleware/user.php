<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
class user
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
        dd('lll');
         if(Auth::check()==false || Auth::User()->logstatus!=1)
         {    
             return redirect('/');
         }
       
        return $next($request);
    }
}
