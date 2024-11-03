<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Chirp>
 */
class ChirpFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Chirp::class;

    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(), // Creates a user and assigns its ID to this chirp
            'message' => $this->faker->sentence(),    // Generates a random sentence for the chirp message
        ];
    }
}
