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
            'username' => fake()->name(),
            'password' => $passwordHash, // password
            'email' => fake()->unique()->safeEmail(),
            'role' => $roles[array_rand($roles, 1)]
        ];
    }

}
