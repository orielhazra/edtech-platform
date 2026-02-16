<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Register
    |--------------------------------------------------------------------------
    */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role'     => 'required|in:admin,instructor,student',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        $token = JWTAuth::fromUser($user);

        return $this->respondWithToken($token, 201);
    }

    /*
    |--------------------------------------------------------------------------
    | Login
    |--------------------------------------------------------------------------
    */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $validator = Validator::make($credentials, [
            'email'    => 'required|email',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        return $this->respondWithToken($token);
    }

    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */
    public function logout()
    {
        auth('api')->logout();

        return response()->json([
            'status' => true,
            'message' => 'Successfully logged out'
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Refresh Token
    |--------------------------------------------------------------------------
    */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    /*
    |--------------------------------------------------------------------------
    | Get Authenticated User
    |--------------------------------------------------------------------------
    */
    public function me()
    {
        return response()->json(auth('api')->user());
    }

    /*
    |--------------------------------------------------------------------------
    | Token Response Structure
    |--------------------------------------------------------------------------
    */
    protected function respondWithToken($token, $statusCode = 200)
    {
        return response()->json([
            'status' => true,
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth('api')->factory()->getTTL() * 60
        ], $statusCode);
    }
}

