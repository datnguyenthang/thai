<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;

class CreatePaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $paymentMethods = [
            [
               'name'=> CASH,
               'description'=> 'Cash payment method',
               'isTransaction'=> 0,
            ],
            [
                'name'=> BANKTRANSFER,
                'description'=>'Bank Transfer payment method',
                'isTransaction'=> 1,
            ],
            [
                'name'=> CARD,
                'description'=>'Card payment method',
                'isTransaction'=> 1,
             ],
            [
                'name'=> PROMPTPAY,
                'description'=>'Promptpay QR payment method',
                'isTransaction'=> 1,
            ],
        ];
    
        foreach ($paymentMethods as $key => $paymentMethod) {
            PaymentMethod::create($paymentMethod);
        }
    }
}
