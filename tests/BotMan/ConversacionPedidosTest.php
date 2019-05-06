<?php

namespace Tests\BotMan;

use Tests\TestCase;

class ConversacionPedidosTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCargarPedidos()
    {
        $this->bot->receives('/testpedidos')
            ->assertQuestion('¿Qué tipo de pedidos desea consultar?')
            ->receivesInteractiveMessage('En Preparación')
            ->assertQuestion('Selecciona un pedido')
            ->receivesInteractiveMessage('2')
            ->assertReply('Información del pedido:')
            ->assertReply('1- Postre de tres leches'."\n"); 
    }
}
