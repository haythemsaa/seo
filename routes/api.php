<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API v1 Routes
Route::prefix('v1')->middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    // Projects
    Route::apiResource('projects', \App\Http\Controllers\Api\ProjectController::class);

    // Keywords
    Route::apiResource('projects.keywords', \App\Http\Controllers\Api\KeywordController::class);

    // Rankings
    Route::get('projects/{project}/rankings', [\App\Http\Controllers\Api\RankingController::class, 'index']);

    // Audits
    Route::post('projects/{project}/audits', [\App\Http\Controllers\Api\AuditController::class, 'create']);
    Route::get('projects/{project}/audits', [\App\Http\Controllers\Api\AuditController::class, 'index']);
    Route::get('audits/{audit}', [\App\Http\Controllers\Api\AuditController::class, 'show']);

    // Backlinks
    Route::get('projects/{project}/backlinks', [\App\Http\Controllers\Api\BacklinkController::class, 'index']);

    // Reports
    Route::get('projects/{project}/reports', [\App\Http\Controllers\Api\ReportController::class, 'index']);
    Route::post('projects/{project}/reports', [\App\Http\Controllers\Api\ReportController::class, 'generate']);

    // Analytics
    Route::get('projects/{project}/analytics', [\App\Http\Controllers\Api\AnalyticsController::class, 'index']);
});
