<?php

use Illuminate\Database\Seeder;

class user1Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("ALTER TABLE users AUTO_INCREMENT = 0;");
        $usuario = \App\User::create(['name' => 'danielamideros', 'email'=> 'daniela.mideroso@autonoma.edu.co','password'=>'102030']);       
    }
}
