<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete(); 
        DB::table('users')->insert([
            'name' => 'Harry Potter',
            'email' => 'harry@potter.com',
            'password' => app('hash')->make('123456'),
            'admin' => User::ADMIN_USER,
        ]);

        DB::table('users')->insert([
            'name' => 'Goldberg',
            'email' => 'goldberg@wwe.com',
            'password' => app('hash')->make('123456'),
            'admin' => User::REGULAR_USER,
        ]);
    }
}
