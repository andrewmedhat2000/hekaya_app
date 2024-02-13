<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\web\UserCreateRequest;
use App\Http\Requests\web\UserUpdateRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class StreamerAdminController extends Controller
{
    protected $mainUrl = 'dashboard.streameradmin.';
    protected $exceptionUrl = 'home';


    public function index(Request $request)
    {
        $users = User::whereHas('roles', function ($query) {
            $query->where('name', 'streamer_admin');
        });

        $data['users'] = $users->paginate(10);
        return view($this->mainUrl . 'index', $data);
    }

    public function getStreamers(Request $request)
    {
        $admin_id = $request->get('admin_id');
        // return view('dashboard.streameradminreporting.' . 'streamer_index', $data);
        return redirect()->route('dashboard.streameradminreporting.' . 'index', ['admin_id' => $admin_id]);
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

    public function store(UserCreateRequest $request)
    {


        $data = $request->all();
        $user_exist = User::where('email', $request->input('email'))->withTrashed()->first();
        if ($user_exist) {

            // if the client is soft deleted restore him
            if ($user_exist->deleted_at !== null) {
                $user_exist->restore();
                $user_exist->update($data);
                $user_exist->assignRole('streamer_admin');
                return redirect()->route($this->mainUrl . 'index')->with('success', [
                    'type' => 'success',
                    'message' => 'User created successfully.'
                ]);
            }

            return redirect()->route($this->mainUrl . 'index')->with('success', [
                'type' => 'success',
                'message' => 'User Is Already Exist !.'
            ]);
        }


        $user = User::create($data);
        $user->assignRole('streamer_admin');


        return redirect()->route($this->mainUrl . 'index')->with('success', [
            'type' => 'success',
            'message' => 'User account created successfully.'
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
        $user = User::where('id', $id)->first();
        $data['resource'] = $user;
        return view($this->mainUrl . 'view', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::where('id', $id)->first();
        $data['resource'] = $user;
        return view($this->mainUrl . 'edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        $data = $request->all();

        $user->update($data);

        return redirect()->route($this->mainUrl . 'index');
    }

    public function destroy($id, Request $request)
    {

        $user = User::where('id', $id)->first();

        $user->delete();

        return redirect()->route($this->mainUrl . 'index');
    }

    public function getFriends(Request $request)
    {
        $user = User::where('id', $request->get('user_id'))->first();
        $data['resource'] = $user;
        $data['friends'] = $user->friends;
        return view($this->mainUrl . 'friends', $data);
    }

    public function getFollowers(Request $request)
    {

        $user = User::where('id', $request->get('user_id'))->first();
        $data['resource'] = $user;
        $data['followers'] = $user->myFollowers()->get();
        return view($this->mainUrl . 'followers', $data);
    }

    public function getFollowing(Request $request)
    {
        $user = User::where('id', $request->get('user_id'))->first();
        $data['resource'] = $user;
        $data['followings'] = $user->amFollow()->get();
        return view($this->mainUrl . 'following', $data);
    }
}
