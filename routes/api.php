<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\ParticipationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::middleware('api')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user-profile', [AuthController::class, 'userProfile']);

        Route::apiResource('/campaigns', CampaignController::class);
        Route::post('/campaigns/status/{campaign}', [CampaignController::class, 'changeStatus']);

        Route::get('/participations', [ParticipationController::class, 'index']);
        Route::post('/participations/join', [ParticipationController::class, 'join']);
        Route::get('/participations/leave/{participation}', [ParticipationController::class, 'leave']);
    });
});

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
