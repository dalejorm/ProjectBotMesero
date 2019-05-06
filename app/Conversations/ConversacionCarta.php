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
    protected $usuario;
    protected $idusuario;
    protected $contrasena;
    protected $validarusuario;
    protected $validarcontrasena;
    protected $bandera = 0;
    protected $pedido;
    protected $direccion;
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
            $this->say("No tenemos categorías registradas");
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
        $buttonArray[] = Button::create('Eliminar Platos del Pedido')->value('Eliminar');
        if (count($platos) == 0) {
            $this->say("No tenemos platos registrados en esta categoría, intenta con otra");
            $this->mostrarCategorias();
        } else {
            $question = Question::create('Selecciona un plato')->addButtons($buttonArray);
            $this->ask($question, function (Answer $answer) {
                if ($answer->isInteractiveMessageReply()) {                       
                    if($answer->getValue() == 'Eliminar'){
                        $this->listarPlatosAEliminar($this->idpedido);
                    }elseif($answer->getValue() == 'Volver'){
                        $this->mostrarCategorias();
                    }else{
                        $this->seleccionarPlato($answer->getValue());
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
            Button::create('Eliminar Platos del Pedido')->value('Eliminar'),            
            Button::create('Volver')->value('Volver')
        ]);
            
        $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                if ($answer->getValue() == 'Agregar') {
                    $this->agregarPlatoPedido($this->plato->id,$this->plato->nombre,$this->plato->categoria_id);
                }elseif($answer->getValue() == 'Finalizar'){
                    $this->finalizarPedido($this->idpedido);                    
                }elseif($answer->getValue() == 'Eliminar'){
                    $this->listarPlatosAEliminar($this->idpedido);
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
        $buttonArray[] = Button::create('Eliminar Platos del Pedido')->value('Eliminar');

        $question = Question::create('Seleccione una opción')->addButtons($buttonArray);
        $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                if ($answer->getValue() == 'Finalizar') {
                    $this->finalizarPedido($this->idpedido);
                }elseif($answer->getValue() == 'Eliminar'){
                    $this->listarPlatosAEliminar($this->idpedido);
                }elseif($answer->getValue() == 'Volver'){
                    $this->mostrarPlatos($this->plato->categoria_id);
                }
            } else {
                $this->say("Selecciona una opción");
                $this->mostrarPlatos();
            }
        });
    }

    public function finalizarPedido($id){
        if($this->contador > 0){
            $this->ask("Ingrese su usuario",function(Answer $response){
                $this->usuario = $response->getText();                
                if($this->verificarUsuario() == true){
                    $this->ask("Ingrese su contraseña",function(Answer $response){
                        $this->contrasena = $response->getText();           
                        if($this->verificarContrasena() > 0){
                            $this->say("Usuario correcto");
                            $this->bandera = 1;                            
                            $this->ask("Ingrese la dirección para el pedido",function(Answer $response){
                                if($response->getText() != ''){
                                    $this->direccion = $response->getText();
                                    $this->pedido = \App\pedido::findOrFail($this->idpedido);
                                    $this->pedido->estado = 'En Preparación';
                                    $this->pedido->usuario_id = $this->verificarContrasena();
                                    $this->pedido->direccion = $this->direccion;
                                    $this->pedido->save();
                                    $this->say("Señor(a) ".$this->usuario." su pedido nro. ".$this->idpedido." se ha finalizado, ya se ha enviado la información de su pedido al restaurante y se encuentra en preparación, no puede realizar más modificaciones.");
                                    $this->say("Los platos agregados a su pedido son: ");
                                    $platospedidos = \App\plato::select ('nombre','precio') 
                                            -> join ('platospedidos','platospedidos.plato_id','=','platos.id')
                                            -> where ('platospedidos.pedido_id', "=", $this->idpedido)
                                            ->get();

                                    foreach ($platospedidos as $platopedido) {
                                        $this->say("".$platopedido->nombre." Precio: ".$platopedido->precio);
                                    }
                                    $this->mostrarmenu();
                                }else{
                                    $this->say("Debe ingresar una dirección");
                                }                          
                            });                            
                        }else{
                            $this->finalizarPedido($this->idpedido);        
                        }
                    });
                }else{
                    $this->finalizarPedido($this->idpedido);
                }
            });
        }else{
            $this->say("No ha agregado platos al pedido");
            $this->mostrarPlatos($this->plato->categoria_id);
        }
         
    }

    public function verificarUsuario(){
        $this->validarusuario = \App\User::select ('name') -> where ('name', "=", $this->usuario)->get();
        if(count($this->validarusuario)== 0){
            $this->say("El usuario ingresado es incorrecto. Inténtelo nuevamente");
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

    public function listarPlatosAEliminar($idpedido){
        $platospedidos = \App\pedido::select('platos.nombre as nombre', 'platospedidos.id as idplatopedido')
                -> join ('platospedidos','platospedidos.pedido_id','=','pedidos.id')
                -> join ('platos','platos.id','=','platospedidos.plato_id')
                -> where('pedidos.id', $idpedido)
                -> orderby('nombre', 'asc')->get();

        $buttonArray = [];
        foreach ($platospedidos as $platopedido) {
            $button = Button::create($platopedido->nombre)->value($platopedido->idplatopedido);
            $buttonArray[] = $button;
        }
        $buttonArray[] = Button::create('Volver')->value('Volver');
        if (count($platospedidos) == 0) {
            $this->say("El pedido aún no tiene platos agregados ".$ans);
            $this->mostrarCategorias();
        } else {
            $question = Question::create('Selecciona el plato que deseas eliminar del pedido')->addButtons($buttonArray);
            $this->ask($question, function (Answer $answer) {
                if ($answer->isInteractiveMessageReply()) {
                    if ($answer->getValue() != 'Volver') {
                        $question2 = Question::create('¿Está seguro de que desea eliminar del pedido el plato seleccionado?')->addButtons([
                            Button::create('Sí')->value('Sí'),
                            Button::create('No')->value('No')
                        ]);
                       $this->ask($question2, function (Answer $answer2) {
                            if ($answer2->isInteractiveMessageReply()) {
                                if ($answer2->getValue() == 'Sí') {
                                    $eliminarplato=App/platopedido::find($answer->getValue())->delete();
                                    $this->say("Se ha eliminado el plato del pedido");
                                } elseif ($answer2->getValue() == 'No') {
                                    $this->listarPlatosAEliminar($this->idpedido);
                                }
                            } else {
                                $this->say("Selecciona una respuesta");
                                $this->listarPlatosAEliminar($this->idpedido);
                            }
                        });
                    }else{
                        $this->mostrarCategorias();
                    }
                } else {
                    $this->say("Selecciona una respuesta");
                    $this->mostrarCategorias();
                }
            });
        }

    }
    
}