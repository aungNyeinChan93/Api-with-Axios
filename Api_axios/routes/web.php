<?php
require_once __DIR__ ."/api.php";
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->scopeBindings();
