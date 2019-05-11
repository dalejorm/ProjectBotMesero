<?php

namespace App\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;

class ConversacionPedidos extends Conversation
{
    protected $id;
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
        $estado_pedidos = \App\pedido::select('estado')->distinct('estado')-> orderby('estado', 'asc')->get();
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


    public function listarPedidosPorEstado($idPedido) {
        $pedidos = \App\pedido::select('pedidos.id as idpedido', 'fecha', 'name')
                -> join ('users','users.id','=','pedidos.usuario_id')
                -> where('estado', $idPedido)
                -> orderby('fecha', 'asc')->get();
        $buttonArray = [];
        foreach ($pedidos as $pedido) {
            $button = Button::create('Nro.: '.$pedido->idpedido.' Fecha: '.$pedido->fecha.' Usuario: '.$pedido->name)->value($pedido->idpedido);
            $buttonArray[] = $button;
        }
        $buttonArray[] = Button::create('Volver')->value('Volver');
        if (count($pedidos) == 0) {
            $this->say("No tenemos pedidos en estado ".$idPedido);
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

    public function listarPedidosDetallado($idPedido) {
        $orden = "";
        $valor = 0;
        $platospedidos = $this->cargarPlatospedidos($idPedido);
        $this->say("Información del pedido:");
        foreach ($platospedidos as $plato) {
            $valor ++;
            $orden = $orden.$valor."- ".$plato->nombre."\n";                    
        }
        $this->say($orden);              
        $estado = $this->cargarestado($idPedido);
        $question = Question::create('Cambia el estado:')->addButtons([
            Button::create($estado)->value($estado),
            Button::create('Volver')->value('volver'),          
        ]);
        $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                if($answer->getValue() == 'Finalizado'){
                    $this->say("Este pedido culmino su ciclo de vida");
                    $this->ConsultarPedidos();                    
                }else if($answer->getValue()!= 'Volver'){                    
                   $this->cambiarEstado($answer->getValue(), $this->id);                   
                }else if($answer->getValue()== 'Volver'){
                    $this->ConsultarPedidos();
                }
            }
            else
            {
                $this->say("Selecciona una respuesta");
                $this->listarPedidosPorEstado($this->id);
            }
        });
    }

    public function cargarPlatospedidos($idPedido){
        $platospedidos = \App\plato::select('platos.id as idplato','nombre', 'descripcion')
                -> leftJoin ('platospedidos','platospedidos.plato_id','=','platos.id')
                -> where('pedido_id', $idPedido)
                -> orderby('platos.nombre', 'asc')->get(); 
        return $platospedidos;
    }
    
    public function cargarestado($idPedido){
        $pedidocargados = \App\pedido::select('estado')->where('id',$idPedido)->get();
        $this->id = $idPedido;
        foreach ($pedidocargados  as $pedido) {
            if($pedido->estado == 'Pendiente'){
                return 'Preparar';
            }else if ($pedido->estado == 'En preparación'){
                return 'Enviar';
            }else if ($pedido->estado == 'Enviado'){
                return 'Entregar';
            }else {
                return 'finalizado';
            }
        }
        
    }
    public function cambiarEstado($estado,$idPedido){
        if($estado == 'Preparar'){
            $this->updateDB('En preparación',$idPedido);
        }else if ($estado == 'Enviar'){
            $this->updateDB('Enviado',$idPedido);
        }else if ($estado == 'Entregar'){
            $this->updateDB('Finalizado',$idPedido);
        }
    }

    public function updateDB($estado,$idPedido){
        $actualizacion = \App\pedido::where('id', $idPedido)->update(['estado'=>$estado]);
        if($actualizacion > 0){
            $this->say("Estado actualizado correctamente");
            $this->informarCLiente($idPedido);
        }else{
            $this->say("Ups, no se pudo actualizar el estado"); 
        }
    }

    public function informarCLiente($idPedido){
        $pedidocargado = \App\pedido::select('*')->where('id',$idPedido)->get();
        foreach ($pedidocargado  as $pedido) {
            $this->say('Tu pedido '. $pedido->id.' ha cambiado de estado, ahora se encuentra: '.$pedido->estado);
        } 
        
    }
}
