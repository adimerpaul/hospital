<?php

namespace App\Http\Controllers;

use App\Models\QueryMedicine;
use App\Http\Requests\StoreQueryMedicineRequest;
use App\Http\Requests\UpdateQueryMedicineRequest;

class QueryMedicineController extends Controller
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
     * @param  \App\Http\Requests\StoreQueryMedicineRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreQueryMedicineRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\QueryMedicine  $queryMedicine
     * @return \Illuminate\Http\Response
     */
    public function show(QueryMedicine $queryMedicine)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\QueryMedicine  $queryMedicine
     * @return \Illuminate\Http\Response
     */
    public function edit(QueryMedicine $queryMedicine)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateQueryMedicineRequest  $request
     * @param  \App\Models\QueryMedicine  $queryMedicine
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateQueryMedicineRequest $request, QueryMedicine $queryMedicine)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\QueryMedicine  $queryMedicine
     * @return \Illuminate\Http\Response
     */
    public function destroy(QueryMedicine $queryMedicine)
    {
        //
    }
}
