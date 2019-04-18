<?php

use Illuminate\Database\Seeder;

class categoria2Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categoria = \App\categoria::create(['nombre' => 'Postres', 'descripcion'=> 'Categorias de tortas y reposteria.','restaurante_id'=>1]);

        \App\plato::create(['categoria_id' => $categoria->id,'nombre' => 'Torta de chocolate','descripcion' => 'torta de harina de trigo recubierta en chocolate','precio' => 4500]);
    
        \App\plato::create(['categoria_id' => $categoria->id,'nombre' => 'Postre de tres leches','descripcion' => 'Postre preparado en 3 leches.','precio' => 6000]);

    }
}
