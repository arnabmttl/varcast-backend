<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;
class AutoLogoutWebMiddlewre
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            if(@Auth::check()){
                if (!empty(@Auth::user()) && @Auth::user()->status == 'I') {
                    \Session::flash('error', 'Your account is inactive by the admin.');
                    @Auth::logout();
                    return redirect()->route('home1');
                }
                elseif(!empty(@Auth::user()) && @Auth::user()->status == 'D'){
                    \Session::flash('error', 'Your account is deleted by the admin.');
                    @Auth::logout();
                    return redirect()->route('home1');
                }
            }
        } catch (\Exception $e) {
            @Auth::logout();
            return redirect()->route('home1');
        }
        return $next($request);
    }
}
