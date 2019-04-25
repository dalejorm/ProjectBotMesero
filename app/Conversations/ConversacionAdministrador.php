<?php

namespace App\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;
use Illuminate\Foundation\Inspiring;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use App\Conversations\ConversacionCategoria;
use App\Conversations\ConversacionPlato;

class ConversacionAdministrador extends Conversation
{
    protected $usuario;
    protected $contrasena;
    protected $validarusuario;
    protected $validarcontrasena;
    protected $bandera = 0;
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
                        $this->queHacer();
                        }
                });
            }
        });
        
    }

    public function verificarUsuario(){
//        $this->validarusuario = \App\administrador::where('nombre', '=', $this->usuario)->get();
        $this->validarusuario = \App\administrador::select ('nombre') -> where ('nombre', "=", $this->usuario)->get();
        //$this->say("El usuario ingresado es incorrecto".$this->validarusuario);

        if(count($this->validarusuario)== 0){
            $this->say("El usuario ingresado es incorrecto");
            return false;
        }
        else{
           return true; 
        }

    }

    public function verificarContrasena(){
        //$this->validarcontrasena = \App\administrador::where([ ['password', '=', $this->contrasena], ['nombre', '=', $this->usuario]       ])->get();

        $this->validarcontrasena = \App\administrador::select ('nombre') 
            -> where ('nombre', "=", $this->usuario)
            -> where ('password', "=", $this->contrasena)
            ->get();

        if(count($this->validarcontrasena)==0){
            $this->say("La contraseña es incorrecta");
            
            return false;
        }
        else{
            
           return true; 
        }

    }

    public function queHacer(){
        $question = Question::create('¿Que deseas hacer?')->addButtons([
                Button::create('Agregar Categorías')->value(1),
                Button::create('Agregar Platos')->value(2),
                Button::create('Atender pedidos')->value(3)
        ]);
        $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                if($answer->getValue()== 1){               
                    //falta mandarle el id de restaurante del administrador en vez que el numero 1 de abajo     
                    $this->bot->startConversation(new ConversacionCategoria(1));
                }
                if($answer->getValue()== 2){
                    $this->bot->startConversation(new ConversacionPlato());
                }
                if($answer->getValue()== 2){
                    //atender pedidos
                }
            }
            else
            {
                //pendiente que al terminar agregar plato o categoría o de atender un pedido se le pregunte que desea hacer
                $this->say("Selecciona una respuesta");
                $this->queHacer();
            }
        });
    }
    
    
}