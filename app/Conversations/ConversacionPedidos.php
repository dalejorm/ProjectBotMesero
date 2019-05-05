<?php

namespace App\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;

class ConversacionPedidos extends Conversation
{
    /**
     * Start the conversation.
     *
     * @return mixed
     */
    public function run()
    {
        $this->ConsultarPedidos();
    }


    public function ConsultarPedidos() {       
        $estado_pedidos = \App\pedido::orderby('estado', 'asc')->get();
        $buttonArray = [];
        foreach ($estado_pedidos as $estado) {
            $button = Button::create($estado->estado)->value($estado->estado);
            $buttonArray[] = $button;
        }
        $buttonArray[] = Button::create('Volver')->value('Volver');
        if (count($estado_pedidos) == 0) {
            $this->say("No existen pedidos registrados");
        } else {
            $question = Question::create('¿Qué tipo de pedidos desea consultar?')->addButtons($buttonArray);
            $this->ask($question, function (Answer $answer) {
                if ($answer->isInteractiveMessageReply()) {
                    if ($answer->getValue() != 'Volver') {
                        $this->listarPedidosPorEstado($answer->getValue());
                    } else {
                        //$this->mostrarmenu(); Qué pasa cuando volver
                    }
                } else {
                    $this->say("Selecciona una respuesta");
                    $this->mostrarCategorias();
                }
            });
        }
    }


    public function listarPedidosPorEstado($ans) {
        $pedidos = \App\pedido::select('pedidos.id as idpedido', 'fecha', 'name')
                -> join ('users','users.id','=','pedidos.usuario_id')
                -> where('estado', $ans)
                -> orderby('fecha', 'asc')->get();
        $buttonArray = [];
        foreach ($pedidos as $pedido) {
            $button = Button::create('Nro.: '.$pedido->idpedido.' Fecha: '.$pedido->fecha.' Usuario: '.$pedido->name)->value($pedido->idpedido);
            $buttonArray[] = $button;
        }
        $buttonArray[] = Button::create('Volver')->value('Volver');
        if (count($pedidos) == 0) {
            $this->say("No tenemos pedidos en estado ".$ans);
            $this->ConsultarPedidos();
        } else {
            $question = Question::create('Selecciona un pedido')->addButtons($buttonArray);
            $this->ask($question, function (Answer $answer) {
                if ($answer->isInteractiveMessageReply()) {
                    if ($answer->getValue() != 'Volver') {
                        $this->listarPedidosDetallado($answer->getValue());
                    }else{
                        $this->ConsultarPedidos();
                    }
                } else {
                    $this->say("Selecciona una respuesta");
                    $this->ConsultarPedidos();
                }
            });
        }
    }

    public function listarPedidosDetallado($ans) {
        $platospedidos = \App\plato::select('platos.id as idplato','nombre', 'descripcion')
                -> leftJoin ('platospedidos','platospedidos.plato_id','=','platos.id')
                -> where('pedido_id', $ans)
                -> orderby('platos.nombre', 'asc')->get();  
        $orden = "";
        $valor = 0;
        $this->say("Información del pedido:");
        foreach ($platospedidos as $plato) {
            $valor ++;
            $orden = $orden.$valor."- ".$plato->nombre."\n";
                    
        }
        $this->say($orden);   
    }
}
