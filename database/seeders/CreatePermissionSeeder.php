<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class CreatePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            ['name'=>'dashboard', 'status'=> 0],
            ['name'=>'order', 'status'=> 0],
            ['name'=>'orderList', 'status'=> 0],
            ['name'=>'orderList/totalMoney', 'status'=> 0],
            ['name'=>'processOrder', 'status'=> 0],
            ['name'=>'processOrder/refund', 'status'=> 0],
            ['name'=>'user', 'status'=> 0],
            ['name'=>'location', 'status'=> 0],
            ['name'=>'ride', 'status'=> 0],
            ['name'=>'agent', 'status'=> 0],
            ['name'=>'promotion', 'status'=> 0],
            ['name'=>'customertype', 'status'=> 0],
            ['name'=>'menu', 'status'=> 0],
            ['name'=>'pickupdropoff', 'status'=> 0],
            ['name'=>'report/daily', 'status'=> 0],
            ['name'=>'report/monthly', 'status'=> 0],
            ['name'=>'report/yearly', 'status'=> 0],
            ['name'=>'report/saleperformance', 'status'=> 0],
            ['name'=>'report/cashflow', 'status'=> 0],
            ['name'=>'report/debt', 'status'=> 0],
            ['name'=>'appearance', 'status'=> 0],
            ['name'=>'cmspagelist', 'status'=> 0],
        ];
    
        foreach ($permissions as $key => $permission) {
            Permission::create($permission);
        }
    }
}
