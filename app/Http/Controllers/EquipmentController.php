<?php

namespace App\Http\Controllers;

use App\Http\Requests\EquipmentRequest;
use App\Models\Equipment;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{

    public function index()
    {
        $equipment = Equipment::all();
        return response()->json($equipment);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(EquipmentRequest $request)
    {
        // Retrieve the validated input data...
        $validated = $request->validated();

        $equipment = Equipment::create($validated);

        return $equipment;
    }

    public function show(string $id)
    {
        $equipment = Equipment::findOrFail($id);

        // Do not overwrite gender with role; return data as it is
        return response()->json($equipment);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'description'                       => 'nullable|string|max:255',
            'quantity'                   => 'nullable|integer',
            'location'                   => 'nullable|string|max:255',
            'condition'                   => 'nullable|string|max:255',
            'equipment_status'            => 'nullable|string|max:255',
        ]);
        // Find the equipment by ID
        $equipment = Equipment::find($id);

        if (!$equipment) {
            return response()->json(['message' => 'Equipment not found'], 404);
        }

        // Update the citizen's details
        $equipment->update($validated);

        // Return the updated citizen data
        return response()->json(['message' => 'Citizen details updated successfully', 'medicine' => $equipment]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $equipment = Equipment::findOrFail($id);

        $equipment->delete();

        return $equipment;
    }
}
