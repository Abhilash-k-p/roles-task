<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;

            // Fetch all role names
            $roles = $user->roles->pluck('role_name');

            return response()->json([
                'access_token' => $token,
                'roles' => $roles,
            ]);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }


    public function logout(Request $request): Application|Redirector|RedirectResponse
    {
        // Revoke all personal access tokens for the user
        $request->user()->tokens->each(function ($token) {
            $token->delete();
        });

        return redirect('/login')->with('message', 'Logged out successfully');
    }
}
