<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ab_Articlecategory extends Model
{
    use HasFactory;
    public $table = 'ab_articlecategory';
    public $timestamps = false;
    public $primaryKey = 'id';
    public $ab_name = 'ab_name';
    public $ab_description = 'ab_description';
    public $ab_parent = 'ab_parent';
}
