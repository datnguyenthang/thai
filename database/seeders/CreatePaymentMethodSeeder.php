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
               'name'=>'Cash',
               'description'=>'Cash payment method',
               'isTransaction'=>0,
            ],
            [
                'name'=>'Bank Transfer',
                'description'=>'Bank Transfer payment method',
                'isTransaction'=>1,
            ],
            [
                'name'=>'Card',
                'description'=>'Card payment method',
                'isTransaction'=>1,
             ],
            [
                'name'=>'QRcode',
                'description'=>'QR payment method',
                'isTransaction'=>1,
            ],
        ];
    
        foreach ($paymentMethods as $key => $paymentMethod) {
            PaymentMethod::create($paymentMethod);
        }
    }
}
