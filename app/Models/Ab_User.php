<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ab_User extends Model
{
    use HasFactory;
    public $table = 'ab_user';
    public $timestamps = false;
    public $primaryKey = 'id';
    public $ab_name = 'ab_name';
    public $ab_password = 'ab_password';
    public $ab_mail = 'ab_mail';

}
