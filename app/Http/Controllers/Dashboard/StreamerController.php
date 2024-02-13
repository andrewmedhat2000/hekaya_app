<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\web\UserCreateRequest;
use App\Http\Requests\web\UserUpdateRequest;
use App\Imports\StreamerImport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\UserStreamer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;

class StreamerController extends Controller
{
    protected $mainUrl = 'dashboard.streamer.';
    protected $exceptionUrl = 'home';


    public function index(Request $request)
    {

        $data['roles'] = Role::whereIn('name', ['super_admin', 'admin'])->get();

        if (Auth::user()->hasanyrole('admin', 'super_admin')) {
            $users = User::whereHas('roles', function ($query) {
                $query->where('name', 'streamer');
            });
        } else {
            $users = User::whereHas('roles', function ($query) {
                $query->where('name', 'streamer');
            })->whereHas('streamerAdmins', function ($query) {
                $query->where('streamer_admin_id', Auth::user()->id);
            });
        }
        $data['users'] = $users->paginate(10);
        return view($this->mainUrl . 'index', $data);
    }

    public function create()
    {

        $streamer_admins = User::whereHas('roles', function ($query) {
            $query->where('name', 'streamer_admin');
        })->get()->pluck('name', 'id')->toArray();

        $roles = Role::whereIn('name', ['super_admin', 'admin'])->get();
        $data['roles'] = $roles;

        $data['streamer_admins'] = $streamer_admins;
        return view($this->mainUrl . 'create', $data);
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
                $user_exist->assignRole('streamer');
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
        $user->assignRole('streamer');

        if ($request->get('stream_admin_id')) {
            UserStreamer::create([
                'streamer_id' => $user->id,
                'streamer_admin_id' => $request->get('stream_admin_id'),
                'cost_per_hour' => $request->get('cost_per_hour')
            ]);
        } else {
            UserStreamer::create([
                'streamer_id' => $user->id,
                'streamer_admin_id' => Auth::user()->id,
                'cost_per_hour' => $request->get('cost_per_hour')
            ]);
        }


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


        $streamer_admins = User::whereHas('roles', function ($query) {
            $query->where('name', 'streamer_admin');
        })->get()->pluck('name', 'id')->toArray();
        $data['streamer_admins'] = $streamer_admins;

        $data['selected_streamer_admin'] = optional(optional($user->streamerAdmins->first())->pluck('id'))->toArray();
        $user->cost_per_hour = optional(optional($user->streamerAdmins->first())->pivot)->cost_per_hour;
        $data['resource'] = $user;

        $roles = Role::whereIn('name', ['super_admin', 'admin'])->get();
        $data['roles'] = $roles;

        return view($this->mainUrl . 'edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, $id)
    {
        $user = User::where('id', $id)->first();

        $data = $request->all();

        $user->update($data);

        if ($request->get('stream_admin_id')) {
            UserStreamer::updateOrCreate([
                'streamer_id' => $user->id,
                'streamer_admin_id' => $request->get('stream_admin_id'),
                'cost_per_hour' => $request->get('cost_per_hour')
            ]);
        } else {
            $streamer_admin = $user->streamerAdmins->first();
            $streamer_admin->pivot->cost_per_hour = $request->get('cost_per_hour');
            $streamer_admin->pivot->save();
        }

        return redirect()->route($this->mainUrl . 'index');
    }

    public function destroy($id, Request $request)
    {
        $user = User::where('id', $id)->first();
        $user_admin = Auth::user();

        $user_streamer = UserStreamer::where('streamer_id', $user->id)->where('streamer_admin_id', $user_admin->id)->first();

        $user_streamer->delete();
        return redirect()->route($this->mainUrl . 'index');
    }

    public function pay(Request $request)
    {
        $user = User::where('id', $request->get('user_id'))->first();
        $admin_id = $request->get('admin_id');
        $user_streamers = UserStreamer::where('streamer_id', $user->id)->where('streamer_admin_id', $admin_id)->first();
        $user_streamer_month = $user_streamers->streamerInfoThisMonth;

        foreach ($user_streamer_month as $user_streamer) {
            $user_streamer->delete();
        }
        return redirect()->back();
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

    public function importView(Request $request)
    {
        $streamer_admins = User::whereHas('roles', function ($query) {
            $query->where('name', 'streamer_admin');
        })->get()->pluck('name', 'id')->toArray();

        return view($this->mainUrl . 'import', compact('streamer_admins'));
    }

    public function import(Request $request)
    {
        $streamer_admin_role_id = Role::where('name', 'streamer_admin')->firstOrFail()->id;

        $request->validate([
            'file' => 'required|file|mimes:xlsx',
            'streamer_admin_id' => 'required|exists:model_has_roles,model_id,role_id,'.$streamer_admin_role_id,
        ]);

        Excel::import(new StreamerImport, $request->file('file'));

        return redirect()->route('dashboard.user.streamers');
    }
}
