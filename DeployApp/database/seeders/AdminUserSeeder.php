<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'lucas@realitybox.local'],
            [
                'name' => 'lucas',
                'password' => Hash::make('lucasrg01'),
                'is_admin' => true,
            ]
        );
    }
}
