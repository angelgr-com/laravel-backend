<?php

namespace Database\Factories;

use App\Models\Party;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class Party_UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // Save all table IDs to an array to get a random element later
        $partyIds = Party::all()->pluck('id')->toArray();
        $userIds = User::all()->pluck('id')->toArray();

        return [
            'party_id'=>$this->faker->randomElement($partyIds),
            'user_id'=>$this->faker->randomElement($userIds)
        ];
    }
}
