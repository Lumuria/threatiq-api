<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\User;
use App\Notifications\NewNewsNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(): JsonResponse
    {
        $news = News::orderByDesc('date')
            ->orderByDesc('id')
            ->get();

        return response()->json($news);
    }

    public function store(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user || !$user->is_admin) {
            return response()->json([
                'message' => 'Unauthorized.'
            ], 403);
        }

        $validated = $request->validate([
            'title' => ['required', 'array'],
            'title.ar' => ['required', 'string'],
            'title.en' => ['required', 'string'],

            'summary' => ['required', 'array'],
            'summary.ar' => ['nullable', 'string'],
            'summary.en' => ['nullable', 'string'],

            'content' => ['required', 'array'],
            'content.ar' => ['nullable', 'string'],
            'content.en' => ['nullable', 'string'],

            'category' => ['required', 'array'],
            'category.ar' => ['nullable', 'string'],
            'category.en' => ['nullable', 'string'],

            'severity' => ['required', 'array'],
            'severity.ar' => ['nullable', 'string'],
            'severity.en' => ['nullable', 'string'],

            'source' => ['required', 'array'],
            'source.ar' => ['nullable', 'string'],
            'source.en' => ['nullable', 'string'],

            'date' => ['nullable', 'date'],

            'recommendations' => ['nullable', 'array'],
            'recommendations.*' => ['array'],
            'recommendations.*.ar' => ['nullable', 'string'],
            'recommendations.*.en' => ['nullable', 'string'],
        ]);

        $news = News::create($validated);

        User::where('id', '!=', $user->id)
            ->where('is_admin', false)
            ->whereNotNull('email_verified_at')
            ->get()
            ->each(function ($recipient) use ($news) {
                $recipient->notify(
                    new NewNewsNotification($news)
                );
            });

        return response()->json([
            'message' => 'News created successfully.',
            'news' => $news,
        ], 201);
    }

    public function show(News $news): JsonResponse
    {
        return response()->json($news);
    }

    public function update(
        Request $request,
        News $news
    ): JsonResponse {
        $user = $request->user();

        if (!$user || !$user->is_admin) {
            return response()->json([
                'message' => 'Unauthorized.'
            ], 403);
        }

        $validated = $request->validate([
            'title' => ['sometimes', 'array'],
            'title.ar' => ['nullable', 'string'],
            'title.en' => ['nullable', 'string'],

            'summary' => ['sometimes', 'array'],
            'summary.ar' => ['nullable', 'string'],
            'summary.en' => ['nullable', 'string'],

            'content' => ['sometimes', 'array'],
            'content.ar' => ['nullable', 'string'],
            'content.en' => ['nullable', 'string'],

            'category' => ['sometimes', 'array'],
            'category.ar' => ['nullable', 'string'],
            'category.en' => ['nullable', 'string'],

            'severity' => ['sometimes', 'array'],
            'severity.ar' => ['nullable', 'string'],
            'severity.en' => ['nullable', 'string'],

            'source' => ['sometimes', 'array'],
            'source.ar' => ['nullable', 'string'],
            'source.en' => ['nullable', 'string'],

            'date' => ['nullable', 'date'],

            'recommendations' => ['nullable', 'array'],
            'recommendations.*' => ['array'],
            'recommendations.*.ar' => ['nullable', 'string'],
            'recommendations.*.en' => ['nullable', 'string'],
        ]);

        $news->update($validated);

        return response()->json([
            'message' => 'News updated successfully.',
            'news' => $news->fresh(),
        ]);
    }

    public function destroy(News $news): JsonResponse
    {
        $user = request()->user();

        if (!$user || !$user->is_admin) {
            return response()->json([
                'message' => 'Unauthorized.'
            ], 403);
        }

        $news->delete();

        return response()->json([
            'message' => 'News deleted successfully.',
        ]);
    }
}