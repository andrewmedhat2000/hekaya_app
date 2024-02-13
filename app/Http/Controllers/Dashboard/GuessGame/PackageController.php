<?php

namespace App\Http\Controllers\Dashboard\GuessGame;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GuessGame;
use App\Models\GuessShape;
use App\Models\GuessPackage;

class PackageController extends Controller
{

    protected $mainUrl = 'dashboard.guessgame.packages.';
    protected $exceptionUrl = 'home';


    public function index(Request $request)
    {
        $data['packages'] = GuessPackage::paginate(10);
        return view($this->mainUrl . 'index', $data);
    }

    public function create()
    {
        return view($this->mainUrl . 'create');
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
        $packages = GuessPackage::create($data);

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
    public function show(GuessPackage $package)
    {
        $data['resource'] = $package;
        return view($this->mainUrl . 'show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(GuessPackage $package)
    {
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
    public function update(Request $request, GuessPackage $package)
    {
        $data = $request->all();
        $package->update($data);

        return redirect()->route($this->mainUrl . 'index');
    }

    public function destroy($id, Request $request)
    {
        $package = GuessPackage::where('id', $id)->first();
        $package->delete();

        return redirect()->route($this->mainUrl . 'index');
    }
}
