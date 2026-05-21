<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller {
    public function register(Request $request)
    {
        $data = $request->json()->all();

        $validator = validator($data, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:5'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'error_message' => $validator->errors()])->setStatusCode(400);
        }

        if (User::where('email', $data['email'])->count() == 1) {
            return response()->json(['success' => false, 'error_message' => 'Email already registered'], 400);
        }

        $data['email_verified_at'] = now();

        $user = new User($data);
        $user->password = Hash::make($data['password']);
        $user->save();

        return response()->json([
            'success' => true,
            'user' => $user
        ])->setStatusCode(201);
    }

     public function login(Request $request)
    {
        $credentials = $request->json()->all();

        $validator = validator($credentials, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'error_message' => $validator->errors()])->setStatusCode(400);
        }

        if (!$token = auth()->guard('api')->attempt($credentials)) {
            return response()->json(['success' => false, 'error_message' => 'Unauthorized, username or password incorrect'], 401);
        }

        $user = auth()->guard('api')->user();

        return response()->json([
            'success' => true,
            'token' => $token,
            'user' => $user
        ])->setStatusCode(200);
    }

        public function logout(Request $request)
    {
        $removeToken = JWTAuth::invalidate(JWTAuth::getToken());

        if($removeToken) {
            //return response JSON
            return response()->json([
                'success' => true,
                'message' => 'Logout Successful!',
            ]);
        }
    }
}