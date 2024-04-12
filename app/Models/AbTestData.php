<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class AbTestData extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'ab_testdata';
    public $timestamps = false;
}
