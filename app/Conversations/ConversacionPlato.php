<?php

namespace App\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;
use Illuminate\Foundation\Inspiring;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;

class ConversacionPlato extends Conversation
{
   protected $nombre;
   protected $descripcion;
   protected $precio=0;
   protected $categoria_id=0;
  
    /**
     * Start the conversation.
     *
     * @return mixed
     */
    public function run(){
        $this->crearPlato();
    }

    public function crearPlato(){ 
       $this->agregarPlato();  
    }    

    public function agregarPlato(){
        $question = Question::create('¿Quieres agregar un nuevo Plato?')->addButtons([
                Button::create('Sí')->value('Sí'),
                Button::create('No')->value('No')
        ]);
        $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                if($answer->getValue()== 'Sí'){
                    $this->seleccionarCategoria();
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
    public function seleccionarCategoria(){       
        $categorias = \App\categoria::orderby('nombre', 'asc')->get();
        $buttonArray = [];
        foreach ($categorias as $categoria) {
            $button = Button::create($categoria->nombre)->value($categoria->id);
            $buttonArray[] = $button;
        }
        $buttonArray[] = Button::create('Volver')->value('Volver');
        if (count($categorias) == 0) {
            $this->say("¡Ay caramba!, no tenemos categorías");
        } else {
            $question = Question::create('¿Selecciona una categoría?')->addButtons($buttonArray);
            $this->ask($question, function (Answer $answer) {
                if ($answer->isInteractiveMessageReply()) {
                    $this->categoria_id = $answer-> getValue();
                    $this->solicitarDatos();                   
                } else {
                    $this->say("Selecciona una respuesta");
                    $this->seleccionarCategoria();
                }
            });           
        }
    }
        

    public function solicitarDatos(){
        if($this->nombre == null){
            $this->solicitarNombre();
        }else if($this->descripcion == null){
                $this->solicitarDescripcion();
        }else if($this->precio == 0){
            $this->SolicitarPrecio();            
        }       
    }
    public function solicitarNombre(){
        $this->ask('¿Cual es el nombre del nuevo Plato?', function (Answer $answer){
            if($answer->getText() != null){
                $this->nombre = $answer-> getValue();
                $this->solicitarDatos();
            }else{
                $this->say("No ingresaste un nombre");
                $this->solicitarDatos();
            }
        });        
    }

    public function solicitarDescripcion(){
        $this->ask('¿Cual es la descripción del nuevo Plato?', function (Answer $answer){
            if($answer->getText() != null){
                $this->descripcion = $answer-> getValue();
                $this->solicitarDatos();
            }else{
                $this->say("No ingresaste una descripcion");
                $this->solicitarDatos();
            }
        });        
    }
    
    public function solicitarPrecio(){
        $this->ask('¿Cual es el precio del nuevo Plato?', function (Answer $answer){
            if($answer->getText() != null){
                $this->precio = $answer-> getValue();
                $this->addCategoria();
            }else{
                $this->say("No ingresaste un Precio");
                $this->solicitarDatos();
            }
        });        
    }

    
    public function addCategoria(){
        \App\Plato::create ([
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'precio' => $this->precio,
            'categoria_id' => $this->categoria_id
        ]);
        $this->say("Se ha registrado el plato: ".$this->nombre);
    }  
    
}