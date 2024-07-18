<?php

namespace App\Http\Controllers;

use App\Http\Requests\DiagnosticRequest;
use App\Models\Diagnostic;
use Illuminate\Http\Request;

class DiagnosticController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(DiagnosticRequest $request)
    {
        // Retrieve the validated input data...
        $validated = $request->validated();

        $diagnostic = Diagnostic::create($validated);

        return $diagnostic;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $diagnostic = Diagnostic::findOrFail($id);

        $diagnostic->delete();

        return $diagnostic;
    }
}
