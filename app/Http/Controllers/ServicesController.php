<?php

namespace App\Http\Controllers;

use App\Models\Services;
use App\Models\CitizenDetails;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;

class ServicesController extends Controller
{
    public function index()
    {
        // Fetch all services from the database
        $services = Services::all();

        // Return the services as a JSON response
        return response()->json($services);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
            ]);

            $service = Services::create($validated);
            return response()->json($service, 201);
        } catch (\Exception $e) {
            Log::error('Error creating service: ' . $e->getMessage());
            return response()->json(['message' => 'An error occurred while creating the service.'], 500);
        }
    }

    /**
     * Show the service summary.
     *
     * @return \Illuminate\View\View
     */
    public function showServicesSummary()
    {
        try {
            // Fetch all services with the count of citizens who availed each service
            $services = Services::all()->map(function ($service) {
                try {
                    $service->citizens_count = $service->citizens()->count(); // Count citizens per service
                } catch (\Exception $e) {
                    Log::error("Error counting citizens for service {$service->name}: " . $e->getMessage());
                    $service->citizens_count = 0; // Set default value in case of error
                }
                return $service;
            });

            // Return the services as a JSON response
            return response()->json($services);
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Error fetching service summary: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['message' => 'An error occurred while fetching the service summary.'], 500);
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $services = Services::findOrFail($id);

        $services->delete();

        return $services;
    }
}
