<?php

use Illuminate\Database\Seeder;

class categoria4Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categoria = \App\categoria::create(['nombre' => 'Platos Fuertes', 'descripcion'=> 'Categorias de platos fuertes.','restaurante_id1'=>1]);

        \App\plato::create(['categoria_id' => $categoria->id,'nombre' => 'Punta de Anca','descripcion' => 'Punta de Anca es servida con papa asada o papa rústica. Acompañadas de nuestro delicioso Chimichurri y una fresca ensalada.','precio' => 22000]);
    
        \App\plato::create(['categoria_id' => $categoria->id,'nombre' => 'Solomito','descripcion' => 'Solomito servido con papa asada o papa rústica. Acompañadas de ensalada. El solomito es un favorito entre la clientela de Hatoviejo.','precio' => 24000]);

    }
}
