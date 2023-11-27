<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class CreateRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            ['name'=>'admin', 'status'=> 0],
            ['name'=>'manager', 'status'=> 0],
            ['name'=>'moderator', 'status'=> 0],
            ['name'=>'viewer', 'status'=> 0],
            ['name'=>'creator', 'status'=> 0],
        ];
    
        foreach ($roles as $key => $role) {
            Role::create($role);
        }
    }
}
