<?php
use App\Http\Controllers\BotManController;
use App\Conversations\ConversacionInicial;
use App\Conversations\ConversacionCarta;
use App\Conversations\ConversacionAdministrador;
use App\Conversations\ConversacionPedidos;
use App\Conversations\ConversacionCancelar;

$botman = resolve('botman');

/////Cliente - usuario
$botman->hears('Hola|Ayuda', function($bot){
    $bot->startConversation(new ConversacionInicial());
})->stopsConversation();

$botman->hears('/carta', function($bot){
    $bot->startConversation(new ConversacionCarta());
})->stopsConversation();


$botman->fallback(function ($bot) {
    $bot->reply('¿Necesitas ayuda?, prueba diciendo Hola o Ayuda');
});

////Administrador
$botman->hears('/administrar', function($bot){
    $bot->startConversation(new ConversacionAdministrador());
})->stopsConversation();

$botman->hears('/testpedidos', function($bot){
    $bot->startConversation(new ConversacionPedidos());
})->stopsConversation();

$botman->hears('/cancelar', function($bot){
    $bot->startConversation(new ConversacionCancelar());
})->stopsConversation();




