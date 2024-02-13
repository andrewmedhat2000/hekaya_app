<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;

class RecordRequests
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
        $request_start_time = microtime(true);

        $request_log_id = DB::table('logged_requests')->insertGetId([
            'path' => $request->path(),
            'user_id' => ($request->user('api')->id ?? null),
            'ip' => $request->ip(),
            'device' => $request->header('User-Agent'),
            'created_at' => now(),
        ]);

        $request->request->add(compact('request_log_id', 'request_start_time'));

        return $next($request);
    }

    public function terminate($request, $response)
	{
        DB::table('logged_requests')->where('id', $request->request_log_id)->update([
            'response_code' => $response->status(),
            'duration' => microtime(true) - $request->request_start_time,
        ]);
	}
}
