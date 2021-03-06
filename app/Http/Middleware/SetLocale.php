<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if($request->routeIs('contact')){
            if(!$request->lang){
                return redirect(url()->current().'?lang='.session('locale'));
            }
            else{
                session(['locale'=>$request->lang]);
            }
        }
        App::setLocale(session('locale'));
        return $next($request);
    }
}
