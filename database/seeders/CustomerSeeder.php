<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use Faker\Factory;
use DB;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
        
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Customer::truncate();

        foreach(range(10, 50) as $i) {
            Customer::create([
                'firstname' => $faker->firstname,
                'lastname' => $faker->lastname,
                'email' => $faker->safeEmail,
                'address'=> $faker->address,
                'phone' => $faker->numerify('##########')
            ]);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    }
}
