<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Ads;
use App\Models\Image;
use Illuminate\Http\Request;

class AdsController extends Controller
{
    protected $mainUrl = 'dashboard.ads.';
    protected $exceptionUrl = 'home';


    public function index(Request $request)
    {
        $data['collection'] = Ads::paginate(10);
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

        $ads = Ads::create($data);


        if ($request->file('images')) {
            foreach($data['images'] as $image){
                $image_model = Image::create(['image'=>$image]);
                $image_model->ads()->attach($ads);
            }
        }

        return redirect()->route($this->mainUrl . 'index')->with('success', [
            'type' => 'success',
            'message' => 'Ad created successfully.'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ads = Ads::where('id',$id)->first();
        $data['resource'] = $ads;
        return view($this->mainUrl . 'show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
        $ads = Ads::where('id',$id)->first();
        $data['resource'] = $ads;

        return view($this->mainUrl . 'edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $data = $request->all();
        $ads = Ads::where('id',$id)->first();

        $ads->update($data);


        if ($request->file('images')) {
            foreach($data['images'] as $image){
                $image_model = Image::create(['image'=>$image]);
                $image_model->ads()->attach($ads);
                
            }
            

        }
        return redirect()->route($this->mainUrl . 'index');
    }

    public function destroy($id, Request $request)
    {
        $ads = Ads::where('id', $id)->first();

        $ads->delete();

        return redirect()->route($this->mainUrl . 'index');
    }

    public function imageDelete(Request $request)
    {
        $image = Image::where('id', $request->input('id'))->delete();
        return $request->input('id');
    }
}
