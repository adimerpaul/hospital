<?php

namespace App\Http\Controllers;

use App\Models\Spent;
use App\Http\Requests\StoreSpentRequest;
use App\Http\Requests\UpdateSpentRequest;

class SpentController extends Controller
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
     * @param  \App\Http\Requests\StoreSpentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSpentRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Spent  $spent
     * @return \Illuminate\Http\Response
     */
    public function show(Spent $spent)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Spent  $spent
     * @return \Illuminate\Http\Response
     */
    public function edit(Spent $spent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSpentRequest  $request
     * @param  \App\Models\Spent  $spent
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSpentRequest $request, Spent $spent)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Spent  $spent
     * @return \Illuminate\Http\Response
     */
    public function destroy(Spent $spent)
    {
        //
    }
}
