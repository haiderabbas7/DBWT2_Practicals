<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleCategory extends Model
{
    use HasFactory;
    public $table = 'articlecategory';
    public $timestamps = false;
    public $primaryKey = 'id';
    public $name = 'name';
    public $description = 'description';
    public $parent = 'parent';
}
