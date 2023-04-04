<?php

namespace Database\Factories;

use App\Models\Community;
use Illuminate\Database\Eloquent\Factories\Factory;

class BoardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $community = Community::factory()->create();

        return [
            "user_id" => $community->user_id,
            "community_id" => $community->id,
            "title" => $this->faker->title,
            "order" => $community->boards()->count(),
            "open" => 1,
        ];
    }
}
