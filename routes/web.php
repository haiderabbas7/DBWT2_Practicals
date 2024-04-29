<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\HomepageController::class, 'index'])->name('homepage');


Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/test', [App\Http\Controllers\TestController::class, 'test'])->name('test');
Route::view('/testview', 'testview');

Route::get('/testdata',[\App\Http\Controllers\TestDataController::class,'index']);

Route::get('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('login');
Route::get('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');
Route::get('/isloggedin', [App\Http\Controllers\AuthController::class, 'isloggedin'])->name('haslogin');



Route::get('/articles', [App\Http\Controllers\ArticlesController::class, 'articles'])->name('articles');
