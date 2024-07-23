<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;
use App\Http\Requests\MedicineRequest;

class MedicineController extends Controller
{
    public function index()
    {
        $medicine = Medicine::all();
        return response()->json($medicine);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(MedicineRequest $request)
    {
        // Retrieve the validated input data...
        $validated = $request->validated();

        $medicine = Medicine::create($validated);

        return $medicine;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MedicineRequest $request, string $id)
    {
        $medicine = Medicine::findOrFail($id);

        $validated = $request->validated();

        $medicine->name = $validated['name'];

        $medicine->save();

        return $medicine;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $medicine = Medicine::findOrFail($id);

        $medicine->delete();

        return $medicine;
    }
}
