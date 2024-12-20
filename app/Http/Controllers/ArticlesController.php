<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArticlesController
{
    /**
     * Displays the articles requested. When nothing fitting the requirements is requested, shows all existing articles in the database.
     *
     * @param Request $request The request for the to be shown articles
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function articles(Request $request) {
        return view('articles');
    }

    /**
     * Returns the image path to the given article id
     *
     * @param string $articleId The images/titleId to find the image
     * @return string Path to the image
     */
    public function getArticleImagePath(String $articleId): String {
        $imagePathPng = public_path("images/article_images/$articleId.png");
        $imagePathJpg = public_path("images/article_images/$articleId.jpg");

        if (file_exists($imagePathPng)) {
            return "images/article_images/$articleId.png";
        } else if (file_exists($imagePathJpg)) {
            return "images/article_images/$articleId.jpg";
        } else {
            return "images/Placeholder_view_vector.svg.png"; // Placeholder
        }
    }


    public function getNewArticleInfo(){
        $con = 1;
        return view('newarticle', ['con' => $con]);
    }


    public function createNewArticle_api(Request $request){
        $name = $request->input('name');
        $price = $request->input('price');
        $description = $request->input('description');
        $status = '';
        $message = '';
        $id = null;
        if($name === "" || !is_numeric($price) || $price <= 0){
            $status = 'Fehler';
            $message = '<b>FEHLER</b>: Bitte geben Sie gültige Werte ein: Kein leerer Name und nur positive Werte für Preis';
        }
        else{
            try {
                Article::create([
                    'name' => $name,
                    'price' => $price,
                    'description' => $description,
                    'creator_id' => 1,
                    'createdate' => now()
                ]);
                $status = 'Erfolg';
                $message = '<b>ERFOLG</b>: Artikel erfolgreich hinzugefügt';
                //holt sich die ID des zuletzt erstellten artikel, wo name, preis und description übereinstimmen
                $id = Article::where('name', $name)
                    ->where('price', $price)
                    ->where('description', $description)
                    ->orderBy('createdate', 'desc')
                    ->first()
                    ->id;
            }
            catch (\Exception){
                $status = 'Fehler';
                $message = '<b>FEHLER</b>: Fehler beim Einfügen in Datenbank, bitte gültige Werte eingeben';
            }
        }
        return response()->json([
            'status' => $status,
            'message' => $message,
            'id' => $id
        ]);
    }


    public function search_api(Request $request) {
        $search = $request->get('search');
        $userId = $request->get('userId');
        $articleIds = [];
        $page = $request->get('page', 1);
        $limit = 5;
        $offset = ($page - 1) * $limit;
        $articles = isset($search) ? DB::table('article')
            ->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($search) . '%'])
            ->limit($limit)->offset($offset)
            ->get()
            : DB::table('article')->limit($limit)->offset($offset)->get();
        foreach ($articles as $article) {
            $article->image_path = $this->getArticleImagePath($article->id);
            $articleIds[] = $article->id;
        }

        //FLOW 1: schickt dem broadcaster die angezeigte artikelmenge des clients mit userId
        $webSocketApplicationController = new WebSocketApplicationController();
        $webSocketApplicationController->sendArticleOnSaleMessage($userId, $articleIds, 1);

        return response()->json($articles);
    }


    public function articleSold_api(Request $request){
        //holt sich die articleID aus der Route und findet den dazugehörigen User. einen fehlerfall kann es nicht geben
        $articleID = $request->route('id');
        $article = json_decode(Article::find($articleID))->name;
        $userID = json_decode(Article::find($articleID))->creator_id;

        //schickt die userID mit dem websocket controller an den broadcaster
        $webSocketApplicationController = new WebSocketApplicationController();
        $webSocketApplicationController->sendArticleSoldMessage($userID, $article);
    }


    public function articleOnSale_api(Request $request){
        //holt sich die articleID aus der Route und findet den dazugehörigen User. einen fehlerfall kann es nicht geben
        $articleID = $request->route('id');
        $article = json_decode(Article::find($articleID))->name;

        //schickt die userID mit dem websocket controller an den broadcaster
        $webSocketApplicationController = new WebSocketApplicationController();
        $webSocketApplicationController->sendArticleOnSaleMessage($articleID, $article, 2);
    }
}
