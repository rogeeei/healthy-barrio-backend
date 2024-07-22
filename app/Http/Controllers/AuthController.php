<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Log in to the specified resource.
     */
    public function login(UserRequest $request)
    {
        // Retrieve the request data
        $data = $request->only('user_id', 'brgy', 'role', 'password');

        // Attempt to find the user based on user_id, brgy, role, and password
        $user = User::where('user_id', $data['user_id'])
            ->where('brgy', $data['brgy'])
            ->where('role', $data['role'])
            ->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Check if the user is approved unless they are an admin
        if ($user->role !== 'admin' && !$user->approved) {
            return response()->json(['message' => 'Your account is pending approval by an admin.'], 403);
        }

        // Generate the token for the authenticated user
        $token = $user->createToken($data['user_id'])->plainTextToken;

        // Prepare the response
        $response = [
            'user'  => $user,
            'token' => $token,
        ];

        // Return the successful login response
        return response()->json(['message' => 'Login successful', 'data' => $response], 200);
    }

    /**
     * Log out of the specified resource.
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        $response = [
            'message' => 'Logout successful',
        ];
        return response()->json($response, 200);
    }
}
