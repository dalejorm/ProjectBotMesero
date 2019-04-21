<?php
use App\Http\Controllers\BotManController;
use App\Conversations\ConversacionInicial;
use App\Conversations\ConversacionCarta;
$botman = resolve('botman');

$botman->hears('Hola|Ayuda', function($bot){
    $bot->startConversation(new ConversacionInicial());
})->stopsConversation();

$botman->hears('/carta', function($bot){
    $bot->startConversation(new ConversacionCarta());
})->stopsConversation();


$botman->fallback(function ($bot) {
    $bot->reply('¿necesitas ayuda?, prueba diciendo Hola o ayuda');
});



