<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Gift;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    //

    protected $mainUrl = 'dashboard.gifts.';
    protected $exceptionUrl = 'home';


    public function index(Request $request)
    {
        $data['gifts'] = Gift::paginate(10);
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

        if(isset($data['gif'])){
            if(!isset($data['display_time'])){
                $data['display_time'] = 6;
            }
        }
        else{
            $data['display_time'] = 0;
        }
        $gift = Gift::create($data);

        return redirect()->route($this->mainUrl . 'index')->with('success', [
            'type' => 'success',
            'message' => 'Gift created successfully.'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Gift $gift)
    {

        $data['resource'] = $gift;
        return view($this->mainUrl . 'show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Gift $gift)
    {
        $data['gift'] = $gift;
        return view($this->mainUrl . 'edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Gift $gift)
    {
        $data = $request->all();

        if(isset($data['image'])){
            $image_path = public_path($gift->upload_distination).$gift->image;
        }
        if(isset($data['gif'])){
            $gif_path = public_path($gift->upload_distination).$gift->gif;
        }

        $gift->update($data);

        if(isset($image_path) && file_exists($image_path)) unlink($image_path);
        if(isset($gif_path) && file_exists($gif_path)) unlink($gif_path);

        return redirect()->route($this->mainUrl . 'index');
    }

    public function destroy($id, Request $request)
    {
        $gift = Gift::where('id', $id)->first();

        if(!empty($gift->image)){
            $image_path = public_path($gift->upload_distination).$gift->image;
        }
        if(!empty($gift->gif)){
            $gif_path = public_path($gift->upload_distination).$gift->gif;
        }

        $gift->delete();

        if(isset($image_path) && file_exists($image_path)) unlink($image_path);
        if(isset($gif_path) && file_exists($gif_path)) unlink($gif_path);

        return redirect()->route($this->mainUrl . 'index');
    }
}
