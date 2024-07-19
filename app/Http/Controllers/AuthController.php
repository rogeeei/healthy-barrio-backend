<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\UserRequest;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Log in to the specified resource.
     */
    public function login(UserRequest $request)
    {
        $user = User::where('user_id', $request->user_id)
            ->where('role', $request->role)
            ->where('brgy', $request->brgy)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'user_id' => ['The provided credentials are incorrect.'],
            ]);
        }

        $response = [
            'user'  => $user,
            'token' => $user->createToken($request->user_id)->plainTextToken
        ];

        return $response;
    }

    /**
     * Log out to the specified resource.
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        $response = [
            'message' => 'Logout'
        ];
        return $response;
    }
}
