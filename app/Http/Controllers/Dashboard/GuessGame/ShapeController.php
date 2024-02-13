<?php

namespace App\Http\Controllers\Dashboard\GuessGame;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GuessGame;
use App\Models\GuessShape;
use App\Models\GuessPackage;

class ShapeController extends Controller
{

    protected $mainUrl = 'dashboard.guessgame.shapes.';
    protected $exceptionUrl = 'home';


    public function index(Request $request)
    {
        $data['shapes'] = GuessShape::paginate(10);
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
        $shapes = GuessShape::create($data);

        return redirect()->route($this->mainUrl . 'index')->with('success', [
            'type' => 'success',
            'message' => 'Shape created successfully.'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(GuessShape $shape)
    {
        $data['resource'] = $shape;
        return view($this->mainUrl . 'show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(GuessShape $shape)
    {
        $data['shape'] = $shape;
        return view($this->mainUrl . 'edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GuessShape $shape)
    {
        $data = $request->all();

        if(isset($data['image'])){
            $image_path = public_path($shape->upload_distination).$shape->image;
        }

        $shape->update($data);
        if(isset($image_path) && file_exists($image_path)) unlink($image_path);

        return redirect()->route($this->mainUrl . 'index');
    }

    public function destroy($id, Request $request)
    {
        $shape = GuessShape::where('id', $id)->first();

        if(!empty($shape->image)){
            $image_path = public_path($shape->upload_distination).$shape->image;
        }

        $shape->delete();
        if(isset($image_path) && file_exists($image_path)) unlink($image_path);

        return redirect()->route($this->mainUrl . 'index');
    }
}
