<?php

namespace App\Http\Controllers;

use App\Models\Lot;
use App\Http\Requests\StoreLotRequest;
use App\Http\Requests\UpdateLotRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class LotController extends Controller
{
    public function index(Request $request){return Lot::where('hospital_id', $request->user()->hospital_id)->get();}
    public function store(Request $request){
        $product = Product::find($request->product_id);
        $product->stock = $product->stock + $request->quantity;
        $product->save();
        return Lot::create($request->all());
    }
    public function show(Lot $lot){return $lot;}
    public function update(Request $request, Lot $lot){$lot->update($request->all());return $lot;}
    public function destroy(Lot $lot){$lot->delete();return $lot;}
}
