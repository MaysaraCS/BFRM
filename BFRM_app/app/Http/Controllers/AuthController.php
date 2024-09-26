<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $req)
    {
        //validate
        $rules =[
            'name'=>'required|string',
            'email'=>'required|string|unique:users',
            'password'=>'required|string|min:6',
        ];
        $validator = Validator::make($req->all(), $rules);
        if(validator->fails()){
            return response()->json($validator->errors(), 400);
        }
        // create new user in the user table 
        $user = User::create([
            'name'=>$req->name,
            'email'=>$req->email,
            'password'=>Hash::make($req->password),
        ]);
        $token = $user->createToken('Personal Access Token')->plainTextToken;
        $response = ['user'=>$user, 'token'=>$token];
        return response()->json($response, 200);
    }
}
