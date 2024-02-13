<?php

namespace App\Http\Controllers\Dashboard;

use App\Exports\StreamersExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\web\UserCreateRequest;
use App\Http\Requests\web\UserUpdateRequest;
use App\Models\CurrencyWorth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\UserStreamerInfo;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class StreamerAdminReportingController extends Controller
{
    protected $mainUrl = 'dashboard.streameradminreporting.';
    protected $exceptionUrl = 'home';


    public function index(Request $request, $raw = false)
    {
        $first_day_of_month = config('streamers.first_day_of_month', 1);

        $data['months'] = Cache::remember('streamer_month_list', 60 * 60, function() use ($first_day_of_month){
            return UserStreamerInfo::selectRaw("date(created_at) AS date")->distinct('date')->get()
            ->mapWithKeys(function($info) use($first_day_of_month) {
                $month = Carbon::parse($info->date)->addDays(1-$first_day_of_month)->format('Y-m');
                return [$month => $month];
            })->toArray();
        });

        $date = $request->filled('month')
            ? Carbon::parse($request->month)->addDays($first_day_of_month-1)
            : today();

        $start_of_month = $date->day >= $first_day_of_month
            ? $date->setDay($first_day_of_month)
            : $date->addMonths(-1)->setDay($first_day_of_month);

        $info = UserStreamerInfo::query()->with('streamer.streamer', 'streamer.streamerAdmin')
            ->selectRaw('user_streamer_id, DATE(created_at) as date, SUM(mins) as mins, SUM(gold) as gold')
            ->groupBy('user_streamer_id', 'date')
            ->having('date', '>=', $start_of_month)->having('date', '<', $start_of_month->clone()->addMonth())
            ->whereHas('streamer', function($query) use($request) {
                $query->whereNull('deleted_at')->when($request->filled('admin_id'),
                    fn($q) => $q->where('streamer_admin_id', $request->admin_id)
                );
            })->get();

        $data['users'] = $info->groupBy('user_streamer_id')->map(function($group) {
            $streamer = $group->first()->streamer;
            $user = clone $streamer->streamer;
            $streamer->setRelation('streamerInfoThisMonth', $group);
            return $user->setRelation('currentStreamerAdminsPivot', $streamer);
        })->sortByDesc(function($user) {
            return $user->getMonthlyGolds();
        });

        $data['admins'] = $data['users']->pluck('currentStreamerAdminsPivot.streamerAdmin')->pluck('name', 'id');

        $data['diamonds_per_dollar'] = ($ratio = CurrencyWorth::first()) ? $ratio->diamond / $ratio->price : 1;

        $result = $raw ? $data : view($this->mainUrl . 'streamer_index', $data)->render();

        return $result;
    }


    public function export_all_mapping(Request $request)
    {
        $collection = $this->index($request, true)['users'];

        return Excel::download(new StreamersExport($collection), 'Streamers ' . Carbon::now()->toDateTimeString() . '.xlsx');
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
    public function show($admin_id)
    {
        $users = User::whereHas('roles', function ($query) use ($admin_id) {
            $query->where('name', 'streamer');
        })->whereHas('streamerAdmins', function ($query) use ($admin_id) {
            $query->where('streamer_admin_id', $admin_id);
        });



        // $users->each(function ($item, $key) use ($admin_id) {
        //     $item['hours'] = $item->whereHas('streamerAdminsPivot', function ($query) use ($admin_id) {
        //         $query->where('streamer_admin_id', $admin_id)->whereHas('streamerInfo', function ($query) use ($admin_id) {
        //             $now = Carbon::now();
        //             $month = $now->month;
        //             $query->whereMonth('created_at','=', $month)->sum('mins');
        //         });
        //     });
        // });

        $data['users'] = $users->paginate(10);
        return view($this->mainUrl . 'streamer_index', $data);
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
