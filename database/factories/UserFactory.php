<?php

namespace Database\Factories;

use App\Helpers\UserHelper;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            //Str::random erzeugt nen random String mit der angegebenen Länge
            'password' => sha1(UserHelper::get_salt() . Str::random(15)),
            'mail' => fake()->unique()->safeEmail()
        ];
    }
}
