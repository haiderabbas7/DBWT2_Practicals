<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('benutzer', function (Blueprint $table) {
            $table->id();

            $table->string('name',80)
                ->nullable(false)
                ->unique(true)
                ->comment("Name");

            $table->string('password', 200)
                ->nullable(false)
                ->comment("Passwort");

            $table->string("mail",200)
                ->nullable(false)
                ->unique(true)
                ->comment("E-Mail-Adresse");
        });

        //zum updaten der ID sequenz
        $maxId = DB::table('benutzer')->max('id') ?? 0;
        DB::statement("ALTER SEQUENCE benutzer_id_seq RESTART WITH " . ($maxId + 1));
        DB::statement("SELECT setval('benutzer_id_seq', (SELECT MAX(id) FROM benutzer))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('benutzer');
    }
};
