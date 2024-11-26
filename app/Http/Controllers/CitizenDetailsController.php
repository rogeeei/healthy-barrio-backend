<?php

namespace App\Http\Controllers;

use App\Models\CitizenDetails;
use App\Http\Requests\CitizenDetailsRequest;
use App\Models\Diagnostic;
use App\Models\CitizenHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CitizenDetailsController extends Controller
{
    public function index(Request $request)
    {
        $query = CitizenDetails::query();

        // Check if a search term is provided in the request
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('firstname', 'like', "%{$search}%")
                ->orWhere('lastname', 'like', "%{$search}%")
                ->orWhere('address', 'like', "%{$search}%");
        }

        // Eager load the services_availed relationship
        $citizens = $query->with('servicesAvailed')->get();

        // Return the citizens data with the services_availed relationship
        return response()->json($citizens);
    }

    public function getCitizenVisitHistory()
    {
        $history = DB::table('citizen_details')
            ->select(
                'citizen_details.created_at',
                'citizen_details.lastname',
                'citizen_details.firstname',
                'citizen_details.citizen_id',
                'citizen_details.gender',
                'citizen_details.date_of_birth',
                'citizen_details.address',
            )
            ->orderBy('citizen_details.created_at', 'desc') // Order by most recent visit date
            ->distinct('citizen_details.citizen_id') // Ensure distinct citizens are selected
            ->take(15) // Limit to 15 records
            ->get();

        if ($history->isEmpty()) {
            return response()->json(['message' => 'No histories found'], 404);
        }

        // Now eager load the services for each citizen
        foreach ($history as $citizen) {
            // Assuming you have a CitizenDetails model
            $citizen->services_availed = CitizenDetails::find($citizen->citizen_id)->services;
        }

        return response()->json($history);
    }

    /**
     * Show the service summary.
     *
     * @return \Illuminate\View\View
     */
    public function showServicesSummary()
    {
        try {
            // Fetch citizen data, using 'services_availed' as the membership date
            $citizens = CitizenDetails::select('citizen_id', 'firstname', 'middle_name', 'lastname', 'suffix', 'created_at')
                ->get();

            // Return the view with the citizens' data
            return response()->json($citizens);
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Error fetching citizen details: ' . $e->getMessage());
            return response()->json(['message' => 'An error occurred while fetching citizen details.'], 500);
        }
    }

    // CitizenController
    public function fetchServicesView(Request $request)
    {
        $citizens = CitizenDetails::with('services')->get();

        return response()->json($citizens);
    }



  public function show(string $citizenId)
{
    // Retrieve citizen details along with related services and histories
    $citizen = CitizenDetails::with(['services', 'histories'])->where('citizen_id', $citizenId)->first();

    if (!$citizen) {
        return response()->json(['message' => 'Citizen not found'], 404);
    }

    // Prepare the response data
    $response = [
        'citizen_id' => $citizen->citizen_id,
        'firstname' => $citizen->firstname,
        'middle_name' => $citizen->middle_name,
        'lastname' => $citizen->lastname,
        'address' => $citizen->address,
        'date_of_birth' => $citizen->date_of_birth,
        'gender' => $citizen->gender,
        'citizen_status' => $citizen->citizen_status,
        'blood_type' => $citizen->blood_type,
        'height' => $citizen->height,
        'weight' => $citizen->weight,
        'allergies' => $citizen->allergies,
        'condition' => $citizen->condition,
        'medication' => $citizen->medication,
        'emergency_contact_name' => $citizen->emergency_contact_name,
        'emergency_contact_no' => $citizen->emergency_contact_no,
        'histories' => $citizen->histories, // Citizen's history records
    ];

    // Merge all the services availed across different records (services can be repeated)
    $allServices = $citizen->services->map(function ($service) {
        return [
            'service_id' => $service->id,
            'name' => $service->name,
            'description' => $service->description, // Assuming service has a description
        ];
    });

    // Include services from the histories and make them distinct
    foreach ($citizen->histories as $history) {
        $historyServices = $history->services->map(function ($service) {
            return [
                'service_id' => $service->id,
                'name' => $service->name,
                'description' => $service->description, // Assuming service has a description
            ];
        });

        // Merge services from history with citizen's services
        $allServices = $allServices->merge($historyServices);
    }

    // Make the services distinct by `service_id`
    $allServices = $allServices->unique('service_id');

    // Add the unique services to the response
    $response['services_availed'] = $allServices;

    return response()->json($response);
}



