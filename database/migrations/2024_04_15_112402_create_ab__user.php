<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ab_user', function (Blueprint $table) {
            $table->id();

            $table->string('ab_name',80)
                ->nullable(false)
                ->unique(true)
                ->comment("Name");

            $table->string('ab_password', 200)
                ->nullable(false)
                ->comment("Passwort");

            $table->string("ab_mail",200)
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
        Schema::dropIfExists('ab_user');
    }
};
