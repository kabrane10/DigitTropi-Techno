<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AgentAuthController;
use App\Http\Controllers\Api\AgentProducteurController;
use App\Http\Controllers\Api\AgentCollecteController;
use App\Http\Controllers\Api\AgentSuiviController;
use App\Http\Controllers\Api\SyncController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Authentification
Route::post('/agent/login', [AgentAuthController::class, 'login']);
Route::post('/agent/logout', [AgentAuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/agent/me', [AgentAuthController::class, 'me'])->middleware('auth:sanctum');
Route::post('/agent/fcm-token', [AgentAuthController::class, 'updateFcmToken'])->middleware('auth:sanctum');

// Routes protégées
Route::middleware('auth:sanctum')->group(function () {
    
    // Producteurs
    Route::get('/producteurs', [AgentProducteurController::class, 'index']);
    Route::post('/producteurs', [AgentProducteurController::class, 'store']);
    Route::get('/producteurs/{id}', [AgentProducteurController::class, 'show']);
    Route::put('/producteurs/{id}', [AgentProducteurController::class, 'update']);
    Route::post('/producteurs/sync/batch', [AgentProducteurController::class, 'syncBatch']);
    Route::get('/producteurs/sync/offline', [AgentProducteurController::class, 'getOfflineData']);
    
    // Collectes
    Route::get('/collectes', [AgentCollecteController::class, 'index']);
    Route::post('/collectes', [AgentCollecteController::class, 'store']);
    Route::get('/collectes/{id}', [AgentCollecteController::class, 'show']);
    Route::post('/collectes/sync/batch', [AgentCollecteController::class, 'syncBatch']);
    Route::get('/collectes/sync/offline', [AgentCollecteController::class, 'getOfflineData']);
    
    // Suivis
    Route::get('/suivis', [AgentSuiviController::class, 'index']);
    Route::post('/suivis', [AgentSuiviController::class, 'store']);
    Route::get('/suivis/{id}', [AgentSuiviController::class, 'show']);
    Route::post('/suivis/sync/batch', [AgentSuiviController::class, 'syncBatch']);
    Route::get('/suivis/sync/offline', [AgentSuiviController::class, 'getOfflineData']);
    
    // Synchronisation globale
    Route::post('/sync/all', [SyncController::class, 'syncAll']);
    Route::get('/sync/status', [SyncController::class, 'status']);
});