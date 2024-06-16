<?php
namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ShoppingCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\ShoppingCartItem;

class ShoppingCartController {
    public function addArticle_api(Request $request) {
        if($request->input('article_id')) {
            $articleId = $request->input('article_id');

            // Abrufen der shoppingcart_id aus der Datenbank
            $shoppingCartId = ShoppingCart::where('creator_id', 1)
                ->first()
                ->id;

            try{
                ShoppingCartItem::create([
                    'shoppingcart_id' => $shoppingCartId,
                    'article_id' => $articleId,
                    'createdate' => now()
                ]);
                return response()->json('Erfolg');
            }
            catch(\Exception){
                return response()->json('Fehler');
            }
        } else {
            return response()->json('Fehler: keine article_id angegeben');
        }
    }

    public function removeArticle_api($shoppingcart_id, $article_id) {
        $shoppingCartItem = ShoppingCartItem::where('article_id', $article_id)
            ->where('shoppingcart_id', $shoppingcart_id)
            ->orderBy('createdate', 'desc')
            ->first();

        if ($shoppingCartItem) {
            $shoppingCartItem->delete();
            return response()->json('Erfolg');
        }
        else{
            return response()->json('Fehler');
        }
    }

    /**
     * Gets the shopping cart items for the given ID of the user
     *
     * @param $shoppingcart_id The ID of the user
     * @return \Illuminate\Http\JsonResponse The shopping cart items
     */
    public function getCart_api($shoppingcart_id) {
        try{
            $shoppingCartItems = DB::table('article')
                ->join('shoppingcart_item', 'article.id', '=', 'shoppingcart_item.article_id')
                ->where('shoppingcart_item.shoppingcart_id', $shoppingcart_id)
                ->select('article.*', 'shoppingcart_item.*')
                ->get();
            return response()->json($shoppingCartItems);
        }
        catch(\Exception $e){
            Log::info($e->getMessage());
            return response()->json(['message' => 'Fehler'], 500);
        }
    }
}
