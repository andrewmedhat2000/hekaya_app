<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Currency;
use App\Models\Target;

class TargetController extends Controller
{
    //
    protected $mainUrl = 'dashboard.targets.';
    protected $exceptionUrl = 'home';


    public function index(Request $request)
    {
        $data['currencies'] = Currency::get()->pluck('name', 'id')->toArray();
        $data['targets'] = Target::paginate(10);
        return view($this->mainUrl . 'index', $data);
    }

    public function create()
    {
        $data['currencies'] = Currency::get()->pluck('name', 'id')->toArray();

        return view($this->mainUrl . 'create', $data);
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


        if ($request->get('hours')) {

            $data['mins'] = $data['hours'] * 60;
        } else {
            $data['mins'] = 0;
        }

        if ($request->get('hour_price')) {

            $data['hour_price'] = $data['hour_price'];
        } else {
            $data['hour_price'] = 0;
        }

        $target = Target::create($data);

        return redirect()->route($this->mainUrl . 'index')->with('success', [
            'type' => 'success',
            'message' => 'target created successfully.'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Target $target)
    {
        $data['currencies'] = Currency::get()->pluck('name', 'id')->toArray();
        $data['target'] = $target;
        return view($this->mainUrl . 'show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Target $target)
    {
        $data['currencies'] = Currency::get()->pluck('name', 'id')->toArray();
        $data['target'] = $target;
        return view($this->mainUrl . 'edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Target $target)
    {
        $data = $request->all();
        if ($request->get('hours')) {

            $data['mins'] = $data['hours'] * 60;
        } else {
            $data['mins'] = 0;
        }

        if ($request->get('hour_price')) {

            $data['hour_price'] = $data['hour_price'];
        } else {
            $data['hour_price'] = 0;
        }

        $target->update($data);

        return redirect()->route($this->mainUrl . 'index');
    }

    public function destroy($id, Request $request)
    {
        $target = Target::where('id', $id)->first();

        $target->delete();

        return redirect()->route($this->mainUrl . 'index');
    }
}
