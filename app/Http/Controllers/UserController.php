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
            'avatar' => 'required|string|max:255',
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

    // update profile
    public function updateProfile(Request $request){
        $user = Auth::user();
        $validator = Validator::make($request->all(),[
            'name'=>'string|max:255|nullable',
            'dob' => 'date|nullable',
            'phone_number' => 'string|max:15|regex:/^\+?[0-9]{7,15}$/|nullable',
            'address' => 'string|max:500|nullable',
            'avatar' => 'string|max:255|nullable',
        ]);
        if($validator->fails()){
            return response()->json([
                'message' => 'validation error',
                'error' => $validator->errors(),
            ],422);
        }
        $user->update([
            'name' => $request->input('name') ?? $user->name,
            'date_of_birth' => $request->input('dob') ?? $user->date_of_birth,
            'phone_number' => $request->input('phone_number') ?? $user->phone_number,
            'address' => $request->input('address') ?? $user->address,
            'avatar' => $request->input('avatar') ?? $user->avatar,
        ]);
        return response()->json([
            'message' => 'profile updated successfully',
            'data' => $user,
        ], 200);

    }

    // change password
    public function changePassword(Request  $request){
        $user = Auth::user();
        $validator = Validator::make($request->all(),[
            'old_password'=>'required|string',
            'new_password'=>'required|string|min:8',
        ]);
        if($validator->fails()){
            return response()->json([
                'message' => 'validation error',
                'error' => $validator->errors(),
            ],422);
        }
        if(!password_verify($request->input('old_password'),$user->password)){
            return response()->json([
                'message' => 'old password is incorrect',
            ],422);
        }
        $user->update([
            'password' => bcrypt($request->input('new_password')),
        ]);
        return response()->json([
            'message' => 'password changed successfully',
        ], 200);

    }
}
