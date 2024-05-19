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
        Schema::create('articlecategory', function (Blueprint $table) {
            $table->id();

            $table->string('name',100)
                ->nullable(false)
                ->unique(true)
                ->comment("Name");

            $table->string('description',1000)
                ->nullable()
                ->comment("Beschreibung");

            $table->unsignedBigInteger("parent")
                ->nullable()
                ->comment("Referenz auf die mögliche Elternkategorie.
                                    Artikelkategorien sind hierarchisch organisiert. Eine Kategorie
                                    kann beliebig viele Kind Kategorien haben. Eine Kategorie kann
                                    nur eine Elternkategorie besitzen.
                                    NULL, falls es keine Elternkategorie gibt und es sich um eine
                                    Wurzelkategorie handelt.");
            $table->foreign("parent")
                ->on("article")
                ->references("id");
        });

        //zum updaten der ID sequenz
        $maxId = DB::table('articlecategory')->max('id') ?? 0;
        DB::statement("ALTER SEQUENCE articlecategory_id_seq RESTART WITH " . ($maxId + 1));
        DB::statement("SELECT setval('articlecategory_id_seq', (SELECT MAX(id) FROM articlecategory))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articlecategory');
    }
};
