<?php

namespace Database\Factories;

use App\Models\Board;
use App\Models\Community;
use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class VisitFactory extends Factory
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
            "ip" => $this->faker->ipv6(),
            "user_id" => $user->id,
            "community_id" => null,
            "board_id" => null,
            "post_id" => null,
            "created_at" => Carbon::now(),
        ];
    }
}
