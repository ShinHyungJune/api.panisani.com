<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class QnaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user = User::first() ?? User::factory()->create();

        return [
            "user_id" => $user->id,
            "description" => $this->faker->paragraph,
        ];
    }
}
