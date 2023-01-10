<?php

namespace App\Http\Controllers;

use App\Models\Via;
use App\Http\Requests\StoreViaRequest;
use App\Http\Requests\UpdateViaRequest;
use Illuminate\Http\Request;

class ViaController extends Controller{
    public function index(){ return Via::all(); }
    public function store(Request $request){ return Via::create($request->all()); }
    public function show(Via $via){ return $via; }
    public function update(Request $request, Via $via){ $via->update($request->all()); return $via; }
    public function destroy(Via $via){ $via->delete(); return $via; }
}
