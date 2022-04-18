<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OwnerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // owners(id, user_id)
        // Save all table IDs to an array to get a random element later
        $userIds = User::all()->pluck('id')->toArray();
        return [
            'user_id'=>$this->faker->randomElement($userIds)
        ];
    }
}
