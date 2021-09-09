<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Product::truncate();

        foreach(range(10, 60) as $i) {
            Product::create([
                'item_code' => 'PDT-Copy-SLB'.$i,
                'description' => 'Super Long Book '.$i,
                'unit_price' => mt_rand(100, 1000),
                'quantity' => mt_rand(10, 1000),
            ]);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
