<?php

namespace Database\Factories;

use App\Models\VerifyNumber;
use Illuminate\Database\Eloquent\Factories\Factory;

class VerifyNumberFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = VerifyNumber::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "contact" => $this->faker->text,
            "number" => rand(0, 1000),
            "verified" => false
        ];
    }
}
