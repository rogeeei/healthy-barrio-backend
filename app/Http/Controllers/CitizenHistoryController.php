<?php

namespace App\Http\Controllers;


use App\Models\CitizenHistory;
use App\Models\Services;
use App\Models\CitizenDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;



class CitizenHistoryController extends Controller
{
    public function index()
    {
        $histories = CitizenHistory::with(['citizen', 'diagnostic'])->get();

        if ($histories->isEmpty()) {
            return response()->json(['message' => 'No histories found'], 404);
        }

        return response()->json($histories);
    }


    public function show($id)
    {
        $history = CitizenHistory::with(['citizen', 'diagnostic'])->find($id);

        if (!$history) {
            return response()->json(['message' => 'History not found'], 404);
        }

        return response()->json($history);
    }

public function getHistoryByMonth(Request $request)
{
    // Optional validation for citizen_id
    $validated = $request->validate([
        'citizen_id' => 'nullable|exists:citizen_details,citizen_id', // citizen_id is optional
    ]);

    $citizenId = $validated['citizen_id'] ?? null; // Get citizen_id or default to null

    // Base query for fetching history
    $query = DB::table('citizen_details')
        ->leftJoin('citizen_service', 'citizen_details.citizen_id', '=', 'citizen_service.citizen_id')
        ->leftJoin('services', 'citizen_service.service_id', '=', 'services.id')
        ->select(
            'citizen_details.citizen_id',
            'citizen_details.lastname',
            'citizen_details.firstname',
            'citizen_details.gender',
            'citizen_details.address',
            'citizen_details.date_of_birth',
            'citizen_details.created_at',
            'services.name as service_name',
            DB::raw('MONTH(citizen_details.created_at) as visit_month'),
            DB::raw('YEAR(citizen_details.created_at) as visit_year')
        );

    // Apply citizen_id filter if provided
    if ($citizenId) {
        $query->where('citizen_details.citizen_id', $citizenId);
    }

    $history = $query->orderBy('citizen_details.created_at', 'desc')->get();

    // Check if no history found
    if ($history->isEmpty()) {
        $message = $citizenId
            ? 'No history found for this citizen'
            : 'No histories found';
        return response()->json(['message' => $message], 404);
    }

    // Group by month and year
    $groupedHistory = $history->groupBy(function ($item) {
        return Carbon::parse($item->created_at)->format('F Y');
    });

    // Map each group to format services availed
    $groupedHistory = $groupedHistory->map(function ($group) {
        return $group->map(function ($item) use ($group) {
            $monthYear = Carbon::parse($item->created_at)->format('F Y');
            $item->visit_month_year = $monthYear;

            $servicesAvailed = $group->where('citizen_id', $item->citizen_id)
                ->pluck('service_name')
                ->unique()
                ->implode(', ');

            $item->services_availed = $servicesAvailed;

            return $item;
        });
    });

    // Return grouped history
    return response()->json($groupedHistory);
}



public function getTransactionHistory($citizenId)
{
    // Fetch the citizen details
    $citizenDetails = CitizenDetails::find($citizenId);

    if (!$citizenDetails) {
        return response()->json(['message' => 'Citizen not found'], 404);
    }

    // Decode diagnostics if stored as JSON
    $diagnostics = $citizenDetails->diagnostics 
        ? json_decode($citizenDetails->diagnostics, true) 
        : [];

    // Fetch transaction history
    $transactionHistories = CitizenHistory::where('citizen_id', $citizenId)
        ->get()
        ->map(function ($history) use ($diagnostics) {
            $services = json_decode($history->services_availed, true) ?? [];
            $transaction = empty($services) ? 'No services availed' : implode(', ', $services);

            return [
                'transaction' => $transaction,
                'date_availed' => $history->created_at->format('Y-m-d'),
                'diagnostics' => $diagnostics, // Attach diagnostics directly
            ];
        });

    return response()->json([
        'citizen' => $citizenDetails->firstname . ' ' . $citizenDetails->lastname,
        'histories' => $transactionHistories,
    ]);
}


}
