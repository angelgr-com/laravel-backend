<?php

namespace Database\Factories;

use App\Models\Party;
use App\Models\User;
use DateTime;
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
        $now = new DateTime();
        $now = $now->format('Y-m-d H:i:s');

        return [
            'from'=>$this->faker->randomElement($userIds),
            'message'=>$this->faker->sentence(),
            'date'=>$now,
            'party_id'=>$this->faker->randomElement($partyIds) 
        ];
    }
}
