<?php

use Illuminate\Support\Facades\Route;

Route::get('/articles', [\App\Http\Controllers\ArticlesController::class, 'search_api']);

//um einen bestimmten Artikel zum shoppingcart hinzuzufügen
Route::post('shoppingcart', [\App\Http\Controllers\ShoppingCartController::class, 'addArticle_api']);

//Um ein bestimmtes Item aus dem shoppingcart zu löschen
Route::delete('/shoppingcart/{shoppingcartid}/articles/{articleId}', [\App\Http\Controllers\ShoppingCartController::class, 'removeArticle_api']);

//Um den gesamten shoppingcart aus der Datenbank zu laden
Route::get('/shoppingcart/{shoppingcartid}', [\App\Http\Controllers\ShoppingCartController::class, 'getCart_api']);

//Zum Erstellen von neuen Artikeln, M3 A8
Route::post('/articles', [\App\Http\Controllers\ArticlesController::class, 'createNewArticle_api']);

//Hilfsroute, damit ich vom Frontend direkt die Kategorien bekomme ohne den Umweg über den Controller
Route::get('/kategorien', [\App\Http\Controllers\HomepageController::class, 'getKategorien_api']);
