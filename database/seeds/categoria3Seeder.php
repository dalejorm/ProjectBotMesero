<?php

use Illuminate\Database\Seeder;

class categoria3Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categoria = \App\categoria::create(['nombre' => 'Entradas', 'descripcion'=> 'Categorias de entradas.','restaurante_id1'=>1]);

        \App\plato::create(['categoria_id' => $categoria->id,'nombre' => 'Súper Nachos','descripcion' => 'Una crujiente cama de tortilla frita cubierta con frijolitos molidos, chorizo criollo y un top de queso moralique derretido, acomnpañado con pico de gallo y sabrosa crema chontaleña.','precio' => 11000]);
    
        \App\plato::create(['categoria_id' => $categoria->id,'nombre' => 'Quesadillas de res, pollo o cerdo','descripcion' => 'Sabrosas tortilla de harina rellenas de carne según su elección, filete de res, pollo o cerdo con quesillo deretido, mezclado con cebollas, chiltomas salteadas en aceite de oliva, acompañados de pico de gallo y crema chontaleña.','precio' => 12000]);

    }
}
