<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ShoppingCart extends Model {
    use HasFactory;

    protected $table = 'shoppingcart';
    public $primaryKey = 'id';
    public $timestamps = false;
    public $incrementing = true;

    public $user_id = 'user_id';
    public $createdate = 'createdate';

    protected $fillable = ['user_id', 'createdate'];

    //real talk, den code hab ich von copilot. das ist, damit es keine lücken in den AUTOINCREMENT IDs mehr gibt
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($article) {
            if (empty($article->id)) {
                $maxId = DB::table('shoppingcart')->max('id');
                $article->id = $maxId + 1;
            }
        });
    }
}
