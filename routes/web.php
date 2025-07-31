<?php

use Illuminate\Support\Facades\Route;

Route::fallback(function () {
    return response()->json([
        'code' => [
            'status' => 404,
            'message' => 'The route ' . request()->path() . ' could not be found.'
        ],
        'data' => null
    ], 404);
});
