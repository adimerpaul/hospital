<?php

namespace App\Http\Controllers;

use App\Models\Lot;
use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\Request;

class ProductController extends Controller{
    public function index(Request $request){return Product::where('hospital_id', $request->user()->hospital_id)->with('lots')->with('category')->get();}
    public function store(Request $request){
        $product = Product::create($request->all());
        $lote = Lot::create([
            'product_id' => $product->id,
            'hospital_id' => $request->user()->hospital_id,
            'quantity' => $request->stock,
            'priceCost' => $request->priceCost,
            'priceSale' => $request->priceSale,
            'expiration' => $request->expiration,
            'batch' => $request->batch,
        ]);
        return $product->with('lots')->get();
    }
    public function show(Product $product){return $product;}
    public function update(Request $request, Product $product){
        return $product->update($request->all());
    }
    public function destroy(Product $product){$product->delete();return $product;}
}
