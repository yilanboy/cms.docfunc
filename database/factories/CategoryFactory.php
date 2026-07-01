<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            'name'        => $this->faker->unique()->word(),
            'icon'        => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"></svg>',
            'description' => $this->faker->sentence(),
        ];
    }
}
