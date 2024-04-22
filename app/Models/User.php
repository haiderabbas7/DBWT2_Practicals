<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;
    public $table = 'user';
    public $timestamps = false;
    public $primaryKey = 'id';
    public $name = 'name';
    public $password = 'password';
    public $mail = 'mail';

}
