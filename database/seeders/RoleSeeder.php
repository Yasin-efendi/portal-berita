<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'admin', 'display_name' => 'Administrator'],
            ['name' => 'editor', 'display_name' => 'Editor'],
            ['name' => 'writer', 'display_name' => 'Penulis'],
            ['name' => 'reader', 'display_name' => 'Pembaca'],
        ];
        
        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}