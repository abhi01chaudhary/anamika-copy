<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory;
use App\Models\Vendor;
use DB;

class VendorSeeder extends Seeder
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

        Vendor::truncate();

        foreach(range(10, 50) as $i) {
            Vendor::create([
                'firstname' => $faker->firstname,
                'lastname' => $faker->lastname,
                'vendor_name' => 'Vendor ' . $faker->lastname,
                'email' => $faker->safeEmail,
                'address'=> $faker->address,
                'phone' => $faker->numerify('##########')
            ]);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    }
}
