<?php

namespace App\Http\Controllers;

use App\Http\Requests\CitizenHistoryRequest;
use App\Models\CitizenHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Add this line

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

    public function store(CitizenHistoryRequest $request)
    {
        // Validate and store the data
        $validated = $request->validated();

        $history = CitizenHistory::create([
            'citizen_id' => $validated['citizen_id'],
            'diagnostic_id' => $validated['diagnostic_id'],
            'date' => $validated['date'],
        ]);

        return response()->json($history, 201); // Return the newly created history record
    }

    public function show($id)
    {
        $history = CitizenHistory::with(['citizen', 'diagnostic'])->find($id);

        if (!$history) {
            return response()->json(['message' => 'History not found'], 404);
        }

        return response()->json($history);
    }
}
