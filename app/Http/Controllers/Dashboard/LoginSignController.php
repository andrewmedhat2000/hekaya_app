<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\LoginSign;
use Illuminate\Http\Request;

class LoginSignController extends Controller
{
    //

    protected $mainUrl = 'dashboard.loginsigns.';
    protected $exceptionUrl = 'home';


    public function index(Request $request)
    {
        $data['collection'] = LoginSign::paginate(10);
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

        $loginSign = LoginSign::create($data);

        return redirect()->route($this->mainUrl . 'index')->with('success', [
            'type' => 'success',
            'message' => 'LoginSign created successfully.'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(LoginSign $loginSign)
    {

        $data['resource'] = $loginSign;
        return view($this->mainUrl . 'show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(LoginSign $loginSign)
    {
        $data['resource'] = $loginSign;
        return view($this->mainUrl . 'edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LoginSign $loginSign)
    {
        $data = $request->all();

        $loginSign->update($data);

        return redirect()->route($this->mainUrl . 'index');
    }

    public function destroy($id, Request $request)
    {
        $loginSign = LoginSign::where('id', $id)->first();

        $loginSign->delete();

        return redirect()->route($this->mainUrl . 'index');
    }
}
