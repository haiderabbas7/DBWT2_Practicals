<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ShoppingCartItem extends Model {
    use HasFactory;
    protected $table = 'shoppingcart_item';
    public $primaryKey = 'id';
    public $timestamps = false;
    public $incrementing = true;


    public $shoppingcart_id = 'shoppingcart_id';
    public $article_id = 'article_id';
    public $createdate = 'createdate';

    protected $fillable = ['shoppingcart_id', 'article_id', 'createdate'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($article) {
            if (empty($article->id)) {
                $maxId = DB::table('shoppingcart_item')->max('id');
                $article->id = $maxId + 1;
            }
        });
    }
}
