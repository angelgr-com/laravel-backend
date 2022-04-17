<?php

namespace Database\Seeders;

use App\Models\Party_User;
use Illuminate\Database\Seeder;

class PartyUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Party_User::factory()->times(10)->create();
    }
}
