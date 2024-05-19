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
        Schema::create('article_has_category', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger("articlecategory_id")
                ->nullable(false)
                ->comment("Referenz auf eine Artikelkategorie");
            $table->foreign("articlecategory_id")
                ->on("articlecategory")
                ->references("id");

            $table->unsignedBigInteger("article_id")
                ->nullable(false)
                ->comment("Referenz auf einen Artike");
            $table->foreign("article_id")
                ->on("article")
                ->references("id");

            //Kombination aus articlecategory_id und article_id sind unique
            $table->unique(["articlecategory_id","article_id"]);
        });

        //zum updaten der ID sequenz
        $maxId = DB::table('article_has_category')->max('id') ?? 0;
        DB::statement("ALTER SEQUENCE article_has_category_id_seq RESTART WITH " . ($maxId + 1));
        DB::statement("SELECT setval('article_has_category_id_seq', (SELECT MAX(id) FROM article_has_category))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_has_category');
    }
};
