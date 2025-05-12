<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Auth\LoginRequest;
use App\Http\Requests\Api\v1\Auth\RegisterRequest;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    use ApiResponseTrait;

    public function register(RegisterRequest $request)
    {
        try {
            $validated = $request->validated();

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            $token = JWTAuth::fromUser($user);

            return $this->successResponse(compact('user', 'token'), 'User registered successfully', 201);
        } catch (\Exception $e) {
            return $this->errorResponse('User registration failed', 500, $e->getMessage());
        }
    }
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return $this->errorResponse('Invalid credentials', 401);
            }

            // Get the authenticated user.
            $user = auth()->user();


            return $this->successResponse(compact('token'), 'User logged in successfully');
        } catch (JWTException $e) {
            return $this->errorResponse('User registration failed', 500, $e->getMessage());
        }
    }
    public function logout(Request $request)
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return $this->successResponse([], 'User logged out successfully');
        } catch (JWTException $e) {
            return $this->errorResponse('User logout failed', 500, $e->getMessage());
        }
    }
}
