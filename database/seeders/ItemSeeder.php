<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use Faker\Factory;
use DB;

class ItemSeeder extends Seeder
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

        Item::truncate();

        foreach(range(1, 10) as $i) {
            Item::create([
                'item_name' => 'Kagaj-Kharid-'.$i . $faker->name,
                'description' => 'Expense '.$i,
                'unit_price' => mt_rand(100, 1000)
            ]);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
    }
}
