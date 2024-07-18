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

        $citizendetails = CitizenDetails::create($validated);

        return $citizendetails;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CitizenDetailsRequest $request, string $id)
    {
        $citizendetails = CitizenDetails::findOrFail($id);

        $validated = $request->validated();

        $citizendetails->name = $validated['firstname'];

        $citizendetails->save();

        return $citizendetails;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $citizendetails = CitizenDetails::findOrFail($id);

        $citizendetails->delete();

        return $citizendetails;
    }
}
