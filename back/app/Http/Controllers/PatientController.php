<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use Illuminate\Http\Request;

class PatientController extends Controller{
    public function index(Request $request){
        return Patient::where('hospital_id', $request->user()->hospital_id)->orderBy('id', 'desc')->get();
    }
    public function store(Request $request){
        $patient = new Patient();
        $patient->name = $request->name==null||$request->name==''?'':$request->name;
        $patient->lastname = $request->lastname==null||$request->lastname==''?'':$request->lastname;
        $patient->sex = $request->sex==null||$request->sex==''?'':$request->sex;
        $patient->phone = $request->phone==null||$request->phone==''?'':$request->phone;
        $patient->email = $request->email==null||$request->email==''?'':$request->email;
        $patient->address = $request->address==null||$request->address==''?'':$request->address;
        $patient->avatar = $request->avatar==null||$request->avatar==''?'':$request->avatar;
        $patient->birthday = $request->birthday==null||$request->birthday==''?'':$request->birthday;
        $patient->hospital_id = $request->hospital_id==null||$request->hospital_id==''?'':$request->hospital_id;
        $patient->save();
        return $patient;
    }
    public function show(Patient $patient){return $patient;}
    public function update(Request $request, Patient $patient){return $patient->update($request->all());}
    public function destroy(Patient $patient){$patient->delete();return $patient;}
}
