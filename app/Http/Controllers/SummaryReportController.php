<?php

namespace App\Http\Controllers;

use App\Models\CitizenDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Services;


class SummaryReportController extends Controller
{
    public function getDemographicSummary()
    {
        try {
            // Age group calculation
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
                END as age_group, COUNT(*) as count")
                ->whereNotNull('date_of_birth') // Ensure date_of_birth is valid
                ->groupBy('age_group')
                ->pluck('count', 'age_group');

            // Gender distribution with filtered valid values
            $genderDistribution = CitizenDetails::selectRaw("IFNULL(gender, 'Unknown') as gender, COUNT(*) as count")
                ->whereIn('gender', ['Male', 'Female', 'Unknown']) // Filter for valid genders
                ->groupBy('gender')
                ->pluck('count', 'gender');

            // Total population count
            $totalPopulation = CitizenDetails::count();

            // Return the demographic summary as JSON
            return response()->json([
                'ageGroups' => $ageGroups,
                'genderDistribution' => $genderDistribution,
                'totalPopulation' => $totalPopulation
            ]);
        } catch (\Exception $e) {
            // Log error and return a 500 response with error message
            Log::error('Failed to fetch demographic summary: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to fetch demographic summary data'], 500);
        }
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
