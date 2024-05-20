<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ArticleCategory extends Model
{
    use HasFactory;
    public $table = 'articlecategory';
    public $timestamps = false;
    public $primaryKey = 'id';
    public $name = 'name';
    public $description = 'description';
    public $parent = 'parent';

    //real talk, den code hab ich von copilot. das ist, damit es keine lücken in den AUTOINCREMENT IDs mehr gibt
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($article) {
            if (empty($article->id)) {
                $maxId = DB::table('articlecategory')->max('id');
                $article->id = $maxId + 1;
            }
        });
    }
}
