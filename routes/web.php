<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StudentController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::view('/', 'students');
// Route::get('/students', [StudentController::class, 'index']);