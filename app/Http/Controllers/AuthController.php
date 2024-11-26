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
    public function login(Request $request)
    {
        // Validate incoming request data
        $validated = $request->validate([
            'user_id' => 'required|string',
            'brgy' => 'required|string',
            'role' => 'required|string',
            'password' => 'required|string',
        ]);

        // Retrieve the validated data
        $data = $validated;

        // Attempt to find the user based on user_id, brgy, role, and password
        $user = User::where('user_id', $data['user_id'])
            ->where('brgy', $data['brgy'])
            ->where('role', $data['role'])
            ->first();

        // Check if user exists and if the password matches
        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Check if the user is approved unless they are an admin
        if ($user->role !== 'admin' && !$user->approved) {
            return response()->json(['message' => 'Your account is pending approval by an admin.'], 403);
        }

        // Generate the token for the authenticated user
        $token = $user->createToken('User Token')->plainTextToken;

        // Prepare the response
        $response = [
            'token' => $token,
            'data' => [
                'role' => $user->role,
                'user_id' => $user->user_id,
            ],
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
