<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ArticlesController
{
    /**
     * Displays the articles requested. When nothing fitting the requirements is requested, shows all existing articles in the database.
     *
     * @param Request $request The request for the to be shown articles
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function articles(Request $request) {
        // Get the search term from the URL
        $search = $request->query('search');

        // If the search term is provided, search for the articles that contain the term
        // If no search term is provided, get all articles

        // Unnötig
        $articles_req = isset($search) ? DB::table('article')
            ->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($search) . '%'])
            ->get() : DB::table('article')->get();

        foreach ($articles_req as $article) {
            $article->image_path = $this->getArticleImagePath($article->id);
        }

        //hier placeholder evtl rausmachen
        $shoppingcart_id = "1";

        return view('articles', ['articles_req' => $articles_req, 'shoppingcart_id' => $shoppingcart_id]);
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


    public function search_articles_view(){
        return view('search_articles');
    }


    /*public function search_api(Request $request) {
        $search = $request->get('search');
        $articles = Article::where('name', 'like', '%' . $search . '%')->get();
        return response()->json($articles);
    }*/

    public function search_api(Request $request) {
        $search = $request->get('search');
        $articles = isset($search) ? DB::table('article')
            ->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($search) . '%'])
            ->get() : DB::table('article')->get();
        foreach ($articles as $article) {
            $article->image_path = $this->getArticleImagePath($article->id);
        }
        return response()->json($articles);
    }
}
