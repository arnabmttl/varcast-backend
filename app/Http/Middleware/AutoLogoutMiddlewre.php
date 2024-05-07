<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use JWTAuth;

class AutoLogoutMiddlewre
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
            $user = JWTAuth::parseToken()->authenticate();
            if (!empty(@$user) && @$user->status == 'I') {
                return response()->json([
                    "code"=> 200,
                    'status' => 'inactive_account',
                    'message' => 'Your account is inactive by the admin.'
                ],200);
            }
            elseif(!empty(@$user) && @$user->status == 'D'){
                return response()->json([
                    "code"=> 200,
                    'status' => 'inactive_account',
                    'message' => 'Your account is deleted by the admin.'
                ],200);
            }
        } catch (\Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json([
                    "code"=> 403,
                    'status' => 'token_expire',
                    'message' => 'Token Invalid',
                ], 403);
            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return response()->json([
                    "code"=> 403,
                    'status' => 'token_expire',
                    'message' => 'Token Expired',
                ], 403);
            } else {
                return response()->json([
                    "code"=> 403,
                    'status' => 'token_expire',
                    'message' => $e->getMessage(),
                ],403);
            }
        }
        return $next($request);
    }
}
