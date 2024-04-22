<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class TestData extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'testdata';
    public $timestamps = false;
}
