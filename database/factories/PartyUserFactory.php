<?php

namespace Database\Factories;

use App\Models\Game;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PartyUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // Save all table IDs to an array to get a random element later
        $gameIds = Game::all()->pluck('id')->toArray();
        $userIds = User::all()->pluck('id')->toArray();

        return [
            'name'=>$this->faker->name(),
            'game_id'=>$this->faker->randomElement($gameIds),
            'user_id'=>$this->faker->randomElement($userIds)
        ];
    }
}
