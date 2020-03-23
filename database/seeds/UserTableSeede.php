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
            'name' => 'Anugrah Sandi',
            'email' => 'admin@daengweb.id',
            'password' => bcrypt('secret'),
            'role' => 'admin'
        ]);

        User::create([
            'name' => 'Riski Amelia',
            'email' => 'riski@daengweb.id',
            'password' => bcrypt('secret'),
            'role' => 'users'
        ]);
    }
}
