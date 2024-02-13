<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    //
    protected $mainUrl = 'dashboard.tag.';
    protected $exceptionUrl = 'home';


    public function index(Request $request)
    {
        $data['tags'] = Tag::paginate(10);
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

        $tag = Tag::create($data);

        return redirect()->route($this->mainUrl . 'index')->with('success', [
            'type' => 'success',
            'message' => 'tag created successfully.'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(tag $tag)
    {

        $data['resource'] = $tag;
        return view($this->mainUrl . 'show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(tag $tag)
    {
        $data['resource'] = $tag;
        return view($this->mainUrl . 'edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, tag $tag)
    {
        $data = $request->all();

        $tag->update($data);

        return redirect()->route($this->mainUrl . 'index');
    }

    public function destroy($id, Request $request)
    {
        $tag = Tag::where('id', $id)->first();

        $tag->delete();

        return redirect()->route($this->mainUrl . 'index');
    }
}
