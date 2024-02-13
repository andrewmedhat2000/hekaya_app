<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdsCollection;
use App\Http\Resources\EventCollection;
use App\Models\Ads;
use App\Models\Conditions;
use App\Models\Event;
use App\Models\Terms;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\AppMobileVersion;


class AppInfoController extends BaseController
{
    //
    public function getTermsNConditions(Request $request)
    {
        try {

            $terms = Terms::first();
            $conditions = Conditions::first();


            return response()->json([
                'status_code' => 200,
                'terms' => optional($terms)->name,
                'conditions' => optional($conditions)->name,
            ], 200);
        } catch (\Exception $ex) {
            Log::info('exception: ');
            Log::info($ex->getMessage());
            Log::info($ex);


            return response()->json([
                'status_code' => 407,
                'message' => 'Something Went Wrong',
                'info' => $ex->getMessage()
            ], 407);
        }
    }

    public function getAds(Request $request)
    {
        try {
            $ads = Ads::orderBy('created_at','desc');


            return response()->json([
                'status_code' => 200,
                'pagintaion' => true,
                'items_per_page' => 10,
                'ads' => new AdsCollection($ads->paginate(10)),
            ], 200);
        } catch (\Exception $ex) {
            Log::info('exception: ');
            Log::info($ex->getMessage());
            Log::info($ex);


            return response()->json([
                'status_code' => 407,
                'message' => 'Something Went Wrong',
                'info' => $ex->getMessage()
            ], 407);
        }
    }


    public function getEvents(Request $request)
    {
        try {

            $events = Event::orderBy('created_at','desc');

            return response()->json([
                'status_code' => 200,
                'pagintaion' => true,
                'items_per_page' => 10,
                'events' => new EventCollection($events->paginate(10)),
            ], 200);
        } catch (\Exception $ex) {
            Log::info('exception: ');
            Log::info($ex->getMessage());
            Log::info($ex);


            return response()->json([
                'status_code' => 407,
                'message' => 'Something Went Wrong',
                'info' => $ex->getMessage()
            ], 407);
        }
    }


    public function getProhibitedWords(Request $request)
    {
        try {

            $words = DB::table('prohibited_words')->pluck('word');

            return response()->json([
                'status_code' => 200,
                'words' => $words,
            ], 200);

        } catch (\Exception $ex) {
            return $this->internalServerError($ex);
        }
    }


    public function getMobVer()
    {

        try {

            $android = AppMobileVersion::where('mobile_os','android')->first();
            $ios = AppMobileVersion::where('mobile_os','ios')->first();

            return response()->json([
                'status_code' => 200,
                'android' => $android,
                'ios' => $ios,
            ], 200);
        } catch (\Exception $ex) {
            Log::info('exception: ');
            Log::info($ex->getMessage());
            Log::info($ex);

            return response()->json([
                'status_code' => 407,
                'message' => 'Something Went Wrong',
                'info' => $ex->getMessage()
            ], 407);
        }
        # code...
    }


}
