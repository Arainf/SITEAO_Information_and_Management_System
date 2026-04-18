<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name'     => 'System Administrator',
            'email'    => 'admin@sims.local',
            'password' => Hash::make('password'),
            'role'     => User::ROLE_ADMIN,
            'status'   => User::STATUS_ACTIVE,
        ]);

        User::factory()->create([
            'name'     => 'Test Member',
            'email'    => 'member@sims.local',
            'password' => Hash::make('password'),
            'role'     => User::ROLE_MEMBER,
            'status'   => User::STATUS_ACTIVE,
        ]);

        User::factory()->create([
            'name'     => 'Pending User',
            'email'    => 'pending@sims.local',
            'password' => Hash::make('password'),
            'role'     => User::ROLE_PENDING,
            'status'   => User::STATUS_ACTIVE,
        ]);
    }
}
