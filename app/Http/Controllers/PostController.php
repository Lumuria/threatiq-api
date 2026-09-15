<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Get all posts
     * Public
     */
    public function index()
    {
        $posts = Post::with('user')
            ->latest()
            ->get();

        return response()->json([
            'posts' => $posts,
        ]);
    }

    /**
     * Create post
     * Admin only
     */
    public function store(Request $request)
    {
        $user = $request->user();

        if (!$user || !$user->is_admin) {
            return response()->json([
                'message' => 'Unauthorized.',
            ], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $post = Post::create([
            'user_id' => $user->id,
            'title' => $validated['title'],
            'body' => $validated['body'],
        ]);

        $post->load('user');

        return response()->json([
            'message' => 'Post created successfully.',
            'post' => $post,
        ], 201);
    }

    /**
     * Update post
     * Admin only
     */
    public function update(Request $request, $id)
    {
        $user = $request->user();

        if (!$user || !$user->is_admin) {
            return response()->json([
                'message' => 'Unauthorized.',
            ], 403);
        }

        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'message' => 'Post not found.',
            ], 404);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $post->update([
            'title' => $validated['title'],
            'body' => $validated['body'],
        ]);

        $post->load('user');

        return response()->json([
            'message' => 'Post updated successfully.',
            'post' => $post,
        ]);
    }

    /**
     * Delete post
     * Admin only
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->user();

        if (!$user || !$user->is_admin) {
            return response()->json([
                'message' => 'Unauthorized.',
            ], 403);
        }

        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'message' => 'Post not found.',
            ], 404);
        }

        $post->delete();

        return response()->json([
            'message' => 'Post deleted successfully.',
        ]);
    }
}