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
   protected $idplato;
   protected $valor;
  
    /**
     * Start the conversation.
     *
     * @return mixed
     */
    public function run(){
        $this->administrarPlatos();
    }

    public function administrarPlatos(){
        $question = Question::create('¿Qué acción deseas realizar?')->addButtons([
                Button::create('Agregar Plato')->value('Agregar'),
                Button::create('Editar Plato')->value('Editar'),
                Button::create('Eliminar Plato')->value('Eliminar')
        ]);
        $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                if($answer->getValue()== 'Agregar'){
                    $this->agregarPlato();
                }elseif($answer->getValue()== 'Editar'){
                    $this->listarCategoriasEditarPlatos();
                }elseif($answer->getValue()== 'Eliminar'){
                    $this->listarCategoriasEliminarPlatos();
                }
            }
            else
            {
                $this->say("Selecciona una respuesta");
                $this->mostrarmenu();
            }
        });
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
    
    public function listarCategoriasEliminarPlatos(){
        $categorias = \App\categoria::orderby('nombre', 'asc')->get();
        $buttonArray = [];
        foreach ($categorias as $categoria) {
            $button = Button::create($categoria->nombre)->value($categoria->id);
            $buttonArray[] = $button;
        }
        $buttonArray[] = Button::create('Volver')->value('Volver');

        $question = Question::create('Seleccione la categoría de la cual desea eliminar platos')->addButtons($buttonArray);
        $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                if($answer->getValue()!= 'Volver'){
                    $this->categoria_id = $answer-> getValue();
                    $this->listarPlatosEliminar($this->categoria_id);
                }else{
                    $this->administrarPlatos();
                }
            }
            else
            {
                $this->listarCategoriasEliminarPlatos();
            }
        });
    }

    public function listarPlatosEliminar($ans){
        $platos = \App\plato::select('id', 'nombre')
                            -> where('categoria_id', $ans)
                            -> orderby('nombre', 'asc')->get();

        $buttonArray = [];
        foreach ($platos as $plato) {
            $button = Button::create($plato->nombre)->value($plato->id);
            $buttonArray[] = $button;
        }
        $buttonArray[] = Button::create('Volver')->value('Volver');

        $question = Question::create('Selecciona el plato que deseas eliminar del pedido')->addButtons($buttonArray);
        $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                if ($answer->getValue() != 'Volver') {
                    $question2 = Question::create('¿Está seguro de que desea eliminar el plato seleccionado?')->addButtons([
                        Button::create('Sí')->value('Sí'),
                        Button::create('No')->value('No')
                    ]);
                    $this->idplato = $answer->getValue();
                    $this->ask($question2, function (Answer $answer2) {
                        if ($answer2->isInteractiveMessageReply()) {
                            if ($answer2->getValue() == 'Sí') {
                                $eliminarplato = \App\plato::find($this->idplato)->delete();
                                $this->say("Se ha eliminado el plato de la categoría");
                                $this->listarPlatosEliminar($this->categoria_id);
                            } elseif ($answer2->getValue() == 'No') {
                                $this->listarPlatosEliminar($this->categoria_id);
                            }
                        } else {
                            $this->say("Selecciona una respuesta");
                            $this->listarPlatosEliminar($this->categoria_id);
                        }
                    });
                }else{
                    $this->listarCategoriasEliminarPlatos();
                }
            } else {
                $this->say("Selecciona una respuesta");
                $this->listarPlatosEliminar($this->categoria_id);
            }
        });
    }


    public function listarCategoriasEditarPlatos(){
        $categorias = \App\categoria::orderby('nombre', 'asc')->get();
        $buttonArray = [];
        foreach ($categorias as $categoria) {
            $button = Button::create($categoria->nombre)->value($categoria->id);
            $buttonArray[] = $button;
        }
        $buttonArray[] = Button::create('Volver')->value('Volver');

        $question = Question::create('Seleccione la categoría de la cual desea editar platos')->addButtons($buttonArray);
        $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                if($answer->getValue()!= 'Volver'){
                    $this->categoria_id = $answer-> getValue();
                    $this->listarPlatosEditar($this->categoria_id);
                }else{
                    $this->administrarPlatos();
                }
            }
            else
            {
                $this->listarCategoriasEditarPlatos();
            }
        });
    }    

    public function listarPlatosEditar($ans){
        $platos = \App\plato::select('id', 'nombre')
                            -> where('categoria_id', $ans)
                            -> orderby('nombre', 'asc')->get();

        $buttonArray = [];
        foreach ($platos as $plato) {
            $button = Button::create($plato->nombre)->value($plato->id);
            $buttonArray[] = $button;
        }
        $buttonArray[] = Button::create('Volver')->value('Volver');

        $question = Question::create('Selecciona el plato que deseas editar')->addButtons($buttonArray);
        $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                if ($answer->getValue() != 'Volver') {
                    $this->idplato = $answer->getValue();
                    $this->ask("Ingrese el valor del plato",function(Answer $response){
                        
                        $this->valor = $response->getText();
                        $this->editarValor($this->idplato,$this->valor);
                    });   
                
                }else{
                    $this->listarCategoriasEditarPlatos();
                }
            } else {
                $this->say("Selecciona una respuesta");
                $this->listarPlatosEditar($this->categoria_id);
            }
        });
    }

    public function editarValor($ans,$valor){       
       $plato = \App\plato::find($ans);
       $plato->precio = $valor;
       $plato->save(); 
        $this->say("El valor del plato ha cambiado");
           
    
    }
}