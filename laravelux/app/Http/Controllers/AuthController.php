<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'API projet Ynov laravel'
)]
/**
 * @OA\Post(
 *     path="/api/login",
 *     summary="login",
 *     tags={"User"},
 *     @OA\Response(response=200, description="token")
 * )
 *  @OA\Post(
 *     path="/api/logout",
 *     summary="logout",
 *     tags={"User"},
 *     @OA\Response(response=200, description="message => Successfully logged out")
 * )
 */
class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $token = auth()->user()->createToken('conn')->plainTextToken;
            return response(['token' => $token], 200);
        } else {
            return response(['error' => 'Unauthorized'], 401);
        }
    }
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response(['message' => 'Successfully logged out'], 200);
    }
    public function getUserInfo(Request $request){
        $user = $request->user();
        if ($user) {
            return response(['user' => $user], 200);
        } else {
            return response(['error' => 'Unauthorized'], 401);
        }
    }
}
