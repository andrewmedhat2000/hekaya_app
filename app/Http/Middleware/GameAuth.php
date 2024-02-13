<?php

namespace App\Http\Middleware;

use App\Models\Game;
use App\Models\User;
use Closure;

class GameAuth
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
        if (in_array($request->route()->getName(), Game::ROUTES)) {
            abort_if(!$request->hasValidSignature(), 401);
            $user = User::findOrFail($request->user_id);
            session()->put('user_id', $user->id);
        } else {
            $user = User::find(session()->get('user_id'));
        }

        if($user) {
            $token = $user->createToken('QME')->accessToken;
            $request->headers->set('Authorization', 'Bearer '.$token);
        }

        return $next($request);
    }
}
