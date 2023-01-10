<?php

namespace App\Http\Controllers;

use App\Models\Number;
use App\Http\Requests\StoreNumberRequest;
use App\Http\Requests\UpdateNumberRequest;
use Illuminate\Http\Request;

class NumberController extends Controller{
    public function index(){ return Number::all(); }
    public function store(Request $request){ return Number::create($request->all()); }
    public function show(Number $number){ return $number; }
    public function update(Request $request, Number $number){ $number->update($request->all()); return $number; }
    public function destroy(Number $number){ $number->delete(); return $number; }
}
