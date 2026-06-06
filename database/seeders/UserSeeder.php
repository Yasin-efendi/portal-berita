<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin (role_id = 1)
        User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@portal.com',
            'password' => Hash::make('password'),
            'role_id' => 1,
        ]);
        
        // Editor (role_id = 2)
        User::create([
            'name' => 'Editor Handal',
            'email' => 'editor@portal.com',
            'password' => Hash::make('password'),
            'role_id' => 2,
        ]);
        
        // Writer (role_id = 3)
        User::create([
            'name' => 'Penulis Kreatif',
            'email' => 'writer@portal.com',
            'password' => Hash::make('password'),
            'role_id' => 3,
        ]);
        
        // Reader (role_id = 4) - default
        User::create([
            'name' => 'Pembaca Setia',
            'email' => 'reader@portal.com',
            'password' => Hash::make('password'),
            'role_id' => 4,
        ]);
    }
}