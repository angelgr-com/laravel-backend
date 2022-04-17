<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class GameFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title'=>$this->faker->word(),
            'thumbnail_url'=>$this->faker->imageUrl(360, 360, 'animals', true, 'dogs', true),
            'url'=>$this->faker->url()
        ];
    }
}
