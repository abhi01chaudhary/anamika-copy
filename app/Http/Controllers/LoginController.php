<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Auth;
use App\Models\User;

class LoginController extends Controller
{
    public function login(LoginRequest $request){
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
            $user = Auth::user();
            $token =  $user->createToken('sanctum_token')->plainTextToken;
            return response()->json(['token' => $token, 'user' => $user], 200);
        }else{
            return response()->json(['error'=>'Unauthorised'], 401);
        }
    }

    public function register(RegisterRequest $request){
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $token = $user->createToken('sanctum_token')->plainTextToken;
        return response()->json(['token' => $token, 'user' => $user], 200);
    }
}
