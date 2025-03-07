<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
   

  

    /**
     * Store a newly created resource in storage.
     */
    public function signup(Request $request)
    {
        
        
        $validata = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);
      
        if($validata->fails()){
            return response()->json([
                'message' => 'Validation failed',
                'error' => $validata->errors(),
            ],422);
        }
       
         User::create([
            'name' => $request->input('name'),
            'email' =>  $request->input('email'),
            'password' =>  $request->input('password'),
            'email_verified_at' => Carbon::now()]);

        return response()->json([
            'message'=> 'user created successfully',
        ],201);
    }


    public function login(Request $request){
        $validate = Validator::make($request->all(),[
'email' => 'required|email',
        'password' => 'required'
        ]);
        if($validate->fails()){
            return response()->json([
                'message'=>'validation error',
                'error' => $validate->errors(),
                'email' => $request->input('email'),
                'password' => $request->input('password') 
                      ]);
        }
        if(Auth::attempt(['email'=>$request->input('email'),'password'=>$request->input('password')])){
            $user = Auth::user();
            return response()->json([
                'message'=>'login successful',
                'token'=> $user->createToken('Api token')->plainTextToken,
            ]);
        }
        else {

            return response()->json([
                'message'=>'login failed',
                'error' => $request->all(),
            ]);
        }
    }

    public function logout(Request $request){
        $user = $request->user();
        if($user){
            $user->tokens()->delete();
            return response()->json([
                'message'=> 'logout successfully',
            ],200);

        }
        return response()->json([
            'message'=>'logout failed',

        ],401);
    }
    



    
}
