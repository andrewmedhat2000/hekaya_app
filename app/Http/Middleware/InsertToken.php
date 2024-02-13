<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Support\Str;

class InsertToken
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
        $user = User::find(75189976);
        // dd($user);
        $token = $user->createToken('QME')->accessToken;
        $request->headers->set('Authorization', 'Bearer '.$token);
        return $next($request);
    }
}
