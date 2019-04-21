<?php
use App\Http\Controllers\BotManController;
use App\Conversations\ConversacionInicial;
use App\Conversations\ConversacionCarta;
use App\Conversations\ConversacionAdministrador;

$botman = resolve('botman');

/////Cliente - usuario
$botman->hears('Hola|Ayuda', function($bot){
    $bot->startConversation(new ConversacionInicial());
})->stopsConversation();

$botman->hears('/carta', function($bot){
    $bot->startConversation(new ConversacionCarta());
})->stopsConversation();


$botman->fallback(function ($bot) {
    $bot->reply('¿necesitas ayuda?, prueba diciendo Hola o ayuda');
});

////Administrador
$botman->hears('/administrar', function($bot){
    $bot->startConversation(new ConversacionAdministrador());
})->stopsConversation();





