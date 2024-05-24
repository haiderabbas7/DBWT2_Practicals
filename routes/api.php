<?php

use Illuminate\Support\Facades\Route;
Route::get('/articles', 'ShoppingCartController@search_api');
Route::post('/shoppingcart', 'ShoppingCartController@addArticle_api');

Route::delete('/shoppingcart/{shoppingcartid}/articles/{articleId}', 'ShoppingCartController@removeArticle_api');
Route::get('/shoppingcart', 'ShoppingCartController@getCart_api');

//Zum Erstellen von neuen Artikeln, M3 A8
Route::post('/articles', [\App\Http\Controllers\ArticlesController::class, 'createNewArticle_api']);
