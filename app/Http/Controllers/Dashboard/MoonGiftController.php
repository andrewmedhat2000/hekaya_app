<?php

namespace App\Http\Controllers\Dashboard;

use Carbon\Carbon;
use App\Models\MoonGift;
use Illuminate\Http\Request;
use App\Models\MoonGiftScore;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class MoonGiftController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $moonGifts = MoonGift::paginate(10);
        return view('dashboard.moon_gift.index', compact('moonGifts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.moon_gift.create');
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
            'round_number' => 'required|integer|unique:moon_gifts',
            'round_value' => 'required|integer|unique:moon_gifts',
            'minimum_win' => 'required|integer|lt:maximum_win',
            'maximum_win' => 'required|integer|gt:minimum_win',
        ]);

        MoonGift::create($request->input());

        return redirect('dashboard/moon-gift');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        if (!request()->date) {
            $filter_date = "month";
        } else {
            $filter_date = request()->date;
        }

        $lucky_gift_score = MoonGiftScore::get();  
        $players_number = $lucky_gift_score->count();
        $diamond_win = $lucky_gift_score->sum('diamond_win');
        $diamond_lost = $lucky_gift_score->sum('diamond_lost');  
        $rounds_number = $lucky_gift_score->sum('rounds_number');
        $data = $lucky_gift_score->sortBy('created_at')->groupBy(function($date) {
            if (request()->date == "day") {
                return Carbon::parse($date->created_at)->format('Y - m - d');
            } else {
                return Carbon::parse($date->created_at)->format('Y-m');
            }
        });
        return view('dashboard.moon_gift.view', compact('filter_date', 'data', 'players_number', 'diamond_win', 'diamond_lost', 'rounds_number'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(MoonGift $moonGift)
    {
        return view('dashboard.moon_gift.edit', compact('moonGift'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MoonGift $moonGift)
    {
        $validated = $request->validate([
            'round_number' => 'required|integer|unique:moon_gifts,round_number,'.$moonGift->id,
            'round_value' => 'required|integer|unique:moon_gifts,round_value,'.$moonGift->id,
            'minimum_win' => 'required|integer|lt:maximum_win',
            'maximum_win' => 'required|integer|gt:minimum_win',
        ]);

        MoonGift::find($moonGift->id)->update($request->input());

        return redirect('dashboard/moon-gift');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        MoonGift::find($id)->delete();
        return redirect('dashboard/moon-gift');
    }

    public function reset()
    {
        DB::table('moon_gift_scores')->update([
            'diamond_win'   => 0,
            'diamond_lost'  => 0,
            'rounds_number' => 0,
        ]);

        return redirect('dashboard/moon-gift/show');
    }
}
