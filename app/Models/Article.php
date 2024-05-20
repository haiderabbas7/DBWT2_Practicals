<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Article extends Model
{
    use HasFactory;
    public $table = 'article';
    public $primaryKey = 'id';
    public $timestamps = false;
    public $incrementing = false;


    public $name = 'name';
    public $price = 'price';
    public $description = 'description';
    public $creator_id = 'creator_id';
    public $createdate = 'createdate';

    protected $fillable = ['name', 'price', 'description', 'creator_id', 'createdate'];

    //real talk, den code hab ich von copilot. das ist, damit es keine lücken in den AUTOINCREMENT IDs mehr gibt
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($article) {
            if (empty($article->id)) {
                $maxId = DB::table('article')->max('id');
                $article->id = $maxId + 1;
            }
        });
    }
}
