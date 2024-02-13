<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\MoonGift;
use Illuminate\Http\Request;
use App\Models\LuckyGiftRound;
use App\Http\Controllers\Controller;

class LuckyRoundController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $luckyRounds = LuckyGiftRound::paginate(10);
        return view('dashboard.lucky_rounds.index', compact('luckyRounds'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.lucky_rounds.create');
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
            'round_number'  => 'required|integer|unique:lucky_gift_rounds',
            'round_value'   => 'required|integer|unique:lucky_gift_rounds',
            'minimum_win'   => 'required|integer|lt:maximum_win',
            'maximum_win'   => 'required|integer|gt:minimum_win',
        ]);

        LuckyGiftRound::create($request->all());

        return redirect('dashboard/lucky-round');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(LuckyGiftRound $luckyRound)
    {
        return view('dashboard.lucky_rounds.edit', compact('luckyRound'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LuckyGiftRound $luckyRound)
    {
        $validated = $request->validate([
            'round_number'  => 'required|integer|unique:lucky_gift_rounds,round_number,'.$luckyRound->id,
            'round_value'   => 'required|integer|unique:lucky_gift_rounds,round_value,'.$luckyRound->id,
            'minimum_win'   => 'required|integer|lt:maximum_win',
            'maximum_win'   => 'required|integer|gt:minimum_win',
        ]);

        LuckyGiftRound::find($luckyRound->id)->update($request->input());

        return redirect('dashboard/lucky-round');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        LuckyGiftRound::find($id)->delete();
        return redirect('dashboard/lucky-round');
    }

}
