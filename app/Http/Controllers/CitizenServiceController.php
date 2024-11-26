<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CitizenDetails;
use App\Models\Services;

class CitizenServiceController extends Controller
{
    public function getCitizensByService($serviceId)
    {
        // Get citizens who availed this specific service
        $citizens = CitizenDetails::whereHas('services', function ($query) use ($serviceId) {
            $query->where('services.id', $serviceId);
        })
            ->with('services') // Optional: Eager load services if needed in response
            ->get();

        // Count the number of citizens
        $citizensCount = $citizens->count();

        // Get the service name
        $serviceName = Services::find($serviceId)->name ?? 'Unknown Service';

        return response()->json([
            'serviceName' => $serviceName,
            'citizensCount' => $citizensCount, // Add the count of citizens
            'citizens' => $citizens // Return the list of citizens
        ]);
    }


    public function show($id)
    {
        $citizen = CitizenDetails::with('services')->find($id);
        if (!$citizen) {
            return response()->json(['error' => 'Citizen not found'], 404);
        }
        return response()->json($citizen);
    }

    /**
     * Get the service with the count of citizens grouped by age and the number of citizens
     */
    public function getServiceWithAgeDistribution($serviceId)
    {
        try {
            // Fetch the service by its ID
            $service = Services::findOrFail($serviceId);

            // Get citizens who availed this service and group them by age
            $ageGroups = CitizenDetails::selectRaw("
            CASE 
                WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 0 AND 2 THEN 'Infant'
                WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 3 AND 5 THEN 'Toddler'
                WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 6 AND 12 THEN 'Child'
                WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 13 AND 19 THEN 'Teenager'
                WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 20 AND 39 THEN 'Young Adult'
                WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 40 AND 59 THEN 'Middle-aged Adult'
                WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 60 AND 79 THEN 'Senior'
                ELSE 'Elderly' 
            END as age_group, 
            COUNT(*) as count
        ")
                ->whereHas('services', function ($query) use ($serviceId) {
                    $query->where('services.id', $serviceId);
                })
                ->groupBy('age_group')
                ->get();

            // Calculate the total number of citizens who availed this service
            $totalCitizens = $ageGroups->sum('count');

            // Return the service data along with the age distribution and total citizens
            return response()->json([
                'serviceName' => $service->name,
                'ageGroups' => $ageGroups,
                'totalCitizens' => $totalCitizens,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while fetching the service data.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
