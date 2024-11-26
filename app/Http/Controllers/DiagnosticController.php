<?php

namespace App\Http\Controllers;

use App\Http\Requests\DiagnosticRequest;
use App\Models\Diagnostic;
use Illuminate\Http\Request;

class DiagnosticController extends Controller
{
    public function index()
    {
        $equipment = Diagnostic::all();
        return response()->json($equipment);
    }
    /**
     * Store a newly created resource in storage.
     */
 public function store(Request $request)
{
    // Validate the request data
    $validatedData = $request->validate([
        'diagnosis' => 'required|string',
        'citizen_id' => 'required|exists:citizen_details,citizen_id',
    ]);

    // Add the current date to the data
    $validatedData['date'] = now()->toDateString();

    // Create the diagnostic record
    $diagnostic = Diagnostic::create($validatedData);

    // Return a success response
    return response()->json([
        'success' => true,
        'message' => 'Diagnostic record added successfully!',
        'data' => $diagnostic,
    ], 201);
}



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $diagnostic = Diagnostic::findOrFail($id);

        $diagnostic->delete();

        return $diagnostic;
    }
}
