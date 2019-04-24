<?php

namespace App\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;
use Illuminate\Foundation\Inspiring;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;

class ConversacionCategoria extends Conversation
{
   protected $nombre;
   protected $descripcion;
   protected $restaurante_id=0;

   public function __construct($resta){
    $this->restaurante_id = $resta;
   }
    /**
     * Start the conversation.
     *
     * @return mixed
     */
    public function run(){
        $this->crearCategoria();
    }

    public function crearCategoria(){ 
       $this->agregarCategoria();  
    }    

    public function agregarCategoria(){
        $question = Question::create('¿Quieres agregar una nueva Categoría?')->addButtons([
                Button::create('Sí')->value('Sí'),
                Button::create('No')->value('No')
        ]);
        $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                if($answer->getValue()== 'Sí'){
                    $this->solicitarDatos();
                }
                if($answer->getValue()== 'No'){

                }
            }
            else
            {
                $this->say("Selecciona una respuesta");
                $this->mostrarmenu();
            }
        });
    }

    public function solicitarDatos(){
        if($this->nombre == null){
            $this->solicitarNombre();
        }else if($this->descripcion == null){
                $this->solicitarDescripcion();
        }else if($this->restaurante_id > 0){
            $this->addCategoria();
            
        }else{
            $this->say("Ups, hubo un problema al cargar información desde la BD");
        }        
    }
    public function solicitarNombre(){
        $this->ask('¿Cual es el nombre de la nueva categoría?', function (Answer $answer){
            if($answer->getText() != null){
                $this->nombre = $answer-> getValue();
                $this->solicitarDatos();
            }else{
                $this->say("No ingresaste un nombre");
                $this->agregarCategoria();
            }
        });        
    }

    public function solicitarDescripcion(){
        $this->ask('¿Cual es la descripción de la nueva categoría', function (Answer $answer){
            if($answer->getText() != null){
                $this->descripcion = $answer-> getValue();
                $this->solicitarDatos();
            }else{
                $this->say("No ingresaste un nombre");
                $this->agregarCategoria();
            }
        });        
    }

    
    public function addCategoria(){
        \App\Categoria::create ([
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'restaurante_id1' => $this->restaurante_id
        ]);
        $this->say("Se ha registrado la categoría: ".$this->nombre);
    }  
    
}