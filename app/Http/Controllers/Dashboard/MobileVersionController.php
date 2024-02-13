<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\AppMobileVersion;
use Illuminate\Http\Request;

class MobileVersionController extends Controller
{
    //

    public function edit()
    {
        $android = AppMobileVersion::where('mobile_os','android')->first();
        $ios = AppMobileVersion::where('mobile_os','ios')->first();

        return view('dashboard.mobile-version.edit', compact('android','ios'));
    }

    public function update(Request $request)
    {
        //dd($request->all());
        $android = AppMobileVersion::where('mobile_os','android')->firstOr(function () {
            return AppMobileVersion::create([]);
        });

        $android->update([
            'mobile_os' => 'android',
            'version' => $request->input('Android_Version'),
            'update_required' =>  $request->input('Android_Required_to_update')

        ]);

        $ios = AppMobileVersion::where('mobile_os','ios')->firstOr(function () {
            return AppMobileVersion::create([]);
        });

        $ios->update([
            'mobile_os' => 'ios',
            'version' => $request->input('IOS_Version'),
            'update_required' =>  $request->input('IOS_Required_to_update')

        ]);

        return redirect()->route('dashboard.mobile-version.edit')->with('success', 'Updated successfully.');
    }
}
