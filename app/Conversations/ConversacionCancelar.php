<?php

namespace App\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;
use Illuminate\Foundation\Inspiring;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use App\Conversations\ConversacionCategoria;
use App\Conversations\ConversacionPlato;

class ConversacionCancelar extends Conversation
{
    protected $usuario;
    protected $idpedido;
    protected $idusuario;
    protected $contrasena;
    protected $validarusuario;
    protected $validarcontrasena;
    
    /**
     * Start the conversation.
     *
     * @return mixed
     */
    public function run()
    {
        $this->preguntarusuario();
    }

    public function preguntarusuario(){
        $this->ask("Ingrese su usuario",function(Answer $response){
            $this->usuario = $response->getText();
            
            if($this->verificarUsuario() == true){
                $this->ask("Ingrese su contraseña",function(Answer $response){
                    $this->contrasena = $response->getText();
                 
                    if($this->verificarContrasena() == true){
                        $this->say("Usuario correcto");
                        $this->bandera = 1;
                        $this->mostrarPedidos();
                        
                        }
                });
            }
        });
        
    }

    public function verificarUsuario(){
        $this->validarusuario = \App\User::select ('name') -> where ('name', "=", $this->usuario)->get();

        if(count($this->validarusuario)== 0){
            $this->say("El usuario ingresado es incorrecto");
            return false;
        }
        else{
           return true; 
        }
    }

    public function verificarContrasena(){

        $users = \App\User::select ('id') 
            -> where ('name', "=", $this->usuario)
            -> where ('password', "=", $this->contrasena)
            ->get();

        foreach ($users as $user) {
            $this->idusuario = $user->id;
        }      

        if(count($users)==0){
            $this->say("La contraseña es incorrecta");            
            return 0;
        }
        else{            
           return $this->idusuario; 
        }

    }


    public function mostrarPedidos(){
        $pedidos = \App\pedido::select ('id','fecha') 
                                            -> where ('pedidos.usuario_id', "=", $this->idusuario)
                                            -> where ('pedidos.estado', "=", 'Pendiente')
                                            ->get();

        if(count($pedidos)== 0){
            $this->say("No tienes pedidos en estado Pendiente");
            return false;
        }
        else{
            $this->say("Sus pedidos en estado pendiente son:");            
            $buttonArray = [];
            foreach ($pedidos as $pedidos) {
                
                $button = Button::create("".$pedidos->id." del día ".$pedidos->fecha)->value($pedidos->id);
                $buttonArray[] = $button;
            }
            $buttonArray[] = Button::create('Volver')->value('Volver');
        
            $question = Question::create('Selecciona un pedido')->addButtons($buttonArray);
            $this->ask($question, function (Answer $answer) {
                if ($answer->isInteractiveMessageReply()) { 
                    if ($answer->getValue() != 'Volver') {
                        mostrarPedidos();
                    }   
                    else{                   
                        $this->mostrarPlatosPedido($answer->getValue());
                    }
                } else {
                    $this->say("Selecciona una respuesta");
                    
                }
             });
            }


    }
    public function mostrarPlatosPedido($ans){
        $this->idpedido = $ans;
        
        $platospedidos = \App\plato::select ('nombre') 
                                            -> join ('platospedidos','platospedidos.plato_id','=','platos.id')
                                            -> where ('platospedidos.pedido_id', "=", $ans)
                                            ->get();
                    
        
                
        $this->say("Los platos de su pedido son:");
        foreach ($platospedidos as $platopedido) {
            $this->say("".$platopedido->nombre);
        }

        $buttonArray[] = Button::create('Si')->value('si');
        $buttonArray[] = Button::create('No')->value('no');
        $question = Question::create('¿Quieres cancelar el pedido?')->addButtons($buttonArray);
        $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                if($answer->getValue() == 'si'){
                    $this->cancelarPedido();
                }
            } else {
                $this->say("Selecciona una respuesta");
                
            }
        });
        

    }


    public function cancelarPedido(){
        $pedido = \App\pedido::find($this->idpedido);

        $pedido->estado = 'Cancelado';
        $pedido->save(); 
        

       
        $this->say("El pedido se ha cancelado");
    }
}
