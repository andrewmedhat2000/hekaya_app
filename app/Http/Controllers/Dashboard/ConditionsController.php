<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Conditions;

class ConditionsController extends Controller
{
    //
    protected $mainUrl = 'dashboard.conditions.';
    protected $exceptionUrl = 'home';


    public function index(Request $request)
    {
        // $data['conditions'] = Conditions::paginate(10);
        // return view($this->mainUrl . 'index', $data);
        $condition = Conditions::first();

        if(!$condition){
            $condition = Conditions::create(['name'=>'']);
        }

        $data['resource'] = $condition;
        return view($this->mainUrl . 'edit', $data);
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

        $conditions = Conditions::create($data);

        return redirect()->route($this->mainUrl . 'index')->with('success', [
            'type' => 'success',
            'message' => 'conditions created successfully.'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Conditions $conditions)
    {

        $data['resource'] = $conditions;
        return view($this->mainUrl . 'show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Conditions $conditions)
    {
        $data['resource'] = $conditions;
        return view($this->mainUrl . 'edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Conditions $conditions)
    {
        $data = $request->all();
        $condition = Conditions::where('id',$data['resource_id']);
        $condition->update(['name'=>$data['name']]);

        return redirect()->route($this->mainUrl . 'index');
    }

    public function destroy($id, Request $request)
    {
        $conditions = Conditions::where('id', $id)->first();

        $conditions->delete();

        return redirect()->route($this->mainUrl . 'index');
    }
}
