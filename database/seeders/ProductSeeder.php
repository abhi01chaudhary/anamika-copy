<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::truncate();

        foreach(range(10, 60) as $i) {
            Product::create([
                'item_code' => 'PDT-1000'.$i,
                'description' => 'Name of Product '.$i,
                'unit_price' => mt_rand(100, 1000)
            ]);
        }
    }
}
