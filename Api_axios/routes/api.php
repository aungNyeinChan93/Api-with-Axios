<?php

use App\Http\Controllers\API\PostController;
use App\Http\Controllers\CountryController;
use Illuminate\Support\Facades\Route;



Route::apiResource("posts",PostController::class);


// Route::apiResource("country",CountryController::class);
