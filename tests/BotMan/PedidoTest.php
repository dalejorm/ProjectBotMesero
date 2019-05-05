<?php

namespace Tests\BotMan;

use Tests\TestCase;

class PedidoTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    

    public function testmenuAdministrador(){
        $this->bot->receives('/administrar')
            ->assertReply('Ingrese su usuario');
    }
    public function testlogeoSuccessful(){
        $this->bot->receives('/administrar')
            ->assertReply('Ingrese su usuario')
            ->receives('Alejandro')
            ->assertReply('Ingrese su contraseña')
            ->receives('12345678')
            ->assertReply('Usuario correcto');
    }

    public function testlogeoFailUser(){
        $this->bot->receives('/administrar')
            ->assertReply('Ingrese su usuario')
            ->receives('pepe')
            ->assertReply('El usuario ingresado es incorrecto');
    }

    public function testlogeoFailPass(){
        $this->bot->receives('/administrar')
            ->assertReply('Ingrese su usuario')
            ->receives('Alejandro')
            ->assertReply('Ingrese su contraseña')
            ->receives('pepitoperez')
            ->assertReply('La contraseña es incorrecta');
    }
}
