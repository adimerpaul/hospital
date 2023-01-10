<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Http\Requests\StoreUnitRequest;
use App\Http\Requests\UpdateUnitRequest;
use Illuminate\Http\Request;

class UnitController extends Controller{
    public function index(){ return Unit::all(); }
    public function store(Request $request){ return Unit::create($request->all()); }
    public function show(Unit $unit){ return $unit; }
    public function update(Request $request, Unit $unit){ $unit->update($request->all()); return $unit; }
    public function destroy(Unit $unit){ $unit->delete(); return $unit; }
}
