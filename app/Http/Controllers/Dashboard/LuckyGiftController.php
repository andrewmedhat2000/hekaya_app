<?php

namespace App\Http\Controllers\Dashboard;

use Carbon\Carbon;
use App\Models\Gift;
use Illuminate\Http\Request;
use App\Models\LuckyGiftScore;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class LuckyGiftController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $luckyGifts = Gift::where('game_gift', Gift::GAME_GIFTS['lucky_gift'])->orderBy("price", "asc")->paginate(10);
        return view('dashboard.lucky_gift.index', compact('luckyGifts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.lucky_gift.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'  => 'required|max:225',
            'price' => 'required|integer',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'gif'   => 'required|image|mimes:gif',
        ]);

        $request->request->add(['game_gift' => Gift::GAME_GIFTS['lucky_gift']]);
        Gift::create($request->all());

        return redirect('dashboard/lucky-gift');

    }

    public function show(Request $request)
    {
        if (!request()->date) {
            $filter_date = "month";
        } else {
            $filter_date = request()->date;
        }

        $lucky_gift_score = LuckyGiftScore::get();  
        $players_number = $lucky_gift_score->count();
        $diamond_win = $lucky_gift_score->sum('points');
        $diamond_lost = $lucky_gift_score->sum('round_value');  
        $rounds_number = $lucky_gift_score->sum('rounds_number');
        
        $data = $lucky_gift_score->groupBy(function($date) {
            if (request()->date == "day") {
                return Carbon::parse($date->created_at)->format('Y - m - d');
            } else {
                return Carbon::parse($date->created_at)->format('Y-m');
            }
        });

        return view('dashboard.lucky_gift.view', compact('filter_date', 'data', 'players_number', 'diamond_win', 'diamond_lost', 'rounds_number'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Gift $luckyGift)
    {
        return view('dashboard.lucky_gift.edit', compact('luckyGift'));
    }

    /**
     * Update thmoon_gifte specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Gift $luckyGift)
    {
        $validated = $request->validate([
            'name'  => 'required|max:225',
            'price' => 'required|integer',
            'image' => 'image|mimes:jpeg,png,jpg,svg',
            'gif'   => 'image|mimes:gif',
        ]);
        Gift::find($luckyGift->id)->update($request->all());
        return redirect('dashboard/lucky-gift');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Gift::find($id)->delete();
        return redirect('dashboard/lucky-gift');
    }

    public function reset()
    {
        DB::table('lucky_gift_scores')->update([
            'points'   => 0,
            'round_value'  => 0,
            'rounds_number' => 0,
        ]);

        return redirect('dashboard/lucky-gift/show');
    }

}
