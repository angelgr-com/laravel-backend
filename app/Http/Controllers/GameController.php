<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Http\Requests\StoreGameRequest;
use App\Http\Requests\UpdateGameRequest;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Game::orderBy('title','asc')->paginate(10);

        return response()->json(['games' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function create()
    // {
    //     //
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreGameRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreGameRequest $request)
    {
        $game = new Game;
        $game->title = $request->title;
        $game->thumbnail_url = $request->thumbnail_url;
        $game->url = $request->url;
        $game->save();
        
        return response()->json([
            'message' => 'New game created successfully',
            'game' => $game,
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function show($game_title)
    {
        $game = Game::where('title', '=', $game_title)->first();

        return response()->json([$game], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Game  $game
     * @return \Illuminate\Http\Response
     */
    // public function edit(Game $game)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateGameRequest  $request
     * @param  \App\Models\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateGameRequest $request, Game $game, $game_title)
    {
        $game = Game::where('title', '=', $game_title)->first();
        $game->title = $request->title;
        $game->thumbnail_url = $request->thumbnail_url;
        $game->url = $request->url;
        $game->save();
        
        return response()->json([
            'message' => 'Game updated successfully',
            'game' => $game,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function destroy($game_title)
    {
        $game = Game::where('title', '=', $game_title)->first();
        $game->delete();

        return response()->json([
            'message' => 'Game deleted successfully'
        ], 200);
    }
}
