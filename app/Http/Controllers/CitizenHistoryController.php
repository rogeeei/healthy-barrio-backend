<?php

namespace App\Http\Controllers;

use App\Http\Requests\CitizenHistoryRequest;
use App\Models\CitizenHistory;
use Illuminate\Http\Request;

class CitizenHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $histories = CitizenHistory::with('citizen')->get();
        return response()->json($histories);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $history = CitizenHistory::with('citizen')->find($id);

        if (!$history) {
            return response()->json(['message' => 'History not found'], 404);
        }

        return response()->json($history);
    }
}
