<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class FakeUserSeeder extends Seeder
{
    public function run(): void
    {
        //create = generieren und direkt in Tabelle einfügen, make() erstellt nur die Daten
        User::factory()->count(20)->create();
    }
}
