<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PaymentMethodsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('payment_methods')->delete();
        
        \DB::table('payment_methods')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Cash',
                'slug' => 'cash',
                'secret_key' => NULL,
                'public_key' => NULL,
                'hash_key' => NULL,
                'is_active' => 1,
                'is_cash' => 1,
                'created_at' => '2021-01-09 12:38:10',
                'updated_at' => '2021-01-09 12:38:10',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Stripe',
                'slug' => 'stripe',
                'secret_key' => 'sk_test_51H72HqAj5RlJyFb42rVtCc2crW3FxfKYhKnm1Cg9AIR3bDYMBhvZktRCHcZnmrIdEFCt580RBh9dUESlQiuUmHYP00qdNA5HTk',
                'public_key' => 'pk_test_51H72HqAj5RlJyFb4IT1DwAN61mqzEKwUPDvqf6O4lWtWSFUhGws3ZPdOZwfe39E3mNRNT33Zqn6Y70VDnxI2sKJs00H5xfGQif',
                'hash_key' => NULL,
                'is_active' => 1,
                'is_cash' => 0,
                'created_at' => '2021-01-09 12:38:10',
                'updated_at' => '2021-01-09 12:38:10',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Paystack',
                'slug' => 'paystack',
                'secret_key' => 'sk_test_6b0e65eda81cbf0a9d4d80db2d1d2859b2639f89',
                'public_key' => 'pk_test_8b36d9ab6b5b51683b2eeba68db3baebd0356eb8',
                'hash_key' => NULL,
                'is_active' => 1,
                'is_cash' => 0,
                'created_at' => '2021-01-09 12:38:10',
                'updated_at' => '2021-01-09 12:38:10',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'RazorPay',
                'slug' => 'razorpay',
                'secret_key' => 'UzT2ScT5iKRnlQwq2odNPzXI',
                'public_key' => 'rzp_test_hNMNePIhEgHgP7',
                'hash_key' => 'whsec_KPrzeWUZJbDa4ux6OOZ6TSgwETwJ5lCX',
                'is_active' => 1,
                'is_cash' => 0,
                'created_at' => '2021-01-09 12:38:10',
                'updated_at' => '2021-01-09 12:38:10',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Flutterwave',
                'slug' => 'flutterwave',
                'secret_key' => 'FLWSECK_TEST-66e0a213eea4a4710f2e027a678a64ee-X',
                'public_key' => 'FLWPUBK_TEST-77a19b7c22b4940d5f600a31d65cbfc3-X',
                'hash_key' => 'FLWSECK_TEST95206dccf3cd',
                'is_active' => 1,
                'is_cash' => 0,
                'created_at' => '2021-01-09 12:38:10',
                'updated_at' => '2021-01-09 12:38:10',
            ),
        ));
        
        
    }
}