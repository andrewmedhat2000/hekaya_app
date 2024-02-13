<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Emoji;
use Illuminate\Http\Request;

class EmojiController extends Controller
{
    //

    protected $mainUrl = 'dashboard.emojis.';
    protected $exceptionUrl = 'home';


    public function index(Request $request)
    {
        $data['emojis'] = Emoji::paginate(10);
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

        $emoji = Emoji::create($data);

        return redirect()->route($this->mainUrl . 'index')->with('success', [
            'type' => 'success',
            'message' => 'Emoji created successfully.'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Emoji $emoji)
    {

        $data['resource'] = $emoji;
        return view($this->mainUrl . 'show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Emoji $emoji)
    {
        $data['emoji'] = $emoji;
        return view($this->mainUrl . 'edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Emoji $emoji)
    {
        $data = $request->all();

        if(isset($data['image'])){
            $image_path = public_path($emoji->upload_distination).$emoji->image;
        }
        if(isset($data['emoji'])){
            $emoji_path = public_path($emoji->upload_distination).$emoji->emoji;
        }

        $emoji->update($data);

        if(isset($image_path) && file_exists($image_path)) unlink($image_path);
        if(isset($emoji_path) && file_exists($emoji_path)) unlink($emoji_path);

        return redirect()->route($this->mainUrl . 'index');
    }

    public function destroy($id, Request $request)
    {
        $emoji = Emoji::where('id', $id)->first();

        if(!empty($emoji->image)){
            $image_path = public_path($emoji->upload_distination).$emoji->image;
        }
        if(!empty($emoji->emoji)){
            $emoji_path = public_path($emoji->upload_distination).$emoji->emoji;
        }

        $emoji->delete();

        if(isset($image_path) && file_exists($image_path)) unlink($image_path);
        if(isset($emoji_path) && file_exists($emoji_path)) unlink($emoji_path);

        return redirect()->route($this->mainUrl . 'index');
    }
}
