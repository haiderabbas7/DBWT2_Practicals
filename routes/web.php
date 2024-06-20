<?php

use Illuminate\Support\Facades\Route;

//heißt hier nicht /newsite, sondern einfach die root seite / damit komfortabler (damit man nicht immer /newsite eingeben muss ya know)
Route::get('/', [App\Http\Controllers\HomepageController::class, 'index'])->name('newsite');

//homepage ist die ALTE (also VOR M5) homepage
Route::get('/homepage', [App\Http\Controllers\HomepageController::class, 'homepage'])->name('homepage');

Route::get('/welcome', function () {
    return view('welcome');
});

Route::any('/test', [App\Http\Controllers\TestController::class, 'test'])->name('test');
Route::view('/testview', 'testview');
Route::get('/testdata',[\App\Http\Controllers\TestDataController::class,'index']);

Route::get('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('login');
Route::get('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');
Route::get('/isloggedin', [App\Http\Controllers\AuthController::class, 'isloggedin'])->name('haslogin');

Route::get('/articles', [App\Http\Controllers\ArticlesController::class, 'articles'])->name('articles');
//Route::post('/articles', [\App\Http\Controllers\ArticlesController::class, 'createNewArticle'])->name('addnewarticle');

Route::get('/newarticle',[\App\Http\Controllers\ArticlesController::class, 'getNewArticleInfo'])->name('newarticle');
