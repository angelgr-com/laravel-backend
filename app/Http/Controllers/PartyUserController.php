<?php

namespace App\Http\Controllers;

use App\Models\Party_User;
use App\Http\Requests\StoreParty_UserRequest;
use App\Http\Requests\UpdateParty_UserRequest;

class PartyUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreParty_UserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreParty_UserRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Party_User  $party_User
     * @return \Illuminate\Http\Response
     */
    public function show(Party_User $party_User)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Party_User  $party_User
     * @return \Illuminate\Http\Response
     */
    public function edit(Party_User $party_User)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateParty_UserRequest  $request
     * @param  \App\Models\Party_User  $party_User
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateParty_UserRequest $request, Party_User $party_User)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Party_User  $party_User
     * @return \Illuminate\Http\Response
     */
    public function destroy(Party_User $party_User)
    {
        //
    }
}
