<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Http\Request;

class CategoryController extends Controller{
    public function index(Request $request){return Category::where('hospital_id', $request->user()->hospital_id)->with('products')->get();}
    public function store(Request $request){return Category::create($request->all());}
    public function show(Category $category){return $category;}
    public function update(Request $request, Category $category){return $category->update($request->all());}
    public function destroy(Category $category){$category->delete();return $category;}
}
