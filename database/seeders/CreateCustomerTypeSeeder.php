<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CustomerType;

class CreateCustomerTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $customerTypes = [
            [
                'name'=>'WALK-IN CUSTOMER',
                'code'=>'WALK-IN',
                'price'=> 500
             ],
            [
               'name'=>'AGENT',
               'code'=>'AGENT',
               'price'=> 500
            ],
            [
                'name'=>'LOCAL',
                'code'=>'LOCAL',
                'price'=> 250
            ],
            [
                'name'=>'SPECIAL',
                'code'=>'SPECIAL',
                'price'=> 0
             ],
            [
                'name'=>'AGENT500',
                'code'=>'AGENT500',
                'price'=> 400
            ],
            [
                'name'=>'LOCAL NO SONGTHEW',
                'code'=>'LOCALNOSONGTHEW',
                'price'=> 200
            ],
            [
                'name'=>'CHILDREN LOCAL (5-9)',
                'code'=>'CHILDRENLOCAL59',
                'price'=> 150
            ],
            [
                'name'=>'AGENT0',
                'code'=>'AGENT0',
                'price'=> 500
            ],
            [
                'name'=>'CHILDREN250',
                'code'=>'CHILDREN250',
                'price'=> 250
            ],
            [
                'name'=>'AGENT800',
                'code'=>'AGENT800',
                'price'=> 400
            ],
            [
                'name'=>'WORKER',
                'code'=>'WORKER',
                'price'=> 350
            ],
        ];
    
        foreach ($customerTypes as $key => $customerType) {
            CustomerType::create($customerType);
        }
    }
}
