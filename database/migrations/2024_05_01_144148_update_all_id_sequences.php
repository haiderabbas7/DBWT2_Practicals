<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tablesAndSequences = [
            'benutzer' => 'benutzer_id_seq',
            'article' => 'article_id_seq',
            'article_has_category' => 'article_has_category_id_seq',
            'articlecategory' => 'articlecategory_id_seq'
            // Wenn neue Tabellen hinzugefügt werden, muss hier immer hinzugefügt werden
        ];

        //Updated die Sequenz für alle oben angegebenen Tabellen
        foreach ($tablesAndSequences as $table => $sequence) {
            $maxId = DB::table("$table")->max('id');
            DB::statement("ALTER SEQUENCE $sequence RESTART WITH " . ($maxId + 1));

            DB::statement("SELECT setval('$sequence', (SELECT MAX(id) FROM $table))");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
