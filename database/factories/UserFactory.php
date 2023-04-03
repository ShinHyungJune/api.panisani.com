<?php

namespace Database\Factories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'admin' => false,
            'ids' => $this->faker->name(),
            'name' => $this->faker->name(),
            'sex' => "공개안함",
            'birth' => Carbon::now()->format("Y"),
            'contact' => $this->faker->phoneNumber,
            'email' => $this->faker->email,

            'address' => $this->faker->paragraph,
            'address_detail' => $this->faker->paragraph,
            'address_zipcode' => $this->faker->paragraph,

            'ids_recommend' => $this->faker->paragraph,
            'reason_leave_out' => $this->faker->paragraph,
            'point' => 0,

            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            "created_at" => Carbon::now(),
            "updated_at" => Carbon::now(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'verified_at' => null,
            ];
        });
    }
}
