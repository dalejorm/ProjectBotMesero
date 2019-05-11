<?php

use Illuminate\Database\Seeder;

class categoria5Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categoria = \App\categoria::create(['nombre' => 'Ensaladas', 'descripcion'=> 'Categorias de ensaladas.','restaurante_id1'=>1]);

        \App\plato::create(['categoria_id' => $categoria->id,'nombre' => 'Ensalada dulce','descripcion' => 'Ensalda de lechuga con piña dulce y masmelos calientes','precio' => 22000]);
    

    }
}
