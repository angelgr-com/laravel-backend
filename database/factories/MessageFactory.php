<?php

namespace Database\Factories;

use App\Models\Party;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $partyIds = Party::all()->pluck('id')->toArray();
        $userIds = User::all()->pluck('id')->toArray();
        
        return [
            'from'=>$this->faker->randomElement($userIds),
            'message'=>$this->faker->sentence(),
            'date'=>$this->faker->dateTime(),
            'party_id'=>$this->faker->randomElement($partyIds) 
        ];
    }
}
