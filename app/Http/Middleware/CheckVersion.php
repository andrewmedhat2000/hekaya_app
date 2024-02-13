<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Api\BaseController;
use App\Models\AppMobileVersion;
use Closure;

class CheckVersion
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
        // if($request->header('web-app', false)){
        //     return $next($request);
        // }

        $app_versions = AppMobileVersion::get();

        $android_version = $app_versions->where('mobile_os', 'android')->where('update_required', 1)->first()->version ?? 0;
        $ios_version = $app_versions->where('mobile_os', 'ios')->where('update_required', 1)->first()->version ?? 0;
        if($request->header('android-version', PHP_INT_MAX) < $android_version){
            return (new BaseController())->apiErrorResponse('Please update the app.', 412);
        }
        if($request->header('ios-version', PHP_INT_MAX) < $ios_version){
            return (new BaseController())->apiErrorResponse('Please update the app.', 412);
        }

        return $next($request);
    }
}
