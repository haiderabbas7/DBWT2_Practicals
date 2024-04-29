<?php

namespace App\Http\Controllers;

use App\Models\ArticleCategory;
use Illuminate\Http\Request;

class HomepageController
{
    public function index(){
        $kategorien = ArticleCategory::all();
        return view('index', ['kategorien' => $kategorien]);
    }
}
