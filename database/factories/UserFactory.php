<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    public function definition(): array
    {
        $roles = array('admin', 'user');
        $num = rand();
        $passwordHash = hash('sha256', $num);
        return [
            'username' => fake()->userName(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'password' => $passwordHash, // password
            'email' => fake()->unique()->safeEmail(),
            'about_me' => fake()->paragraph(10),
            'role' => $roles[array_rand($roles, 1)],
            'registered_at' => fake()->dateTime()
        ];
    }

}
