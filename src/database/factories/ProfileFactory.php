<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $postcode = $this->faker->postcode();

        return [
            'user_id' => User::factory(),
            'profile_image' => 'profile_sample/default-user.png',
            'postal_code' => substr_replace($postcode, '-', 3, 0),
            'address' => $this->faker->address(),
            'building' => $this->faker->optional()->secondaryAddress(),
        ];
    }
}
