<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlatospedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('platospedidos', function (Blueprint $table) {
            $table->unsignedBigInteger('pedido_id');
            $table->foreign('pedido_id')->references('id')->on('pedidos');
            $table->unsignedBigInteger('plato_id');
            $table->foreign('plato_id')->references('id')->on('platos')>onUpdate('cascade')->onDelete('cascade');
            $table->string('solicitud');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('platospedidos');
    }
}
