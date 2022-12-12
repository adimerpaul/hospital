<?php

namespace App\Http\Controllers;

use App\Models\Reservations;
use App\Http\Requests\StoreReservationsRequest;
use App\Http\Requests\UpdateReservationsRequest;
use Illuminate\Http\Request;

class ReservationsController extends Controller{
    public function index(Request $request){return Reservations::where('hospital_id', $request->user()->hospital_id)->with('patient')->with('user')->get();}
    public function store(Request $request){return Reservations::create($request->all());}
    public function show(Reservations $reservation){
        return Reservations::where('id', $reservation->id)->with('patient')->with('user')->first();
    }
    public function update(Request $request, Reservations $reservation){$reservation->update($request->all());return $reservation;}
    public function destroy(Reservations $reservation){$reservation->delete();return $reservation;}
}
