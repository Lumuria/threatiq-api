<?php

namespace App\Http\Controllers;

use App\Models\Prevention;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PreventionController extends Controller
{
    // ==========================================
    // GET ALL PREVENTIONS
    // ==========================================
    public function index()
    {
        $preventions = Prevention::with('translations')->get();

        $result = $preventions->map(function ($prevention) {

            $translations = $prevention->translations
                ->keyBy('language');

            return [
                'id' => $prevention->id,

                'category' => [
                    'ar' => $prevention->category_ar,
                    'en' => $prevention->category_en,
                ],

                'importance' => [
                    'ar' => $prevention->importance_ar,
                    'en' => $prevention->importance_en,
                ],

                'difficulty' => [
                    'ar' => $prevention->difficulty_ar,
                    'en' => $prevention->difficulty_en,
                ],

                'title' => [
                    'ar' => $translations['ar']->title ?? '',
                    'en' => $translations['en']->title ?? '',
                ],

                'description' => [
                    'ar' => $translations['ar']->description ?? '',
                    'en' => $translations['en']->description ?? '',
                ],

                'tips' => [
                    'ar' => $translations['ar']->tips ?? [],
                    'en' => $translations['en']->tips ?? [],
                ],
            ];
        });

        return response()->json($result);
    }


    // ==========================================
    // CREATE PREVENTION
    // ==========================================
    public function store(Request $request)
    {
        $validated = $request->validate([

            'category.ar' => 'required|string',
            'category.en' => 'required|string',

            'importance.ar' => 'required|string',
            'importance.en' => 'required|string',

            'difficulty.ar' => 'required|string',
            'difficulty.en' => 'required|string',

            'title.ar' => 'required|string',
            'title.en' => 'required|string',

            'description.ar' => 'required|string',
            'description.en' => 'required|string',

            'tips.ar' => 'required|array',
            'tips.en' => 'required|array',
        ]);

        $prevention = DB::transaction(function () use ($validated) {

            $prevention = Prevention::create([
                'category_ar' => $validated['category']['ar'],
                'category_en' => $validated['category']['en'],

                'importance_ar' => $validated['importance']['ar'],
                'importance_en' => $validated['importance']['en'],

                'difficulty_ar' => $validated['difficulty']['ar'],
                'difficulty_en' => $validated['difficulty']['en'],
            ]);

            foreach (['ar', 'en'] as $language) {

                $prevention->translations()->create([
                    'language' => $language,

                    'title' =>
                        $validated['title'][$language],

                    'description' =>
                        $validated['description'][$language],

                    'tips' =>
                        $validated['tips'][$language],
                ]);
            }

            return $prevention->load('translations');
        });

        return response()->json([
            'message' => 'Prevention created successfully.',
            'prevention' => $prevention,
        ], 201);
    }


    // ==========================================
    // UPDATE PREVENTION
    // ==========================================
    public function update(Request $request, $id)
    {
        $prevention = Prevention::with('translations')
            ->findOrFail($id);

        $validated = $request->validate([

            'category.ar' => 'required|string',
            'category.en' => 'required|string',

            'importance.ar' => 'required|string',
            'importance.en' => 'required|string',

            'difficulty.ar' => 'required|string',
            'difficulty.en' => 'required|string',

            'title.ar' => 'required|string',
            'title.en' => 'required|string',

            'description.ar' => 'required|string',
            'description.en' => 'required|string',

            'tips.ar' => 'required|array',
            'tips.en' => 'required|array',
        ]);

        DB::transaction(function () use (
            $prevention,
            $validated
        ) {

            $prevention->update([

                'category_ar' =>
                    $validated['category']['ar'],

                'category_en' =>
                    $validated['category']['en'],

                'importance_ar' =>
                    $validated['importance']['ar'],

                'importance_en' =>
                    $validated['importance']['en'],

                'difficulty_ar' =>
                    $validated['difficulty']['ar'],

                'difficulty_en' =>
                    $validated['difficulty']['en'],
            ]);

            foreach (['ar', 'en'] as $language) {

                $prevention->translations()
                    ->where('language', $language)
                    ->updateOrCreate(
                        [
                            'language' => $language,
                        ],
                        [
                            'title' =>
                                $validated['title'][$language],

                            'description' =>
                                $validated['description'][$language],

                            'tips' =>
                                $validated['tips'][$language],
                        ]
                    );
            }
        });

        return response()->json([
            'message' => 'Prevention updated successfully.',
            'prevention' =>
                $prevention->load('translations'),
        ]);
    }


    // ==========================================
    // DELETE PREVENTION
    // ==========================================
    public function destroy($id)
    {
        $prevention = Prevention::findOrFail($id);

        $prevention->delete();

        return response()->json([
            'message' =>
                'Prevention deleted successfully.',
        ]);
    }
}