<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("users")->delete();
        DB::update("ALTER TABLE users AUTO_INCREMENT = 1;");
        $_users = array(
            [
                "user_name" => "Juan Dela Cruz",
                "password" => "password",
                "email" => "admin@yahoo.com",
                "role_id" => 1
            ],
            [
                "user_name" => "Jim Billo",
                "password" => "password",
                "email" => "user@yahoo.com",
                "role_id" => 2
            ]
        );
        foreach ($_users as $_user){
            App\User::create($_user);
        }
    }
}
