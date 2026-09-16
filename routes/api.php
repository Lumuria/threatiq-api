<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\AttackController;
use App\Http\Controllers\AwarenessController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PreventionController;
use App\Http\Controllers\ToolController;
use Illuminate\Support\Facades\Route;

Route::get('/attacks', [AttackController::class, 'index']);

Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::post('/attacks', [AttackController::class, 'store']);
    Route::put('/attacks/{id}', [AttackController::class, 'update']);
    Route::delete('/attacks/{id}', [AttackController::class, 'destroy']);

    Route::post('/preventions', [PreventionController::class, 'store']);
    Route::put('/preventions/{id}', [PreventionController::class, 'update']);
    Route::delete('/preventions/{id}', [PreventionController::class, 'destroy']);

    Route::post('/incidents', [IncidentController::class, 'store']);
    Route::put('/incidents/{id}', [IncidentController::class, 'update']);
    Route::delete('/incidents/{id}', [IncidentController::class, 'destroy']);

    Route::post('/awareness', [AwarenessController::class, 'store']);
    Route::put('/awareness/{id}', [AwarenessController::class, 'update']);
    Route::delete('/awareness/{id}', [AwarenessController::class, 'destroy']);

    Route::post('/tools', [ToolController::class, 'store']);
    Route::put('/tools/{tool}', [ToolController::class, 'update']);
    Route::delete('/tools/{tool}', [ToolController::class, 'destroy']);

    Route::post('/news', [NewsController::class, 'store']);
    Route::put('/news/{news}', [NewsController::class, 'update']);
    Route::delete('/news/{news}', [NewsController::class, 'destroy']);

    Route::post('/posts', [PostController::class, 'store']);
    Route::put('/posts/{id}', [PostController::class, 'update']);
    Route::delete('/posts/{id}', [PostController::class, 'destroy']);

    Route::put('/users/{id}', [AuthController::class, 'updateUser']);
    Route::delete('/users/{id}', [AuthController::class, 'deleteUser']);
});

Route::get('/preventions', [PreventionController::class, 'index']);

Route::get('/incidents', [IncidentController::class, 'index']);

Route::get('/awareness', [AwarenessController::class, 'index']);

Route::get('/news', [NewsController::class, 'index']);
Route::get('/news/{news}', [NewsController::class, 'show']);

Route::get('/tools', [ToolController::class, 'index']);
Route::get('/tools/{tool}', [ToolController::class, 'show']);
Route::post('/tools/check-url', [ToolController::class, 'checkUrl']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/verify-email', [AuthController::class, 'verifyEmail']);
Route::post('/login', [AuthController::class, 'login']);


Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::post('/verify-reset-code', [AuthController::class, 'verifyResetCode']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/me', [AuthController::class, 'me']);
    Route::get('/users', [AuthController::class, 'users']);

    Route::get('/profile', [AuthController::class, 'profile']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    Route::get('/profile/likes', [AuthController::class, 'likedPosts']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);
    
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);

    Route::post('/comments', [CommentController::class, 'store']);
    Route::delete('/comments/{id}', [CommentController::class, 'destroy']);

    Route::post('/posts/{postId}/like', [LikeController::class, 'toggle']);
});

Route::get('/comments', [CommentController::class, 'index']);

Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{postId}/likes', [LikeController::class, 'index']);