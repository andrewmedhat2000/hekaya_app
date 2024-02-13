<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Alert;

class CountryController extends BaseController
{

    public function getCities(Request $request)
    {
        //


        try {

            $cities = City::get();

            return response()->json([
                'status_code' => 200,
                'cities' => $cities,
            ], 200);
        } catch (\Exception $ex) {

            DB::rollback();

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

    public function getCity(Request $request)
    {
        //


        try {

            $validator = Validator::make($request->all(), [
                'country_id'                          => 'required|exists:countries,id',
            ]);

            if ($validator->fails() != null) {
                return response()->json([
                    'status_code' => 409,
                    'message' => $validator->errors()->first(),
                ], 409);
            }


            $cities = City::where('country_id', $request->get('country_id'))->get();

            return response()->json([
                'status_code' => 200,
                'cities' => $cities,
            ], 200);
        } catch (\Exception $ex) {

            DB::rollback();

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
}
