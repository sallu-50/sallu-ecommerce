<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\User; // important
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        // Puraton order gulo delete korte
        Order::query()->delete();

        // Existing user IDs ber koro
        $userIds = User::pluck('id')->toArray(); // ekhane shob existing user id pabo

        // Check jodi kono user e na thake
        if (empty($userIds)) {
            throw new \Exception('No users found. Please seed users first.');
        }

        for ($i = 0; $i < 10; $i++) {
            Order::create([
                'user_id' => $userIds[array_rand($userIds)], // existing theke random user_id
                'total' => rand(500, 10000) / 100,
                'status' => ['pending', 'processing', 'completed', 'canceled'][rand(0, 3)],
                'created_at' => now()->subDays(rand(0, 30)),
                'updated_at' => now(),
            ]);
        }
    }
}
