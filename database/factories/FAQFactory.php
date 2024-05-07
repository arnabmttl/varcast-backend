<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FAQ>
 */
class FAQFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
         return [
            'question' => fake()->text(100),
            'answer' => fake()->text(250),
            'is_order' => $this->faker->numerify("###"),
            'status' => 'A',
        ];
    }
}
