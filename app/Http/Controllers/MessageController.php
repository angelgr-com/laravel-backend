<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Http\Requests\StoreMessageRequest;
use App\Http\Requests\UpdateMessageRequest;
use App\Models\Party;
use DateTime;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Message::orderBy('date','asc')->paginate(10);

        return response()->json(['messages' => $data]);
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
     * @param  \App\Http\Requests\StoreMessageRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMessageRequest $request)
    {
        $now = new DateTime();
        $now = $now->format('Y-m-d H:i:s');

        $message = new Message();
        $message->from = $request->from;
        $message->message = $request->message;
        $message->date = $now;
        $message->party_id = $request->party_id;
        $message->save();
        
        return response()->json([
            'message' => 'New message created successfully',
            'game' => $message,
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function show($uuid)
    {
        $message = Message::find($uuid);

        return response()->json([$message], 200);
    }

    public function showPartyMessages($party_name)
    {
        $party = Party::where('name', '=', $party_name)->first();

        $messages = DB::table('messages')
            ->select('messages.message as message', 'users.username as user')
            ->where('party_id', '=', $party->id)
            ->leftJoin('users', 'users.id', '=', 'messages.from')
            ->orderBy('messages.date', $direction = 'asc')
            ->get();

        return response()->json($messages, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    // public function edit(Message $message)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateMessageRequest  $request
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMessageRequest $request, Message $message)
    {
        $message = Message::find($request->uuid);
        $message->message = $request->message;
        $message->save();
        
        return response()->json([
            'message' => 'Message updated successfully',
            'game' => $message,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function destroy($uuid)
    {
        $message = Message::find($uuid);
        $message->delete();

        return response()->json([
            'message' => 'Message deleted successfully'
        ], 200);
    }
}
