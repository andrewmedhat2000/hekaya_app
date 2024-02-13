<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Terms;

class TermsController extends Controller
{
    //
    protected $mainUrl = 'dashboard.terms.';
    protected $exceptionUrl = 'home';


    public function index(Request $request)
    {
        // $data['terms'] = Terms::paginate(10);
        // return view($this->mainUrl . 'index', $data);


        $term = Terms::first();

        if(!$term){
            $term = Terms::create(['name'=>'']);
        }


        $data['resource'] = $term;
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

        $terms = Terms::create($data);

        return redirect()->route($this->mainUrl . 'index')->with('success', [
            'type' => 'success',
            'message' => 'terms created successfully.'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Terms $terms)
    {

        $data['resource'] = $terms;
        return view($this->mainUrl . 'show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Terms $terms)
    {
        $data['resource'] = $terms;
        return view($this->mainUrl . 'edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Terms $terms)
    {
        
        $data = $request->all();
        $term = Terms::where('id',$data['resource_id']);
        $term->update(['name'=>$data['name']]);

        return redirect()->route($this->mainUrl . 'index');
    }

    public function destroy($id, Request $request)
    {
        $terms = terms::where('id', $id)->first();

        $terms->delete();

        return redirect()->route($this->mainUrl . 'index');
    }
}
