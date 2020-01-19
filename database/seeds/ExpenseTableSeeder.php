<?php

use Illuminate\Database\Seeder;

class ExpenseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("expenses")->delete();
        DB::update("ALTER TABLE expenses AUTO_INCREMENT = 1;");
        $_expenses = array(
            [
                "category_id" => 1,
                "amount" => "230",
                "entry_date" => date("Y-m-d")
            ],
            [
                "category_id" => 2,
                "amount" => "20",
                "entry_date" => date("Y-m-d")
            ]
        );
        foreach ($_expenses as $_expense){
            App\Expenses::create($_expense);
        }
    }
}
