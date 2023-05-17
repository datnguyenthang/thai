<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class CreateUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
               'name'=>'Admin User',
               'email'=>'admin@admin.com',
               'role'=>1,
               'password'=> bcrypt('123456'),
            ],
            [
               'name'=>'Manager User',
               'email'=>'manager@manager.com',
               'role'=> 2,
               'password'=> bcrypt('123456'),
            ],
            [
                'name'=>'Moderator User',
                'email'=>'moderator@moderator.com',
                'role'=> 3,
                'password'=> bcrypt('123456'),
             ],
            [
               'name'=>'User',
               'email'=>'user@user.com',
               'role'=> 0,
               'password'=> bcrypt('123456'),
            ],
        ];
    
        foreach ($users as $key => $user) {
            User::create($user);
        }
    }
}
