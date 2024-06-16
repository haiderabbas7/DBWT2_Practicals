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
        Schema::create('shoppingcart', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger("creator_id")
                ->nullable(false)
                ->comment("Referenz auf den/die Benutzer:in, dem der Warenkorb gehört");
            $table->foreign("creator_id")
                ->on("benutzer")
                ->references("id");

            $table->timestamp("createdate")
                ->nullable(false)
                ->comment("Zeitpunkt der Erstellung des Artikels");
        });

        //zum updaten der ID sequenz
        $maxId = DB::table('shoppingcart')->max('id') ?? 0;
        DB::statement("ALTER SEQUENCE shoppingcart_id_seq RESTART WITH " . ($maxId + 1));
        DB::statement("SELECT setval('shoppingcart_id_seq', (SELECT MAX(id) FROM shoppingcart))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shoppingcart');
    }
};
