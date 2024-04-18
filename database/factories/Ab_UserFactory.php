<?php

namespace Database\Factories;

use App\Helpers\UserHelper;
use App\Models\Ab_User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Ab_User>
 */
class Ab_UserFactory extends Factory
{

    public function definition(): array
    {
        return [
            'ab_name' => fake()->name(),
            //Str::random erzeugt nen random String mit der angegebenen Länge
            'ab_password' => sha1(UserHelper::get_salt() . Str::random(15)),
            'ab_mail' => fake()->unique()->safeEmail()
        ];
    }
}
