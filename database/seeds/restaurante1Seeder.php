<?php

use Illuminate\Database\Seeder;

class restaurante1Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("ALTER TABLE restaurantes AUTO_INCREMENT = 0;");
        $restaurante = \App\restaurante::create(['name' => 'ComeBot', 'direccion'=> 'Calle siempre viva']);

    }
}
