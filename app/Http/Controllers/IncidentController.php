<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use Illuminate\Http\Request;

class IncidentController extends Controller
{
    // GET ALL INCIDENTS
    public function index()
    {
        $incidents = Incident::all();

        $result = $incidents->map(function ($incident) {
            return [
                'id' => $incident->id,

                'type' => [
                    'ar' => $incident->type_ar,
                    'en' => $incident->type_en,
                ],

                'severity' => [
                    'ar' => $incident->severity_ar,
                    'en' => $incident->severity_en,
                ],

                'status' => [
                    'ar' => $incident->status_ar,
                    'en' => $incident->status_en,
                ],

                'description' => [
                    'ar' => $incident->description_ar,
                    'en' => $incident->description_en,
                ],
            ];
        });

        return response()->json($result);
    }


    // CREATE INCIDENT
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type.ar' => 'required|string',
            'type.en' => 'required|string',

            'severity.ar' => 'required|string',
            'severity.en' => 'required|string',

            'status.ar' => 'required|string',
            'status.en' => 'required|string',

            'description.ar' => 'required|string',
            'description.en' => 'required|string',
        ]);

        $incident = Incident::create([
            'type_ar' => $validated['type']['ar'],
            'type_en' => $validated['type']['en'],

            'severity_ar' => $validated['severity']['ar'],
            'severity_en' => $validated['severity']['en'],

            'status_ar' => $validated['status']['ar'],
            'status_en' => $validated['status']['en'],

            'description_ar' => $validated['description']['ar'],
            'description_en' => $validated['description']['en'],
        ]);

        return response()->json([
            'message' => 'Incident created successfully.',
            'incident' => $incident,
        ], 201);
    }


    // UPDATE INCIDENT
    public function update(Request $request, $id)
    {
        $incident = Incident::findOrFail($id);

        $validated = $request->validate([
            'type.ar' => 'required|string',
            'type.en' => 'required|string',

            'severity.ar' => 'required|string',
            'severity.en' => 'required|string',

            'status.ar' => 'required|string',
            'status.en' => 'required|string',

            'description.ar' => 'required|string',
            'description.en' => 'required|string',
        ]);

        $incident->update([
            'type_ar' => $validated['type']['ar'],
            'type_en' => $validated['type']['en'],

            'severity_ar' => $validated['severity']['ar'],
            'severity_en' => $validated['severity']['en'],

            'status_ar' => $validated['status']['ar'],
            'status_en' => $validated['status']['en'],

            'description_ar' => $validated['description']['ar'],
            'description_en' => $validated['description']['en'],
        ]);

        return response()->json([
            'message' => 'Incident updated successfully.',
            'incident' => $incident,
        ]);
    }


    // DELETE INCIDENT
    public function destroy($id)
    {
        $incident = Incident::findOrFail($id);

        $incident->delete();

        return response()->json([
            'message' => 'Incident deleted successfully.',
        ]);
    }
}