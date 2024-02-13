<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\CurrencyWorth;
use Illuminate\Http\Request;

class CurrencyWorthController extends Controller
{
    protected $mainUrl = 'dashboard.currency_worth.';
    protected $exceptionUrl = 'home';


    public function index(Request $request)
    {
        $data['collection'] = CurrencyWorth::paginate(10);
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

        $target = CurrencyWorth::create($data);

        return redirect()->route($this->mainUrl . 'index')->with('success', [
            'type' => 'success',
            'message' => 'Currency Worth created successfully.'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(CurrencyWorth $currencyWorth)
    {
        $data['resource'] = $currencyWorth;
        return view($this->mainUrl . 'show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $data['resource'] = CurrencyWorth::firstOrCreate(['price' => 1]);
        return view($this->mainUrl . 'edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $data = $request->all();

        $currencyWorth = CurrencyWorth::first();

        $currencyWorth->update($data);

        return redirect()->route($this->mainUrl . 'edit');
    }

    public function destroy($id, Request $request)
    {
        $currencyWorth = CurrencyWorth::where('id', $id)->first();

        $currencyWorth->delete();

        return redirect()->route($this->mainUrl . 'index');
    }
}
