<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class ProductFactory extends Factory
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
            'product_image' => $this->faker->imageUrl(640, 480, 'fashion', true, 'Product'),
            'name' => $this->faker->words(2, true),
            'brand' => $this->faker->optional()->company,
            'price' => $this->faker->numberBetween(1000, 20000),
            'description' => $this->faker->paragraph,
            'condition' => $this->faker->randomElement(['良好', '目立った傷や汚れなし', 'やや傷や汚れあり', '状態が悪い']),
            'selling_status' => $this->faker->boolean(20),
        ];
    }
}