public function store(CitizenDetailsRequest $request)
{
    // Validate the incoming request
    $validated = $request->validate([
        'firstname' => 'required|string',
        'middle_name' => 'nullable|string|max:255',
        'lastname' => 'required|string',
        'address' => 'required|string',
        'date_of_birth' => 'required|date|date_format:Y-m-d',
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
        'services_availed' => 'required|array', // Array of service IDs
        'services_availed.*' => 'exists:services,id', // Ensure each ID exists in the services table
    ]);

    // Get the names of the services the citizen availed
    $serviceNames = \App\Models\Services::whereIn('id', $validated['services_availed'])
        ->pluck('name')
        ->toArray();

    // Check if a citizen with the same details exists
    $citizen = CitizenDetails::updateOrCreate(
        [
            'firstname' => $validated['firstname'],
            'lastname' => $validated['lastname'],
            'middle_name' => $validated['middle_name'],
            'date_of_birth' => $validated['date_of_birth'],
            'gender' => $validated['gender'],
            'address' => $validated['address'],
        ],
        $validated // update other fields
    );

    // Create or update citizen history
    $citizenHistory = CitizenHistory::create([
        'citizen_id' => $citizen->citizen_id,
        'diagnostic_id' => null, // Update this as needed
        'date' => now(),
        'visit_date' => now(),
        'firstname' => $citizen->firstname,
        'middle_name' => $citizen->middle_name,
        'lastname' => $citizen->lastname,
        'address' => $citizen->address,
        'date_of_birth' => $citizen->date_of_birth,
        'gender' => $citizen->gender,
        'citizen_status' => $citizen->citizen_status,
        'blood_type' => $citizen->blood_type,
        'height' => $citizen->height,
        'weight' => $citizen->weight,
        'allergies' => $citizen->allergies,
        'condition' => $citizen->condition,
        'medication' => $citizen->medication,
        'emergency_contact_name' => $citizen->emergency_contact_name,
        'emergency_contact_no' => $citizen->emergency_contact_no,
        // Store services availed in history if needed (optional)
        'services_availed' => json_encode($serviceNames), // Optional if you want to track the services in CitizenHistory
    ]);

    // Attach the availed services to the citizen and history (via pivot)
    $citizenHistory->services()->sync($validated['services_availed']);

    // Reload citizen with services
    $citizen->load('services');

    return response()->json([
        'citizen' => $citizen,
        'citizen_history' => $citizenHistory,
        'isNew' => !$citizen->exists, // True if the citizen was just created
    ], 201);
}



    public function getCitizens()
    {
        // Use the existing relationship 'services' to fetch citizens along with their services
        $citizens = CitizenDetails::with('services')->get();
        return response()->json($citizens);
    }


    /**
     * Update the specified citizen in the database.
     */
    public function update(Request $request, $id)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'firstname' => 'nullable|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'lastname' => 'nullable|string|max:255',
            'suffix' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string|max:10',
            'citizen_status' => 'nullable|string|max:100',
            'blood_type' => 'nullable|string|max:10',
            'height' => 'nullable|string',
            'weight' => 'nullable|string',
            'allergies' => 'nullable|string|max:255',
            'condition' => 'nullable|string|max:255',
            'medication' => 'nullable|string|max:255',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_no' => 'nullable|string|max:20',
            'services_availed' => 'nullable|array',
            'services_availed.*' => 'exists:services,services_id',
        ]);

        // Find the citizen by ID
        $citizen = CitizenDetails::find($id);

        if (!$citizen) {
            return response()->json(['message' => 'Citizen not found'], 404);
        }

        // Update the citizen's details
        $citizen->update($validated);

        // Update the services associated with the citizen
        if ($request->has('services_availed')) {
            $citizen->services()->sync($request->input('services_availed'));
        }

        return response()->json(['message' => 'Citizen details updated successfully', 'citizen' => $citizen]);
    }


    public function destroy(string $id)
    {
        $citizenDetails = CitizenDetails::findOrFail($id);

        $citizenDetails->delete();

        return response()->json(null, 204);
    }

    public function getServicesSummary()
    {
        // Fetch the citizen data along with related service data (adjust the fields as necessary)
        $citizens = CitizenDetails::select('address', 'lastname', 'firstname', 'middle_name', 'suffix', 'created_at')
            ->get();

        // Return the data as JSON
        return response()->json($citizens);
    }

   
}
