<?php

use Illuminate\Database\Seeder;

class administrador1Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("ALTER TABLE categorias AUTO_INCREMENT = 0;");
        $administrador = \App\administrador::create(['nombre' => 'Alejandro', 'password'=> '12345678','restaurante_id'=> '1']);
    
    }
}
