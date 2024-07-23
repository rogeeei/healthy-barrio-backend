<?php

namespace App\Http\Controllers;

use App\Models\CitizenDetails;
use App\Http\Requests\CitizenDetailsRequest;
use Illuminate\Http\Request;

class CitizenDetailsController extends Controller
{
    public function index(Request $request)
    {
        $query = CitizenDetails::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('firstname', 'like', "%{$search}%")
                ->orWhere('lastname', 'like', "%{$search}%")
                ->orWhere('address', 'like', "%{$search}%")
                ->orWhere('services_availed', 'like', "%{$search}%");
        }

        $citizens = $query->get();
        return response()->json($citizens);
    }

    public function store(CitizenDetailsRequest $request)
    {
        $request->validate([
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'address' => 'required|string',
            'date_of_birth' => 'required|date',
            'gender' => 'required|string',
            'citizen_status' => 'required|string',
            'blood_type' => 'nullable|string',
            'height' => 'required|string',
            'weight' => 'required|string',
            'allergies' => 'nullable|string',
            'condition' => 'required|string',
            'medication' => 'nullable|string',
            'emergency_contact_name' => 'required|string',
            'emergency_contact_no' => 'required|string',
            'services_availed' => 'required|string',
        ]);

        $citizen = new CitizenDetails($request->all());
        $citizen->save();

        return response()->json($citizen, 201);
    }

    public function update(CitizenDetailsRequest $request, string $id)
    {
        $citizenDetails = CitizenDetails::findOrFail($id);

        $validated = $request->validated();

        $citizenDetails->update($validated);

        return response()->json($citizenDetails);
    }

    public function destroy(string $id)
    {
        $citizenDetails = CitizenDetails::findOrFail($id);

        $citizenDetails->delete();

        return response()->json(null, 204);
    }
}
