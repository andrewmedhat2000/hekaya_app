<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Log;
use Closure;

class LogAfterRequest {

	public function handle($request, Closure $next)
	{
		return $next($request);
	}

	public function terminate($request, $response)
	{
		Log::info([
            'path' => $request->path(),
            'user_id' => ($request->user('api')->id ?? null),
            'request' => $request->all(),
            'response_code' => $response->status(),
            'response' => array_map(function($item){
                return is_array($item) ? array_keys($item) : $item;
            }, json_decode($response->content(), true)),
        ]);
	}

    // if($request->is('*/auth*')){
    //     Log::info([
    //         'path' => $request->path(),
    //         'user_id' => ($request->user('api')->id ?? null),
    //         'request' => $request->all(),
    //         'response_code' => $response->status(),
    //         'ip_address' => $response->status() == 423 ? $request->ip() : '',
    //         'response' => array_map(function($item){
    //             return is_array($item) ? array_keys($item) : $item;
    //         }, json_decode($response->content(), true)),
    //     ]);
    // }

}
