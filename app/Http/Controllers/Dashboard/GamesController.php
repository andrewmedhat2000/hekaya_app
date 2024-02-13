<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Game;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GamesController extends Controller
{
      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $games = Game::paginate(10);
        return view('dashboard.games.index', compact('games'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.games.create');
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
            'title' => 'nullable|max:225|unique:games',
            'link'  => 'required|unique:games',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
        ]);
        Game::create($request->input());

        return redirect('dashboard/games-slider');
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
    public function edit(Game $game, $id)
    {
        $game = Game::find($id);
        return view('dashboard.games.edit', compact('game'));
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
        $validated = $request->validate([
            'title'     => 'nullable|max:225|unique:games,title,'.$id,
            'link'      => 'required|unique:games,link,'.$id,
            'image'     => 'image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        Game::find($id)->update($request->all());
        return redirect('dashboard/games-slider');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Game::find($id)->delete();
        return redirect('dashboard/games-slider');
    }

}
