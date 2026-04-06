<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@lepenremen.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Employee 1',
            'email' => 'employee1@lepenremen.com',
            'password' => bcrypt('password'),
            'role' => 'employee',
        ]);

        User::create([
            'name' => 'Employee 2',
            'email' => 'employee2@lepenremen.com',
            'password' => bcrypt('password'),
            'role' => 'employee',
        ]);
    }
}
