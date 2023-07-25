<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Clear the users table before populating with new data
        DB::table('users')->truncate();

        // Generate dummy users
        User::create([
            'name' => 'Tia Espejo',
            'email' => 'tiaespejo@gmail.com',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'Angelo Beltran',
            'email' => 'angelobeltran@gmail.com',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'Hanna Medina',
            'email' => 'hannamedina@gmail.com',
            'password' => Hash::make('password'),
        ]);

        // Add more users as needed
    }
}
