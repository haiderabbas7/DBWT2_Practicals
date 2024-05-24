<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShoppingCart extends Model {
    use HasFactory;

    protected $table = 'shoppingcart';
    public $primaryKey = 'id';
    public $timestamps = false;
    public $incrementing = true;

    public $user_id = 'user_id';
    public $createdate = 'createdate';

    protected $fillable = ['user_id', 'createdate'];
}
