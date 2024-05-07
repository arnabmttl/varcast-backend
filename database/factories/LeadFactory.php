<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vendor>
 */
class LeadFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => $this->faker->text(20),
            'qty' => $this->faker->numerify("#"),
            'unit' => 'Piece',
            'budget' => $this->faker->numerify("#####"), // password
            'address' => $this->faker->text(50), // password
            'details' => $this->faker->text(100), // password
            'country_id' => 101, // password
            'mobile' => $this->faker->numerify("##########"),
            'status' => 'N'
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}