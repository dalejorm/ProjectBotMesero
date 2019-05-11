<?php

namespace Tests\BotMan;

use Tests\TestCase;

class ValorPlato extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $this->bot->receives('Hi')
            ->assertReply('Hello!');
    }

    public function testeditarValor()
    {
        $this->bot->receives('/administrar')
            ->assertQuestion('Ingrese su usuario')
            ->receivesInteractiveMessage('Alejandro')
            ->assertQuestion('Ingrese su contraseña')
            ->receivesInteractiveMessage('12345678')
            ->assertReply('Usuario correcto')
            ->assertQuestion('¿Que deseas hacer?')
            ->receivesInteractiveMessage('Editar')
            ->assertReply('Estado actualizado correctamente');
    }

}
