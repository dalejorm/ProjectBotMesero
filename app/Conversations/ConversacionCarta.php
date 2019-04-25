<?php

namespace App\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;
use Illuminate\Foundation\Inspiring;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;

class ConversacionCarta extends Conversation {

    protected $plato;
    protected $contador = 0;
    protected $idpedido;
    /**
     * Start the conversation.
     *
     * @return mixed
     */
    public function run() {
        $this->atender();
    }

    public function atender() {
        $this->mostrarmenu();
    }

    public function mostrarmenu() {
        $contador = 0;
        $question = Question::create('¿Quieres conocer nuestros productos?')->addButtons([
            Button::create('Sí')->value('Sí'),
            Button::create('No')->value('No'),
            Button::create('Volver')->value('Volver')
        ]);
        $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                if ($answer->getValue() == 'Sí') {
                    $this->mostrarCategorias();
                } elseif ($answer->getValue() == 'Volver') {
                    $this->say('¿Necesitas ayuda?, prueba diciendo Hola o Ayuda');
                }
            } else {
                $this->say("Selecciona una respuesta");
                $this->mostrarmenu();
            }
        });
    }

    public function mostrarCategorias() {        
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
                    if ($answer->getValue() != 'Volver') {
                        $this->mostrarPlatos($answer->getValue());
                    } else {
                        $this->mostrarmenu();
                    }
                } else {
                    $this->say("Selecciona una respuesta");
                    $this->mostrarCategorias();
                }
            });
        }
    }

    public function mostrarPlatos($ans) {
        $platos = \App\plato::orderby('nombre', 'asc')->get()->where('categoria_id', $ans);
        $buttonArray = [];
        foreach ($platos as $plato) {
            $button = Button::create($plato->nombre)->value($plato->id);
            $buttonArray[] = $button;
        }
        $buttonArray[] = Button::create('Volver')->value('Volver');
        if (count($platos) == 0) {
            $this->say("¡Ay caramba!, no tenemos productos en esta categoría, prueba con otra");
            $this->mostrarCategorias();
        } else {
            $question = Question::create('Selecciona un plato')->addButtons($buttonArray);
            $this->ask($question, function (Answer $answer) {
                if ($answer->isInteractiveMessageReply()) {
                    if ($answer->getValue() != 'Volver') {
                        $this->seleccionarPlato($answer->getValue());
                    }else{
                        $this->mostrarCategorias();
                    }
                } else {
                    $this->say("Selecciona una respuesta");
                    $this->mostrarPlatos();
                }
            });
        }
    }

    public function seleccionarPlato($ans) {        
        $this->plato = \App\plato::find($ans);

        $this->say("Plato : ".$this->plato->nombre." Descripción: ".$this->plato->descripcion." Precio: $ ".$this->plato->precio);

        $question = Question::create('Selecciona una opción')->addButtons([
            Button::create('Agregar al Pedido')->value('Agregar'),
            Button::create('Finalizar Pedido')->value('Finalizar'),
            Button::create('Volver')->value('Volver')
        ]);
            
        $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                if ($answer->getValue() == 'Agregar') {
                    $this->agregarPlatoPedido($this->plato->id,$this->plato->nombre,$this->plato->categoria_id);
                }elseif($answer->getValue() == 'Finalizar'){
                    //Metodo
                }else{
                    $this->say("Plato : ".$this->plato->categoria_id);
                    $this->mostrarPlatos($this->plato->categoria_id);
                }
            } else {
                $this->say("Selecciona una opción");
                $this->seleccionarPlato($ans);
            }
        });
        
    }

    public function agregarPlatoPedido($idplato,$nombrePlato,$idcategoria){
        $this->contador = $this->contador + 1;
        $buttonArray = [];
        if($this->contador == 1){
            \App\pedido::create ([
                'estado' => 'Pendiente',
                'fecha' => new \DateTime(),
                'usuario_id' => 1
            ]);            
            $this->idpedido = \App\pedido::max('id');                                    
        }

        \App\platopedido::create ([
            'pedido_id' => $this->idpedido,
            'plato_id' => $idplato
        ]);
        $this->say("Se ha agregado el plato: ".$nombrePlato.", su pedido es el Nro. ".$this->idpedido);
        $buttonArray[] = Button::create('Finalizar Pedido')->value('Finalizar');
        $buttonArray[] = Button::create('Agregar más Platos')->value('Volver');

        $question = Question::create('')->addButtons($buttonArray);
        $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                if ($answer->getValue() == 'Finalizar') {
                    //Finalizar Pedido
                }elseif($answer->getValue() == 'Volver'){
                    $this->mostrarPlatos($this->plato->categoria_id);
                }
            } else {
                $this->say("Selecciona una opción");
                $this->mostrarPlatos();
            }
        });
    }
}