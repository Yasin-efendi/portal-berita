<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PostApiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Ini akan otomatis membuat route GET /api/posts, GET /api/posts/{slug}, dll.
Route::apiResource('posts', PostApiController::class);