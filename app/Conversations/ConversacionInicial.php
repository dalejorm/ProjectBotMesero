<?php

namespace App\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;
use Illuminate\Foundation\Inspiring;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;

class ConversacionInicial extends Conversation
{
   
    /**
     * Start the conversation.
     *
     * @return mixed
     */
    public function run()
    {
        $this->ayudar();

    }

    public function ayudar(){
       $this->say('Hola Bienvenido al restaurante, comandos utiles: ');  
       $this->mostrarayuda();  
    }

    public function mostrarayuda(){
        $ayuda = ['/carta' => 'Revisa categoría de productos',
        '/historial' => 'Ver historial de pedidos',
        '/pedido' => 'Revisa tu pedido',
        '/orden' => 'Sigue tu solicutud'];
        $this->say("Los comandos disponibles son:");
        foreach($ayuda as $key=>$value){
            $this->say($key . ": " . $value);
        }
    }
}