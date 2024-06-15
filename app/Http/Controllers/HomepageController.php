<?php

namespace App\Http\Controllers;

use App\Models\ArticleCategory;
use Illuminate\Http\Request;

class HomepageController
{
    public function index(){
        return view('index');
    }

    public function getKategorien_api(){
        $kategorien = ArticleCategory::all();
        return response()->json($kategorien);
    }
}
