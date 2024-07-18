<?php

namespace App\Http\Controllers;

use App\Http\Requests\EquipmentRequest;
use App\Models\Equipment;

class EquipmentController extends Controller
{
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

    /**
     * Update the specified resource in storage.
     */
    public function update(EquipmentRequest $request, string $id)
    {
        $equipment = Equipment::findOrFail($id);

        $validated = $request->validated();

        $equipment->name = $validated['name'];

        $equipment->save();

        return $equipment;
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
