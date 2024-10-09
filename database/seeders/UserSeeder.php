<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (app()->environment('production')) {
            return;
        }

        $users = [
            ['name' => 'TestUser1', 'phone_number' => '09799000001', 'address' => 'address 1'],
            ['name' => 'TestUser2', 'phone_number' => '09799000002', 'address' => 'address 2'],
            ['name' => 'TestUser3', 'phone_number' => '09799000003', 'address' => 'address 3'],
            ['name' => 'TestUser5', 'phone_number' => '09799000004', 'address' => 'address 4'],
            ['name' => 'TestUser6', 'phone_number' => '09799000005', 'address' => 'address 5'],
        ];

        foreach ($users as $user) {
            User::firstOrCreate([
                'phone_number' => $user['phone_number'],
            ], [
                'name' => $user['name'],
                'address' => $user['address'],
            ]);
        }
    }
}
