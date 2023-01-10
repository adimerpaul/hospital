<?php

namespace App\Http\Controllers;

use App\Models\Time;
use App\Http\Requests\StoreTimeRequest;
use App\Http\Requests\UpdateTimeRequest;
use Illuminate\Http\Request;

class TimeController extends Controller{
    public function index(){ return Time::all(); }
    public function store(Request $request){ return Time::create($request->all()); }
    public function show(Time $time){ return $time; }
    public function update(Request $request, Time $time){ $time->update($request->all()); return $time; }
    public function destroy(Time $time){ $time->delete(); return $time; }
}
