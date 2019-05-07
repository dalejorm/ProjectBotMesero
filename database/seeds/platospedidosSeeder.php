<?php

use Illuminate\Database\Seeder;

class platospedidosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("ALTER TABLE platospedidos AUTO_INCREMENT = 0;");
        $platospedidos = \App\Platopedido::create(['pedido_id'=>1,'plato_id'=>2]);
        \App\Platopedido::create(['pedido_id'=>2,'plato_id'=>2]);
        \App\Platopedido::create(['pedido_id'=>2,'plato_id'=>1]);
    }
}
