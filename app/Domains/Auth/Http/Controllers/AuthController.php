<?php

namespace App\Domains\Auth\Http\Controllers;

use App\Domains\Auth\Actions\AuthUser;
use App\Domains\Auth\Actions\Logout;
use App\Domains\Auth\Exceptions\InvalidCredentialsException;
use App\Domains\Auth\Http\Requests\AuthUserRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function login(AuthUserRequest $request, AuthUser $action)
    {

        try {
            $tokenDTO = $action->handle($request->validated('email'), $request->validated('password'));
        } catch(InvalidCredentialsException $e){
            return response()->json(['error' => $e->getMessage()], 401);
        }catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }

        return response()->json($tokenDTO);
    }

    public function logout(Logout $action)
    {
        try {
            $action->handle();
        } catch (JWTException $e) {
            return response()->json(['error' => 'Failed to logout, please try again'], 500);
        }
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function getUser()
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }
            return response()->json($user);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Failed to fetch user profile'], 500);
        }
    }
}