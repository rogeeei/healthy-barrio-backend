<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;
use App\Http\Requests\MedicineRequest;

class MedicineController extends Controller
{
    public function index()
    {
        $medicine = Medicine::all();
        return response()->json($medicine);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(MedicineRequest $request)
    {
        // Retrieve the validated input data...
        $validated = $request->validated();

        $medicine = Medicine::create($validated);

        return $medicine;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $medicine = Medicine::findOrFail($id);

        // Do not overwrite gender with role; return data as it is
        return response()->json($medicine);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name'                       => 'nullable|string|max:255',
            'usage_description'          => 'nullable|string|max:255',
            'quantity'                   => 'nullable|integer',
            'expiration_date'            => 'nullable||date|date_format:Y-m-d',
            'batch_no'                   => 'nullable|string|max:255',
            'location'                   => 'nullable|string|max:255',
            'medicine_status'            => 'nullable|string|max:255',
        ]);
        // Find the citizen by ID
        $medicine = Medicine::find($id);

        if (!$medicine) {
            return response()->json(['message' => 'Medicine not found'], 404);
        }

        // Update the citizen's details
        $medicine->update($validated);

        // Return the updated citizen data
        return response()->json(['message' => 'Citizen details updated successfully', 'medicine' => $medicine]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $medicine = Medicine::findOrFail($id);

        $medicine->delete();

        return $medicine;
    }
}
