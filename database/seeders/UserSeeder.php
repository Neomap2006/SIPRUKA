<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'nama' => 'Admin User', // ubah 'nama' jadi 'name'
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'nama' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
        ]);

        User::create([
            'nama' => 'ARPAN',
            'email' => 'Arpan123@gmail.com',
            'password' => Hash::make('Admin123'),
            'role' => 'admin',
        ]);
    }
}
