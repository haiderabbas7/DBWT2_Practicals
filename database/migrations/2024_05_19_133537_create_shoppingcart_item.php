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
        Schema::create('shoppingcart_item', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger("shoppingcart_id")
                ->nullable(false)
                ->comment("Referenz auf den Warenkorb");
            $table->foreign("shoppingcart_id")
                ->on("shoppingcart")
                ->references("id")
                ->onDelete('cascade');

            $table->unsignedBigInteger("article_id")
                ->nullable(false)
                ->comment("Referenz auf den Artike");
            $table->foreign("article_id")
                ->on("article")
                ->references("id");

            $table->timestamp("createdate")
                ->nullable(false)
                ->comment("Zeitpunkt der Erstellung des Artikels");
        });

        //zum updaten der ID sequenz
        $maxId = DB::table('shoppingcart_item')->max('id') ?? 0;
        DB::statement("ALTER SEQUENCE shoppingcart_item_id_seq RESTART WITH " . ($maxId + 1));
        DB::statement("SELECT setval('shoppingcart_item_id_seq', (SELECT MAX(id) FROM shoppingcart_item))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shoppingcart_item');
    }
};
