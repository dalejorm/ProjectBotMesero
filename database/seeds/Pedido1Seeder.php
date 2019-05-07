<?php

use Illuminate\Database\Seeder;

class Pedido1Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("ALTER TABLE pedidos AUTO_INCREMENT = 0;");
        $pedido = \App\Pedido::create (['estado' => 'En preparación', 'fecha'=> new \DateTime(),'usuario_id'=>'1','direccion'=>'calle siempre viva']);
        
        \App\Pedido::create (['estado' => 'En preparación', 'fecha'=>new \DateTime(),'usuario_id'=>'1','direccion'=>'calle 2a']);
    }
}
