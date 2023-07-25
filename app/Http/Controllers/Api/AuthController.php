<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    //

    public function __construct(){
        $this->middleware('auth:api',[
            'except' => [
                'login',
                'register'
            ]
            ]);
    }

    public function register(Request $request){

        $validator = Validator::make($request->all(), [
            'name'=>'required|max:255',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:6|max:100',
            //'confirm_password'=>'required|same:password'
        ]);

        $errors = $validator->errors();

         if ($validator->fails()) {
             return response()->json([
                 'message'=>'Validation fails',
                 'errors'=>$validator->errors()
             ],422);
         }

        $user=User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password)
        ]);

        $token = Auth::login($user);

        return response()->json([
            'message'=>'User successfully registered',
            //'data'=>$user
        ],200);
    }   

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()){
            return response()->json([
                'message'=>'Validation fails',
                'errors'=>$validator->errors()
            ],422);
        }

        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);

        if(!$token) {   //token not generated or auth fail
            return response()->json([
                'message' => 'Unauthorized User: Invalid credentials'
            ],401);
        }

        $user = Auth::user();
        return response()->json([
            'access_token' => $token
        ]);
    }

    public function logout()
    {
        Auth::logout();

        return response()->json(['message' => 'Successfully logged out']);
    }
}
