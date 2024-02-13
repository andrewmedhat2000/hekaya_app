<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    //
    protected $mainUrl = 'dashboard.packages.';
    protected $exceptionUrl = 'home';


    public function index(Request $request)
    {
        $data['currencies'] = Currency::get()->pluck('name','id')->toArray();
        $data['packages'] = Package::paginate(10);
        return view($this->mainUrl . 'index', $data);
    }

    public function create()
    {
        $data['currencies'] = Currency::get()->pluck('name','id')->toArray();

        return view($this->mainUrl . 'create',$data);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {


        $data = $request->all();

        $package = Package::create($data);

        return redirect()->route($this->mainUrl . 'index')->with('success', [
            'type' => 'success',
            'message' => 'Package created successfully.'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Package $package)
    {
        $data['currencies'] = Currency::get()->pluck('name','id')->toArray();
        $data['package'] = $package;
        return view($this->mainUrl . 'show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Package $package)
    {
        $data['currencies'] = Currency::get()->pluck('name','id')->toArray();
        $data['package'] = $package;
        return view($this->mainUrl . 'edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Package $package)
    {
        $data = $request->all();

        $package->update($data);

        return redirect()->route($this->mainUrl . 'index');
    }

    public function destroy($id, Request $request)
    {
        $package = Package::where('id', $id)->first();

        $package->delete();

        return redirect()->route($this->mainUrl . 'index');
    }
}
