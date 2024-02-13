<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\LocalChannel;
use App\Models\LocalChat;
use App\Models\User;
use Illuminate\Http\Request;

class LocalChannelController extends Controller
{
    //
    protected $mainUrl = 'dashboard.local_channel.';
    protected $routeUrl = 'dashboard.local.channel.';
    protected $exceptionUrl = 'home';


    public function index(Request $request)
    {
        $data['channels'] = LocalChannel::orderBy('status','DESC')->orderBy('updated_at','DESC')->paginate(10);
        return view($this->mainUrl . 'index', $data);
    }

    public function create()
    {
        $data['users'] = User::whereHas('roles', function ($query) {
            $query->where('name', 'client');
        })->get()->pluck('user_name','user_name')->toArray();

        return view($this->mainUrl . 'create',$data);
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

        $channel = LocalChannel::updateOrCreate(['name' => $data['name']]);

        return redirect()->route($this->routeUrl . 'get',['channel_id' =>$channel->id])->with('success', [
            'type' => 'success',
            'message' => 'Channel created successfully.'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, LocalChannel $channel)
    {
        $channel = LocalChannel::where('id',$request->get('channel_id'))->first();
        $channel->update(['status' => 0]);

        $data['user'] = $channel->user()->first();

        $data['channel'] = $channel;
        $data['messages'] = $channel->chat()->get();

        return view($this->mainUrl . 'show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(LocalChannel $channel)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,LocalChannel $channel)
    {

    }
    public function send(Request $request)
    {
        $data = $request->all();

        $message = LocalChat::create([
            'from_user_id' => $request->get('user_id'),
            'message' => $request->get('message'),
            'channel_id' => $request->get('channel_id'),
            ]);
            

        return redirect()->route($this->routeUrl . 'get',['channel_id' =>$request->get('channel_id')])->with('success', [
            'type' => 'success',
            'message' => 'Message send successfully.'
        ]);
    }

    public function destroy($id, Request $request)
    {
        $channel = LocalChannel::where('id',$request->get('channel_id'))->first();

        $channel->delete();

        return redirect()->route($this->routeUrl . 'index');
    }
}
