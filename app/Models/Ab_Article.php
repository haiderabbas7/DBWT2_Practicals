<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ab_Article extends Model
{
    use HasFactory;
    public $table = 'ab_article';
    public $primaryKey = 'id';
    public $timestamps = false;
    public $ab_name = 'ab_name';
    public $ab_price = 'ab_price';
    public $ab_description = 'ab_description';
    public $ab_creator_id = 'ab_creator_id';
    public $ab_createdate = 'ab_createdate';
}
