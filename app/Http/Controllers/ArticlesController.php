<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ArticlesController
{
    /**
     * Displays the articles requested. When nothing fitting the requirements is requested, shows all existing articles in the database.
     *
     * @param Request $request The request for the to be shown articles
     * @return void
     */
    public function articles(Request $request) {
        // Get the search term from the URL
        $search = $request->query('search');

        // If the search term is provided, search for the articles that contain the term
        // If no search term is provided, get all articles
        $articles_req = isset($search) ? DB::table('article')
            ->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($search) . '%'])
            ->get() : DB::table('article')->get();

        foreach ($articles_req as $article) {
            $article->image_path = $this->getArticleImagePath($article->id);
        }
        return view('articles', ['articles_req' => $articles_req]);
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
}
