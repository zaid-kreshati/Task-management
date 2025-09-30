<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Repositories\UserRepository;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database with users.
     *
     * @return void
     */
    public function run()
    {
        // Define an array of user data
        $users = [
            [
                'name' => 'Zaid ',
                'email' => 'Zaid@gmail.com',
                'password' => Hash::make('password123'), 
            ],
            [
                'name' => ' Malek',
                'email' => 'Malek@gmail.com',
                'password' => Hash::make('password123'),
            ],
            [
                'name' => 'Ameen',
                'email' => 'Ameen@gmail.com',
                'password' => Hash::make('password123'),
            ],
            [
                'name' => 'Admin',
                'email' => 'Admin@gmail.com',
                'password' => Hash::make('password123'),
            ],
        ];

         // Insert users into the database
         foreach ($users as $user) {
            User::create($user);
        }

       
    }
}





