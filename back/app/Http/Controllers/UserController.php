<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name'=>'required|string',
            'email'=>'required|string|email|unique:users',
            'password'=>'required|string|confirmed|min:6',
            'sex'=>'required|string',
            'specialty'=>'required|string',
            'ci'=>'required|string',
            'nameHospital'=>'required|string',
            'address'=>'required|string',
            'phone'=>'required|string',
            'web'=>'required|string',
            'type'=>'required|string',
        ]);
        $hospital = Hospital::create([
            'nameHospital'=>$request->nameHospital,
            'address'=>$request->address,
            'phone'=>$request->phone,
            'web'=>$request->web,
        ]);

        $user=User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>bcrypt($request->password),
            'type'=>'DOCTOR',
            'sex'=>$request->sex,
            'specialty'=>$request->specialty,
            'ci'=>$request->ci,
            'hospital_id'=>$hospital->id,
        ]);
        $token = $user->createToken('web')->plainTextToken;
        return response()->json([
            'user'=>$user->with('hospital')->find($user->id),
            'token'=>$token
        ],201);
    }
    public function me(Request $request){
        return User::with('hospital')->find($request->user()->id);
    }
    public function logout(Request $request){
        $request->user()->tokens()->delete();
        return response()->json([
            'message'=>'Logout'
        ]);
    }
    public function login(){
        $credentials = request(['email','password']);
        if(!auth()->attempt($credentials)){
            return response()->json([
                'message'=>'Credenciales incorrectas'
            ],401);
        }
        $user = User::with('hospital')->find(auth()->user()->id);
        $token = auth()->user()->createToken('web')->plainTextToken;
        return response()->json([
            'user'=>$user,
            'token'=>$token
        ]);
    }
}
