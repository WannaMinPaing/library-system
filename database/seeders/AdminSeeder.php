<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (app()->environment('production')) {
            return;
        }

        $admins = [
            ['name' => 'TestAdmin1', 'email' => 'testadmin1@gmail.com', 'password' => 'password'],
            ['name' => 'TestAdmin2', 'email' => 'testadmin2@gmail.com', 'password' => 'password'],
            ['name' => 'TestAdmin3', 'email' => 'testadmin3@gmail.com', 'password' => 'password'],
            ['name' => 'TestAdmin5', 'email' => 'testadmin4@gmail.com', 'password' => 'password'],
            ['name' => 'TestAdmin6', 'email' => 'testadmin5@gmail.com', 'password' => 'password'],
        ];

        foreach ($admins as $admin) {
            Admin::firstOrCreate([
                'email' => $admin['email'],
            ], [
                'name' => $admin['name'],
                'password' => bcrypt($admin['password']),
            ]);
        }
    }
}
