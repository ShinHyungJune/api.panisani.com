<?php

namespace Database\Factories;

use App\Models\Youtube;
use Illuminate\Database\Eloquent\Factories\Factory;

class YoutubeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "title" => $this->faker->title,
            "url" => $this->faker->url,
            "thumbnail" => $this->faker->url,
            "key" => $this->faker->randomKey,
            "order" => Youtube::count(),
        ];
    }
}
