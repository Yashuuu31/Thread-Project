<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public static function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'User1',
                'email' => 'user1@gmail.com',
                'password' => Hash::make('123456'),
            ],
            [
                'name' => 'User2',
                'email' => 'user2@gmail.com',
                'password' => Hash::make('123456'),
            ],
            [
                'name' => 'User3',
                'email' => 'user3@gmail.com',
                'password' => Hash::make('123456'),
            ]
        ]);
    }
}
