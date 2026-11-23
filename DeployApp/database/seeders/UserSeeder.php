<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment('local', 'testing')) {
            return;
        }

        User::firstOrCreate(
            ['email' => 'admin@realitybox.local'],
            [
                'name' => 'admin',
                'password' => Hash::make('1234'),
                'is_admin' => true,
                'demo_balance' => 500,
            ]
        );

        User::firstOrCreate(
            ['email' => 'test@realitybox.local'],
            [
                'name' => 'test',
                'password' => Hash::make('1234'),
                'is_admin' => false,
                'demo_balance' => 250,
            ]
        );
    }
}
