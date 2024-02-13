<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use Illuminate\Http\Request;

class CityController extends Controller
{
    //
    protected $mainUrl = 'dashboard.city.';
    protected $exceptionUrl = 'home';


    public function index(Request $request)
    {
        $data['cities'] = City::paginate(10);
        return view($this->mainUrl . 'index', $data);
    }

    public function create()
    {
        $data['countries'] = Country::get()->pluck('name', 'id')->toArray();
        
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

        $city = City::create($data);

        return redirect()->route($this->mainUrl . 'index')->with('success', [
            'type' => 'success',
            'message' => 'City created successfully.'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(City $city)
    {

        $data['resource'] = $city;
        return view($this->mainUrl . 'show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(City $city)
    {
        $data['countries'] = Country::get()->pluck('name', 'id')->toArray();
        $data['resource'] = $city;
        return view($this->mainUrl . 'edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, City $city)
    {
        $data = $request->all();
        $city->update($data);
        

        return redirect()->route($this->mainUrl . 'index');
    }

    public function destroy($id, Request $request)
    {
        $city = City::where('id', $id)->first();

        $city->delete();

        return redirect()->route($this->mainUrl . 'index');
    }
}
