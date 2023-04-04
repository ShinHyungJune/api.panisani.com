<?php

namespace Database\Factories;

use App\Models\Board;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user = User::first() ?? User::factory()->create();
        $board = Board::first() ?? Board::factory()->create();

        return [
            "user_id" => $user->id,
            "board_id" => $board->id,
            "community_id" => $board->community_id,
            "title" => $this->faker->title,
            "description" => $this->faker->paragraph,
        ];
    }
}
