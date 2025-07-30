<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WilayahController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\PositionController;

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

// Public routes (no authentication required)
Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
});

// Wilayah routes (no authentication required)
Route::controller(WilayahController::class)->group(function () {
    Route::get('provinces', 'getProvinces');
    Route::post('provinces/clear-cache', 'clearCache');
    Route::get('provinces/cache-status', 'getCacheStatus');
    
    // Regency routes
    Route::get('provinces/{provinceCode}', 'getRegencies');
    Route::post('provinces/{provinceCode}/clear-cache', 'clearRegenciesCache');
});

// Public image access (no authentication required)
Route::controller(FileUploadController::class)->group(function () {
    Route::get('image/{filename}', 'showImage')->name('api.image.show');
});

// Protected routes (authentication required)
Route::middleware('auth:api')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('logout', 'logout');
        Route::get('user-profile', 'userProfile');
    });
    
    // File upload routes (protected)
    Route::controller(FileUploadController::class)->group(function () {
        Route::post('upload/image', 'uploadImage');
    });
    
    // Team routes (protected)
    Route::controller(TeamController::class)->group(function () {
        Route::get('teams', 'index');
        Route::post('teams', 'store');
        Route::get('teams/{id}', 'show');
        Route::put('teams/{id}', 'update');
        Route::delete('teams/{id}', 'destroy');
    });

    // Public position routes (no authentication required)
    Route::controller(PositionController::class)->group(function () {
        Route::get('positions', 'index');
        Route::get('positions/{id}', 'show');
    });
}); 