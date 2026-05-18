<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admineplansunaya@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // User::create([
        //     'name' => 'Dosen',
        //     'email' => 'dosen@gmail.com',
        //     'password' => Hash::make('password'),
        //     'role' => 'dosen',
        // ]);

        // User::create([
        //     'name' => 'Mahasiswa',
        //     'email' => 'mahasiswa@gmail.com',
        //     'password' => Hash::make('password'),
        //     'role' => 'mahasiswa',
        // ]);
    }
}
