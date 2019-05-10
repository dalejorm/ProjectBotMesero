<?php

namespace Tests\BotMan;

use Tests\TestCase;

class EstadoPedidoTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCambiarEstadopedido()
    {
        $this->bot->receives('/testpedidos')
            ->assertQuestion('¿Qué tipo de pedidos desea consultar?')
            ->receivesInteractiveMessage('En Preparación')
            ->assertQuestion('Selecciona un pedido')
            ->receivesInteractiveMessage('1')
            ->assertReply('Información del pedido:')
            ->assertReply('1- Postre de tres leches'."\n") 
            ->assertQuestion('Cambia el estado:')
            ->receivesInteractiveMessage('Enviar')
            ->assertReply('Estado actualizado correctamente');
    }
    public function testCambiarEstadopedido2()
    {
        $this->bot->receives('/testpedidos')
        ->assertQuestion('¿Qué tipo de pedidos desea consultar?')
        ->receivesInteractiveMessage('En Preparación')
        ->assertQuestion('Selecciona un pedido')
        ->receivesInteractiveMessage('1')
        ->assertReply('Información del pedido:')
        ->assertReply('1- Postre de tres leches'."\n") 
        ->assertQuestion('Cambia el estado:')
        ->receives('cualquiercosa')
        ->assertReply('Selecciona una respuesta');
    }

    public function testCambiarEstadopedido3()
    {
        $this->bot->receives('/testpedidos')
        ->assertQuestion('¿Qué tipo de pedidos desea consultar?')
        ->receivesInteractiveMessage('En Preparación')
        ->assertQuestion('Selecciona un pedido')
        ->receivesInteractiveMessage('1')
        ->assertReply('Información del pedido:')
        ->assertReply('1- Postre de tres leches'."\n") 
        ->assertQuestion('Cambia el estado:')
        ->receivesInteractiveMessage('Volver')
        ->assertQuestion('¿Qué tipo de pedidos desea consultar?');
    }
}
