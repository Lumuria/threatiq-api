<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    /**
     * Get likes for a post
     * Public
     */
    public function index($postId)
    {
        $post = Post::find($postId);

        if (!$post) {
            return response()->json([
                'message' => 'Post not found.'
            ], 404);
        }

        $likes = Like::where('post_id', $postId)
            ->with('user:id,name,username,email')
            ->latest()
            ->get();

        return response()->json([
            'count' => $likes->count(),
            'likes' => $likes,
        ]);
    }


    /**
     * Like / Unlike a post
     * Authenticated users
     */
    public function toggle(Request $request, $postId)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated.'
            ], 401);
        }

        $post = Post::find($postId);

        if (!$post) {
            return response()->json([
                'message' => 'Post not found.'
            ], 404);
        }

        $like = Like::where('user_id', $user->id)
            ->where('post_id', $postId)
            ->first();

        // =========================
        // UNLIKE
        // =========================

        if ($like) {
            $like->delete();

            $count = Like::where(
                'post_id',
                $postId
            )->count();

            return response()->json([
                'liked' => false,
                'count' => $count,
                'message' => 'Post unliked successfully.',
            ]);
        }

        // =========================
        // LIKE
        // =========================

        $like = Like::create([
            'user_id' => $user->id,
            'post_id' => $postId,
        ]);

        $count = Like::where(
            'post_id',
            $postId
        )->count();

        $like->load(
            'user:id,name,username,email'
        );

        return response()->json([
            'liked' => true,
            'count' => $count,
            'like' => $like,
            'message' => 'Post liked successfully.',
        ], 201);
    }
}