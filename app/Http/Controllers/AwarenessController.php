<?php

namespace App\Http\Controllers;

use App\Models\Awareness;
use Illuminate\Http\Request;

class AwarenessController extends Controller
{


    public function index()
    {
        $awareness = Awareness::orderBy('id', 'desc')->get();

        return response()->json($awareness);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|array',
            'title.ar' => 'nullable|string',
            'title.en' => 'nullable|string',

            'duration' => 'required|array',
            'duration.ar' => 'nullable|string',
            'duration.en' => 'nullable|string',

            'modules' => 'required|array',

            'modules.*' => 'required|array',
            'modules.*.ar' => 'nullable|string',
            'modules.*.en' => 'nullable|string',
        ]);

        $awareness = Awareness::create($validated);

        return response()->json([
            'message' => 'Awareness track created successfully.',
            'awareness' => $awareness,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $awareness = Awareness::find($id);

        if (!$awareness) {
            return response()->json([
                'message' => 'Awareness track not found.',
            ], 404);
        }

        $validated = $request->validate([
            'title' => 'required|array',
            'title.ar' => 'nullable|string',
            'title.en' => 'nullable|string',

            'duration' => 'required|array',
            'duration.ar' => 'nullable|string',
            'duration.en' => 'nullable|string',

            'modules' => 'required|array',

            'modules.*' => 'required|array',
            'modules.*.ar' => 'nullable|string',
            'modules.*.en' => 'nullable|string',
        ]);

        $awareness->update($validated);

        return response()->json([
            'message' => 'Awareness track updated successfully.',
            'awareness' => $awareness->fresh(),
        ]);
    }

    
    public function destroy($id)
    {
        $awareness = Awareness::find($id);

        if (!$awareness) {
            return response()->json([
                'message' => 'Awareness track not found.',
            ], 404);
        }

        $awareness->delete();

        return response()->json([
            'message' => 'Awareness track deleted successfully.',
        ]);
    }
}