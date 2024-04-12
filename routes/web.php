<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/testdata',[\App\Http\Controllers\AbTestDataController::class,'index']);
