<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use Illuminate\Http\Request;

class PatientController extends Controller{
    public function index(Request $request){return Patient::where('hospital_id', $request->user()->hospital_id)->get();}
    public function store(Request $request){return Patient::create($request->all());}
    public function show(Patient $patient){return $patient;}
    public function update(Request $request, Patient $patient){return $patient->update($request->all());}
    public function destroy(Patient $patient){$patient->delete();return $patient;}
}
