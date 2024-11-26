<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\Validator;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $user = User::all();
        return response()->json($user);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        // Retrieve the validated input data
        $validated = $request->validated();

        // Check if the 'role' is 'user'
        if (isset($validated['role']) && $validated['role'] === 'user') {
            // If the 'approved' field is not set or false, set the user as unapproved and return a waiting for approval message
            if (!isset($validated['approved']) || !$validated['approved']) {
                // Set the 'approved' field to false if it's not provided
                $validated['approved'] = false;

                // Create the user with the 'approved' field set to false
                $user = User::create($validated);

                return response()->json([
                    'message' => 'Your account is waiting for the approval of the admin.',
                    'user' => $user
                ], 201); // Account created, but awaiting approval
            }
        }

        // If the password is provided, hash it
        $validated['password'] = Hash::make($validated['password']);

        // Create the user
        $user = User::create($validated);

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user
        ], 201);
    }




    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return User::findOrFail($id);
    }


    /**
     * Update the password of the specified resource in storage.
     */
    public function password(UserRequest $request, string $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validated();

        $user->password = Hash::make($validated['password']);

        $user->save();

        return $user;
    }


    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, string $id)
{
    // Find the user by ID or fail with a 404
    $user = User::findOrFail($id);

    // Validate the incoming data
    $validated = $request->validate([
        'firstname' => 'nullable|string|max:255',
        'middle_name' => 'nullable|string|max:255',
        'lastname' => 'nullable|string|max:255',
        'suffix' => 'nullable|string|max:255',
        'email' => 'nullable|email|max:255|unique:users,email,' . $id . ',user_id', // Ignore current user
        'phone_number' => 'nullable|string|max:20|unique:users,phone_number,' . $id . ',user_id', // Ignore current user
        'birthdate' => 'nullable|date',
        'brgy' => 'nullable|string|max:255',
        'role' => 'nullable|string|max:255',
        'image_path' => 'nullable|string|max:255',
        'password' => 'nullable|string|min:8|confirmed', // Password confirmation required
    ]);

    // Update the user's data with validated values
    $user->update([
        'firstname' => $validated['firstname'] ?? $user->firstname,
        'middle_name' => $validated['middle_name'] ?? $user->middle_name,
        'lastname' => $validated['lastname'] ?? $user->lastname,
        'suffix' => $validated['suffix'] ?? $user->suffix,
        'email' => $validated['email'] ?? $user->email,
        'phone_number' => $validated['phone_number'] ?? $user->phone_number,
        'birthdate' => $validated['birthdate'] ?? $user->birthdate,
        'brgy' => $validated['brgy'] ?? $user->brgy,
        'role' => $validated['role'] ?? $user->role,
        'image_path' => $validated['image_path'] ?? $user->image_path,
        'password' => isset($validated['password']) ? bcrypt($validated['password']) : $user->password,
    ]);

    // Return the updated user data as a JSON response
  return response()->json([
  'success' => true,
  'user' => $user->only(['firstname', 'middle_name', 'lastname', 'email', 'phone_number', 'birthdate', 'brgy', 'role', 'image_path']),
], 200);

}


    public function getUserDetails()
    {
        // Check if user is authenticated
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        // Return the necessary user details
        return response()->json([
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'barangay' => $user->brgy,  // Renamed to match the frontend's expected key
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return $user;
    }
}
