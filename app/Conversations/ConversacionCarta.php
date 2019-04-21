<?php

namespace App\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;
use Illuminate\Foundation\Inspiring;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;

class ConversacionCarta extends Conversation
{
   
    /**
     * Start the conversation.
     *
     * @return mixed
     */
    public function run()
    {
        $this->atender();

    }

    public function atender(){ 
       $this->mostrarmenu();  
    }    

    public function mostrarmenu(){
        $question = Question::create('¿Quieres conocer nuestros productos?')->addButtons([
                Button::create('Sí')->value('Sí'),
                Button::create('No')->value('No')
        ]);
        $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                if($answer->getValue()== 'Sí'){
                    $this->mostrarCategorias();
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
    public function mostrarCategorias(){
        $categorias = \App\categoria::orderby('nombre', 'asc')->get();       
        $buttonArray = []; 
        foreach($categorias as $categoria){
            $button = Button::create($categoria->nombre.": ".$categoria->descripcion)->value($categoria->id);
            $buttonArray[] = $button;
        }
        if(count($categorias) == 0){
            $this->say("¡Ay caramba!, no tenemos categorías");
        }else{
            $question = Question::create('¿Selecciona una categoría?')->addButtons($buttonArray);
                $this->ask($question, function (Answer $answer) {
                if ($answer->isInteractiveMessageReply()) {
                $this->mostrarPlatos($answer->getValue());
                }
                else
                {
                    $this->say("Selecciona una respuesta");
                    $this->mostrarCategorias();
                }
            }); 
        }        
    }  
    
    public function mostrarPlatos($ans){
        $platos = \App\plato::orderby('nombre', 'asc')->get()->where('categoria_id',$ans);       
        $buttonArray = []; 
        foreach($platos as $plato){
            $button = Button::create($plato->nombre.": ".$plato->descripcion)->value($plato->nombre);
            $buttonArray[] = $button;
        }
        if(count($platos) == 0){
            $this->say("¡Ay caramba!, no tenemos productos en esta categoría, prueba con otra");
            $this->mostrarCategorias();
        }else {
            $question = Question::create('Selecciona un plato')->addButtons($buttonArray);
                $this->ask($question, function (Answer $answer) {
                if ($answer->isInteractiveMessageReply()) {
                //$this->mostrarPlatos($answer.getText());
                }
                else
                {
                    $this->say("Selecciona una respuesta");
                    $this->mostrarPlatos();
                }
            }); 
        }
        
    }
}