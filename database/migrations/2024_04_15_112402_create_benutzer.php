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

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('benutzer');
    }
};
