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
        $ayuda = ['/carta' => 'Comando para visualizar las categorías del restaurante',
        '/consultar' => 'Comando para que el usuario consulte el estado de sus pedidos',
        '/cancelar' => 'Comando para que el usuario cancele los pedidos en estado Pendiente',
        '/administrar' => 'Comando para que el usuario administrador gestione el menú del restaurante y consulte pedidos realizados por los usuarios',
        '/testpedidos' => 'Comando para consultar los pedidos registrados por estados',
        'hola' => 'Comando de ayuda para visualizar los comandos de la conversación',
        'ayuda' => 'Comando de ayuda para visualizar los comandos de la conversación'];
        $this->say("Los comandos disponibles son:");
        foreach($ayuda as $key=>$value){
            $this->say($key . ": " . $value);
        }
    }
}