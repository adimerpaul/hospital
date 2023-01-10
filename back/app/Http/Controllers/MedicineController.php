<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Http\Requests\StoreMedicineRequest;
use App\Http\Requests\UpdateMedicineRequest;
use Illuminate\Http\Request;

class MedicineController extends Controller{
    public function index(){ return Medicine::all(); }
    public function store(Request $request){ return Medicine::create($request->all()); }
    public function show(Medicine $medicine){ return $medicine; }
    public function update(Request $request, Medicine $medicine){ $medicine->update($request->all()); return $medicine; }
    public function destroy(Medicine $medicine){ $medicine->delete(); return $medicine; }
}
