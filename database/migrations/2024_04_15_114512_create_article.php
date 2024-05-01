<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('article', function (Blueprint $table) {
            $table->id();

            $table->string("name",80)
                ->nullable(false)
                ->comment("Name");

            $table->integer("price")
                ->nullable(false)
                ->comment("Preis in Cent");

            $table->string("description",1000)
                ->nullable(false)
                ->comment("Beschreibung, die die Güte oder die Beschaffenheit näher darstellt.
                                    Wird durch den „Ersteller“ (user) gepflegt");

            $table->unsignedBigInteger("creator_id")
                ->nullable(false)
                ->comment("Referenz auf den/die Nutzer:in, der den Artikel erstellt hat und verkaufen möchte");
            $table->foreign("creator_id")
                ->on("benutzer")
                ->references("id");

            $table->timestamp("createdate")
                ->nullable(false)
                ->comment("Zeitpunkt der Erstellung des Artikels");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article');
    }
};
