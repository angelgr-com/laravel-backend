<?php

namespace App\Http\Controllers;

use App\Models\Party;
use App\Http\Requests\StorePartyRequest;
use App\Http\Requests\UpdatePartyRequest;
use App\Models\Game;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PartyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $data = Party::orderBy('name','asc')->get();
        $data = DB::table('parties')
            ->select('parties.name as party name', 'games.title as game title', 'parties.owner_id')
            ->leftJoin('games', 'games.id', '=', 'parties.game_id')
            ->get();

        return response()->json(['parties' => $data]);
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
     * @param  \App\Http\Requests\StorePartyRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePartyRequest $request)
    {
        // party(name, game_id, owner_id)
        $party = new Party;
        
        // Assign name
        $party->name = $request->party_name;

        // Assign game_id
        $game = Game::where('title', '=', $request->game_title)->first();
        $game_id = $game->id;
        $party->game_id = $game_id;

        // Assign user_id
        // while registering a new party,
        // owner_id is the logged in user
        $user = auth('api')->user();
        $party->owner_id = $user->id;

        // Save new party register
        $party->save();
        
        return response()->json([
            'message' => 'New party created successfully',
            'party' => $party,
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Party  $party
     * @return \Illuminate\Http\Response
     */
    public function show($party_name)
    {
        $party = Party::where('name', '=', $party_name)->first();
        $game = Game::find($party->game_id);
        $user = User::find($party->owner_id);

        return response()->json([
                                    'Party' => $party->name,
                                    'Game' => $game->title,
                                    'Owner' => $user->username,
                                ], 200);
    }

    public function showByGame($game_title)
    {
        $game = Game::where('title', '=', $game_title)->first();
        $party = DB::table('parties')
        ->select('parties.name as Party', 'games.title as Game', 'users.username as Owner')
        ->where('title', '=', $game_title)
        ->leftJoin('games', 'games.id', '=', 'parties.game_id')
        ->leftJoin('users', 'users.id', '=', 'parties.owner_id')
        ->orderBy('parties.name', $direction = 'asc')
        ->get();

        return response()->json($party, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Party  $party
     * @return \Illuminate\Http\Response
     */
    // public function edit(Party $party)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePartyRequest  $request
     * @param  \App\Models\Party  $party
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePartyRequest $request, Party $party, $party_name)
    {
        $party = Party::where('name', '=', $party_name)->first();
        $party->name = $request->party_name;

        // Assign game_id
        $game = Game::where('title', '=', $request->game_title)->first();
        $game_id = $game->id;
        $party->game_id = $game_id;

        // Assign user_id
        // while registering a new party,
        // owner_id is the logged in user
        $user = auth('api')->user();
        $party->owner_id = $user->id;

        $party->save();
        
        return response()->json([
            'message' => 'Party updated successfully',
            'party' => $party,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Party  $party
     * @return \Illuminate\Http\Response
     */
    public function destroy($party_name)
    {
        $party = Party::where('name', '=', $party_name)->first();
        $party->delete();

        return response()->json([
            'message' => 'Party deleted successfully'
        ], 200);
    }
}
