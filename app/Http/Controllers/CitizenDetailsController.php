<?php

namespace App\Http\Controllers;

use App\Models\CitizenDetails;
use App\Http\Requests\CitizenDetailsRequest;

class CitizenDetailsController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(CitizenDetailsRequest $request)
    {
        // Retrieve the validated input data...
        $validated = $request->validated();

        $citizenDetails = CitizenDetails::create($validated);

        return response()->json($citizenDetails, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CitizenDetailsRequest $request, string $id)
    {
        $citizenDetails = CitizenDetails::findOrFail($id);

        $validated = $request->validated();

        $citizenDetails->update($validated);

        return response()->json($citizenDetails);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $citizenDetails = CitizenDetails::findOrFail($id);

        $citizenDetails->delete();

        return response()->json(null, 204);
    }
}
