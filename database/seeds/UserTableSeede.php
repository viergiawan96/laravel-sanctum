<?php

use Illuminate\Database\Seeder;
use App\User;

class UserTableSeede extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();
        User::create([
            'name' => 'Satrio Yudho',
            'email' => 'viergiawan96@gmail.com',
            'password' => bcrypt('secret'),
            'role' => 'admin'
        ]);

        User::create([
            'name' => 'angga',
            'email' => 'angga@gmail.com',
            'password' => bcrypt('secret'),
            'role' => 'users'
        ]);
    }
}
