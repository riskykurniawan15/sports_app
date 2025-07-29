<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WilayahController;

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

// Protected routes (authentication required)
Route::middleware('auth:api')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('logout', 'logout');
        Route::get('user-profile', 'userProfile');
    });
    
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
}); 