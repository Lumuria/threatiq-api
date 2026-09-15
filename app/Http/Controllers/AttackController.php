<?php

namespace App\Http\Controllers;

use App\Models\Attack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttackController extends Controller
{
    public function index()
    {
        $attacks = Attack::with('translations')->get();

        $result = $attacks->map(function ($attack) {
            $translations = $attack->translations->keyBy('language');

            return [
                'id' => $attack->id,
                'name' => $attack->name,

                'title' => [
                    'ar' => $translations['ar']->title ?? '',
                    'en' => $translations['en']->title ?? '',
                ],

                'date' => [
                    'ar' => $translations['ar']->date ?? '',
                    'en' => $translations['en']->date ?? '',
                ],

                'type' => [
                    'ar' => $translations['ar']->type ?? '',
                    'en' => $translations['en']->type ?? '',
                ],

                'target' => [
                    'ar' => $translations['ar']->target ?? '',
                    'en' => $translations['en']->target ?? '',
                ],

                'damage' => [
                    'ar' => $translations['ar']->damage ?? '',
                    'en' => $translations['en']->damage ?? '',
                ],

                'description' => [
                    'ar' => $translations['ar']->description ?? '',
                    'en' => $translations['en']->description ?? '',
                ],

                'prevention' => [
                    'ar' => $translations['ar']->prevention ?? '',
                    'en' => $translations['en']->prevention ?? '',
                ],

                'detection' => [
                    'ar' => $translations['ar']->detection ?? '',
                    'en' => $translations['en']->detection ?? '',
                ],

                'solution' => [
                    'ar' => $translations['ar']->solution ?? '',
                    'en' => $translations['en']->solution ?? '',
                ],

                'severity' => [
                    'ar' => $translations['ar']->severity ?? '',
                    'en' => $translations['en']->severity ?? '',
                ],

                'color' => $attack->color,
            ];
        });

        return response()->json($result);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string|max:255',

            'title.ar' => 'required|string',
            'title.en' => 'required|string',

            'date.ar' => 'required|string',
            'date.en' => 'required|string',

            'type.ar' => 'required|string',
            'type.en' => 'required|string',

            'target.ar' => 'required|string',
            'target.en' => 'required|string',

            'damage.ar' => 'required|string',
            'damage.en' => 'required|string',

            'description.ar' => 'required|string',
            'description.en' => 'required|string',

            'prevention.ar' => 'required|string',
            'prevention.en' => 'required|string',

            'detection.ar' => 'required|string',
            'detection.en' => 'required|string',

            'solution.ar' => 'required|string',
            'solution.en' => 'required|string',

            'severity.ar' => 'required|string',
            'severity.en' => 'required|string',
        ]);

        $attack = DB::transaction(function () use ($validated) {
            $attack = Attack::create([
                'name' => $validated['name'],
                'color' => $validated['color'],
            ]);

            foreach (['ar', 'en'] as $language) {
                $attack->translations()->create([
                    'language' => $language,
                    'title' => $validated['title'][$language],
                    'date' => $validated['date'][$language],
                    'type' => $validated['type'][$language],
                    'target' => $validated['target'][$language],
                    'damage' => $validated['damage'][$language],
                    'severity' => $validated['severity'][$language],
                    'description' => $validated['description'][$language],
                    'prevention' => $validated['prevention'][$language],
                    'detection' => $validated['detection'][$language],
                    'solution' => $validated['solution'][$language],
                ]);
            }

            return $attack->load('translations');
        });

        return response()->json([
            'message' => 'Attack created successfully.',
            'attack' => $attack,
        ], 201);
    }
        public function update(Request $request, $id)
    {
        $attack = Attack::with('translations')->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string|max:255',

            'title.ar' => 'required|string',
            'title.en' => 'required|string',

            'date.ar' => 'required|string',
            'date.en' => 'required|string',

            'type.ar' => 'required|string',
            'type.en' => 'required|string',

            'target.ar' => 'required|string',
            'target.en' => 'required|string',

            'damage.ar' => 'required|string',
            'damage.en' => 'required|string',

            'description.ar' => 'required|string',
            'description.en' => 'required|string',

            'prevention.ar' => 'required|string',
            'prevention.en' => 'required|string',

            'detection.ar' => 'required|string',
            'detection.en' => 'required|string',

            'solution.ar' => 'required|string',
            'solution.en' => 'required|string',

            'severity.ar' => 'required|string',
            'severity.en' => 'required|string',
        ]);

        DB::transaction(function () use ($attack, $validated) {

            $attack->update([
                'name' => $validated['name'],
                'color' => $validated['color'],
            ]);

            foreach (['ar', 'en'] as $language) {
                $attack->translations()
                    ->where('language', $language)
                    ->updateOrCreate(
                        ['language' => $language],
                        [
                            'title' => $validated['title'][$language],
                            'date' => $validated['date'][$language],
                            'type' => $validated['type'][$language],
                            'target' => $validated['target'][$language],
                            'damage' => $validated['damage'][$language],
                            'severity' => $validated['severity'][$language],
                            'description' => $validated['description'][$language],
                            'prevention' => $validated['prevention'][$language],
                            'detection' => $validated['detection'][$language],
                            'solution' => $validated['solution'][$language],
                        ]
                    );
            }
        });

        return response()->json([
            'message' => 'Attack updated successfully.',
            'attack' => $attack->load('translations'),
        ]);
    }

    public function destroy($id)
    {
        $attack = Attack::findOrFail($id);

        $attack->delete();

        return response()->json([
            'message' => 'Attack deleted successfully.',
        ]);
    }
    
}