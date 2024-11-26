<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class AdminController extends Controller
{
    // Display a list of users pending approval
    public function approveUser($userId)
    {
        $user = User::find($userId);

        if ($user) {
            $user->approved = 1; // Approve the user
            $user->save();

            return response()->json(['message' => 'User approved successfully.'], 200);
        }

        return response()->json(['message' => 'User not found.'], 404);
    }

    public function declineUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $user->delete(); // Delete the user

        return response()->json(['message' => 'User deleted successfully']);
    }

    public function addUser(Request $request)
{
    // Validate the incoming request
    $validator = Validator::make($request->all(), [
        'firstname' => 'required|string|max:255',
        'lastname' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6|confirmed',
        'password_confirmation' => 'required_with:password',
        'role' => 'required|in:user,admin,bhw',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'message' => 'Validation failed',
            'errors' => $validator->errors(),
        ], 422);
    }

    // Get the validated data
    $validated = $validator->validated();
    $validated['password'] = Hash::make($validated['password']); // Hash the password

    // Set is_admin to true if the role is 'admin', otherwise false
    $validated['is_admin'] = $validated['role'] === 'admin';

    // Create the user with the validated data
    $user = User::create($validated);

    return response()->json([
        'message' => 'User created successfully',
        'user' => $user,
    ], 201);
}

}
