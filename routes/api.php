<?php

use Illuminate\Support\Facades\Route;

//Ich denk mal zum Laden aller Artikel aus der Datenbank? Kann man refactoren, sodass im ArticlesController funktioniert
Route::get('/articles', 'ShoppingCartController@search_api');

//um einen bestimmten Artikel zum shoppingcart hinzuzufügen
Route::post('/shoppingcart', 'ShoppingCartController@addArticle_api');

//Um ein bestimmtes Item aus dem shoppingcart zu löschen
Route::delete('/shoppingcart/{shoppingcartid}/articles/{articleId}', 'ShoppingCartController@removeArticle_api');

//Um den gesamten shoppingcart aus der Datenbank zu laden
Route::get('/shoppingcart', 'ShoppingCartController@getCart_api');

//Zum Erstellen von neuen Artikeln, M3 A8
Route::post('/articles', [\App\Http\Controllers\ArticlesController::class, 'createNewArticle_api']);
