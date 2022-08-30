<?php

namespace Database\Factories;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MusicFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->text(20),
            'user_id' => User::factory(),
            'lyrics' => $this->faker->text(200),
            'length' => rand(10, 1000),
            'category_id' => Category::factory(),
            'tags' => $this->faker->text()
        ];
    }
}
