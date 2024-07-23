<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\Validator;

class UserController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        // Retrieve the validated input data...
        $validated = $request->validated();

        $validated['password'] = Hash::make($validated['password']);

        //     // Create a new user with the approved status set to false
        $user = User::create([
            'firstname' => $request->firstname,
            'middle_name' => $request->middle_name,
            'lastname' => $request->lastname,
            'suffix' => $request->suffix,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'birthdate' => $request->birthdate,
            'brgy' => $request->brgy,
            'role' => $request->role,
            'password' => Hash::make($request->password),
            'approved' => false, // Set approved to false by default
            'image_path' => $request->image_path,
        ]);

        return $user;
        return response()->json(['message' => 'Registration successful. Awaiting admin approval.']);
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
    public function update(UserRequest $request, string $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validated();

        $user->lastname = $validated['lastname'];

        $user->save();

        return $user;
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

    /**
     * Admin.
     */
    public function approve(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->approved = true;
        $user->save();

        return response()->json(['message' => 'User approved successfully'], 200);
    }


    /**
     * Register a new user.
     */
    // public function store(UserRequest $request)
    // {
    //     // Create a new user with the approved status set to false
    //     $user = User::create([
    //         'firstname' => $request->firstname,
    //         'middle_name' => $request->middle_name,
    //         'lastname' => $request->lastname,
    //         'suffix' => $request->suffix,
    //         'email' => $request->email,
    //         'phone_number' => $request->phone_number,
    //         'birthdate' => $request->birthdate,
    //         'brgy' => $request->brgy,
    //         'role' => $request->role,
    //         'password' => Hash::make($request->password),
    //         'approved' => false, // Set approved to false by default
    //         'image_path' => $request->image_path,
    //     ]);

    //     return response()->json(['message' => 'Registration successful. Awaiting admin approval.']);
    // }
}
