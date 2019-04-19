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
        $categoria = \App\categoria::create(['nombre' => 'bebidas', 'descripcion'=> 'Categorias de refrescos y jugos','restaurante_id1'=>'1']);

    }
}
