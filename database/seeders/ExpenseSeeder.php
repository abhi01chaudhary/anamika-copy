<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Expense;
use App\Models\ExpenseItem;
use Faker\Factory;
use DB;

class ExpenseSeeder extends Seeder
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

        Expense::truncate();
        ExpenseItem::truncate();

        foreach(range(1, 30) as $i) {
            $expense = Expense::create([
                'number' => 'EXP-2000'.$i,
                'vendor_id' => $i,
                'date' => '2078-12-'.$i,
                'due_date' => '2079-01-'.$i,
                'reference' => 'LPO #'.$i,
                'terms_and_conditions' => $faker->text,
                'discount' => mt_rand(0, 100),
                'sub_total' => mt_rand(1000, 2000),
                'status' => rand(0,1) ? 'Paid' :  'Un paid'
            ]);

            foreach(range(1, mt_rand(2, 4)) as $j) {
                ExpenseItem::create([
                    'expense_id' => $expense->id,
                    'item_id' => mt_rand(1, 40),
                    'unit_price' => mt_rand(100, 500),
                    'qty' => mt_rand(1, 6)
                ]);
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
