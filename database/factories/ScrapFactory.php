<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScrapFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $post = Post::first() ?? Post::factory()->create();
        $user = User::first() ?? User::factory()->create();

        return [
            "post_id" => $post->id,
            "user_id" => $user->id,
        ];
    }
}
