<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Benutzer extends Model
{
    use HasFactory;
    public $table = 'benutzer';
    public $primaryKey = 'id';
    public $timestamps = false;
    public $incrementing = false;

    public $name = 'name';
    public $password = 'password';
    public $mail = 'mail';

    protected $fillable = ['name', 'password', 'mail'];

    //real talk, den code hab ich von copilot. das ist, damit es keine lücken in den AUTOINCREMENT IDs mehr gibt
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($article) {
            if (empty($article->id)) {
                $maxId = DB::table('benutzer')->max('id');
                $article->id = $maxId + 1;
            }
        });
    }
}
