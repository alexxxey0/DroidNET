<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use \App\Models\User;
use \App\Models\Post;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $users = User::all()->pluck('username')->toArray();
        $posts = Post::all()->pluck('id')->toArray();
        return [
            'post' => $posts[array_rand($posts, 1)],
            'author' => $users[array_rand($users, 1)],
            'content' => fake()->paragraph(2)
        ];
    }
}
