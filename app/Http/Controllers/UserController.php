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
     * signup user
     */
    public function signup(Request $request)
    {


        $validata = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'dob' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'phone_number' => 'required|string|max:15|regex:/^\+?[0-9]{7,15}$/',
            'address' => 'required|string|max:500',
            'avator' => 'required|string|max:255',
        ]);

        if ($validata->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'error' => $validata->errors(),
            ], 422);
        }
// add avater as image which is required
        User::create([
            'name' => $request->input('name'),
            'email' =>  $request->input('email'),
            'password' =>  $request->input('password'),
            'date_of_birth' => $request->input('dob'),
            'gender' => $request->input('gender'),
            'phone_number' => $request->input('phone_number'),
            'address' => $request->input('address'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'avatar' => $request->input('avatar'),
        ]);

        return response()->json([
            'message' => 'user created successfully',
        ], 201);
    }

// login user
    public function login(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if ($validate->fails()) {
            return response()->json([
                'message' => 'validation error',
                'error' => $validate->errors(),
                'email' => $request->input('email'),
                'password' => $request->input('password')
            ]);
        }
        if (Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
            $user = Auth::user();
            return response()->json([
                'message' => 'login successful',
                'token' => $user->createToken('Api token')->plainTextToken,
            ]);
        } else {

            return response()->json([
                'message' => 'login failed',
                'error' => $request->all(),
            ]);
        }
    }

    // logout user
    public function logout(Request $request)
    {
        // return "logout";
        $user = $request->user();
        if ($user) {
            $user->tokens()->delete();
            return response()->json([
                'message' => 'logout successfully',
            ], 200);
        }
        return response()->json([
            'message' => 'logout failed',

        ], 401);
    }
}
