<?php

namespace Database\Seeders;

use App\Models\Ab_User;
use Illuminate\Database\Seeder;

class FakeUserSeeder extends Seeder
{
    public function run(): void
    {
        //create = generieren und direkt in Tabelle einfügen
        //Ab_User::factory()->count(10)->create();
        Ab_User::factory()->count(20)->create();
    }
}
