<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommunityFactory extends Factory
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
            "title" => $this->faker->unique()->domainName(),
            "description" => $this->faker->paragraph,
            "url" => $this->faker->url,
            "accept" => 1,
            "has_admin" => 1,
        ];
    }
}
