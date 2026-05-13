<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->delete();

        User::create([
            'name' => 'admin',
            'email' => 'admin@realitybox.local',
            'password' => Hash::make('1234'),
            'is_admin' => true,
            'demo_balance' => 500,
        ]);

        User::create([
            'name' => 'test',
            'email' => 'test@realitybox.local',
            'password' => Hash::make('1234'),
            'is_admin' => false,
            'demo_balance' => 250,
        ]);
    }
}
