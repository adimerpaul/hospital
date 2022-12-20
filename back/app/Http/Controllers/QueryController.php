<?php

namespace App\Http\Controllers;

use App\Models\Query;
use App\Http\Requests\StoreQueryRequest;
use App\Http\Requests\UpdateQueryRequest;
use Illuminate\Http\Request;

class QueryController extends Controller{
    public function index(Request $request){return Query::where('hospital_id', $request->user()->hospital_id)->get();}
    public function store(Request $request){return Query::create($request->all());}
    public function show(Query $query){return $query;}
    public function update(Request $request, Query $query){return $query->update($request->all());}
    public function destroy(Query $query){$query->delete();return $query;}
}
