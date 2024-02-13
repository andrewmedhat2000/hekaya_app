<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    //
    protected $mainUrl = 'dashboard.country.';
    protected $exceptionUrl = 'home';


    public function index(Request $request)
    {
        $data['countries'] = Country::paginate(10);
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

        $country = Country::create($data);

        return redirect()->route($this->mainUrl . 'index')->with('success', [
            'type' => 'success',
            'message' => 'Country created successfully.'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Country $country)
    {

        $data['resource'] = $country;
        return view($this->mainUrl . 'show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Country $country)
    {
        $data['resource'] = $country;
        return view($this->mainUrl . 'edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Country $country)
    {
        $data = $request->all();

        $country->update($data);

        return redirect()->route($this->mainUrl . 'index');
    }

    public function destroy($id, Request $request)
    {
        $country = Country::where('id', $id)->first();

        $country->delete();

        return redirect()->route($this->mainUrl . 'index');
    }
}
