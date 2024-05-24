<?php

use Illuminate\Support\Facades\Route;
Route::get('/articles', 'ShoppingCartController@search_api');
Route::post('/shoppingcart', 'ShoppingCartController@addArticle_api');

Route::delete('/shoppingcart/{shoppingcartid}/articles/{articleId}', 'ShoppingCartController@removeArticle_api');
Route::get('/shoppingcart', 'ShoppingCartController@getCart_api');
