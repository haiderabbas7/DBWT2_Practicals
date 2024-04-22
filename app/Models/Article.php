<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;
    public $table = 'article';
    public $primaryKey = 'id';
    public $timestamps = false;
    public $name = 'name';
    public $price = 'price';
    public $description = 'description';
    public $creator_id = 'creator_id';
    public $createdate = 'createdate';
}
