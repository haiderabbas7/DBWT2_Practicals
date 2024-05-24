<?php
namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ShoppingCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\ShoppingCartItem;

class ShoppingCartController {
    /**
     * Adds an article to the shopping cart
     *
     * @param Request $request The request containing the article id and the user id
     * @return \Illuminate\Http\JsonResponse The shopping cart item that was added
     */
    public function addArticle_api(Request $request) {
        if($request->input('articleid') && $request->input('userid')) {
            $articleId = $request->input('articleid');
            $userId = $request->input('userid');

            // Abrufen der shoppingcart_id aus der Datenbank
            $shoppingCart = ShoppingCart::where('creator_id', $userId)->first();
            $shoppingCartId = $shoppingCart->id;

            $shoppingCartItem = new ShoppingCartItem();
            $shoppingCartItem->shoppingcart_id = $shoppingCartId;
            $shoppingCartItem->article_id = $articleId;
            $shoppingCartItem->createdate = date('Y-m-d H:i:s');

            $shoppingCartItem->save();

            return response()->json($shoppingCartItem);
        } else {
            return response()->json(['message' => 'Article ID and User ID are required'], 400);
        }
    }

    /**
     * Removes an article from the shopping cart
     *
     * @param $shoppingcartid The ID of the shopping cart
     * @param $articleId The ID of the article to remove
     * @return \Illuminate\Http\JsonResponse The response
     */
    public function removeArticle_api($shoppingcartid, $articleId) {
        // Finden Sie das ShoppingCartItem, das die gegebene articleId und shoppingcartid hat
        $shoppingCartItem = ShoppingCartItem::where('article_id', $articleId)
            ->where('shoppingcart_id', $shoppingcartid)
            ->first();

        // Überprüfen Sie, ob das ShoppingCartItem gefunden wurde
        if ($shoppingCartItem) {
            // Löschen Sie das ShoppingCartItem
            $shoppingCartItem->delete();

            // Geben Sie eine Antwort zurück
            return response()->json(['message' => 'Artikel erfolgreich aus dem Warenkorb entfernt']);
        } else {
            // Geben Sie eine Fehlermeldung zurück, wenn das ShoppingCartItem nicht gefunden wurde
            return response()->json(['message' => 'Artikel nicht im Warenkorb gefunden'], 404);
        }
    }

    /**
     * Gets the shopping cart items for the given ID of the user
     *
     * @param $userId The ID of the user
     * @return \Illuminate\Http\JsonResponse The shopping cart items
     */
    public function getCart_api($userId) {
        if($userId) {
            // Abrufen der shoppingcart_id aus der Datenbank
            $shoppingCart = ShoppingCart::where('creator_id', $userId)->first();
            $shoppingCartId = $shoppingCart->id;

            $shoppingCartItems = ShoppingCartItem::where('shoppingcart_id', $shoppingCartId)->get();
            return response()->json($shoppingCartItems);
        } else {
            return response()->json(['message' => 'User ID is required'], 400);
        }
    }

    /**
     * Searches for articles based on the given search term
     *
     * @param Request $request The request containing the search term
     * @return \Illuminate\Http\JsonResponse The articles that match the search term
     */
    public function search_api(Request $request) {
        $search = $request->get('search');
        $articles = Article::where('name', 'like', '%' . $search . '%')->get();
        return response()->json($articles);
    }
}
