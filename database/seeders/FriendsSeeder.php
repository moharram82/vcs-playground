<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class FriendsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $friends = [
            ['requester_id' => 1, 'invited_id' => 2, 'is_request' => true, 'accepted_at' => null],
            ['requester_id' => 2, 'invited_id' => 3, 'is_request' => false, 'accepted_at' => now()],
        ];

        foreach ($friends as $friend) {
            $fri = \App\Models\Friend::create($friend);
        }
    }
}
