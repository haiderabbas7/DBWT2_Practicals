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
        Schema::create('ab_article_has_articlecategory', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger("ab_articlecategory_id")
                ->nullable(false)
                ->comment("Referenz auf eine Artikelkategorie");
            $table->foreign("ab_articlecategory_id")
                ->on("ab_articlecategory")
                ->references("id");

            $table->unsignedBigInteger("ab_article_id")
                ->nullable(false)
                ->comment("Referenz auf einen Artike");
            $table->foreign("ab_article_id")
                ->on("ab_article")
                ->references("id");


            //Kombination aus ab_articlecategory_id und ab_article_id sind unique
            $table->unique(["ab_articlecategory_id","ab_article_id"]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ab_article_has_articlecategory');
    }
};
