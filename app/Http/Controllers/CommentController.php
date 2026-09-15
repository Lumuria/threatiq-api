<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    // =========================================
    // GET /api/comments
    // Everyone can view comments
    // =========================================

    public function index()
    {
        $comments = Comment::with([
            'user:id,name,username,email',
            'post:id,title,body',
        ])
        ->orderBy('created_at', 'asc')
        ->get();

        return response()->json([
            'comments' => $comments,
        ]);
    }


    // =========================================
    // POST /api/comments
    // Authenticated users can add comments
    // =========================================

    public function store(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $validated = $request->validate([
            'post_id' => 'required|exists:posts,id',
            'text' => 'required|string|max:2000',
        ]);

        $comment = Comment::create([
            'user_id' => $user->id,
            'post_id' => $validated['post_id'],
            'text' => trim($validated['text']),
        ]);

        $comment->load([
            'user:id,name,username,email',
            'post:id,title,body',
        ]);

        return response()->json([
            'message' => 'Comment added successfully.',
            'comment' => $comment,
        ], 201);
    }


    // =========================================
    // DELETE /api/comments/{id}
    // Admin only
    // =========================================

    public function destroy(Request $request, $id)
    {
        $user = $request->user();

        if (!$user || !$user->is_admin) {
            return response()->json([
                'message' => 'Unauthorized.',
            ], 403);
        }

        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json([
                'message' => 'Comment not found.',
            ], 404);
        }

        $comment->delete();

        return response()->json([
            'message' => 'Comment deleted successfully.',
        ]);
    }
}