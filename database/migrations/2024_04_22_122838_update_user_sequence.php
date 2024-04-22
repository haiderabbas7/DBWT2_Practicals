<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        /* Zum einmaligen Resetten der Sequenz
        $maxId = DB::table('user')->max('id');
        DB::statement("ALTER SEQUENCE user_id_seq RESTART WITH " . ($maxId + 1));*/

        //Setzt die Sequenz der Primärschlüssel, sodass sie von den Daten in der Tabelle abhängt
        DB::statement("SELECT setval('user_id_seq', (SELECT MAX(id) FROM user))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
