<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user = User::first() ?? User::factory()->create();

        $post = Post::first() ?? Post::factory()->create();

        return [
            "user_id" => $user->id,
            "post_id" => $post->id,
            "description" => $this->faker->sentence
        ];
    }
}
