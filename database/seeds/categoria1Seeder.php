<?php

use Illuminate\Database\Seeder;

class categoria1Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("ALTER TABLE categorias AUTO_INCREMENT = 0;");
        $categoria = \App\categoria::create(['nombre' => 'Bebidas', 'descripcion'=> 'Categorias de refrescos y jugos','restaurante_id1'=>'1']);

    }
}
