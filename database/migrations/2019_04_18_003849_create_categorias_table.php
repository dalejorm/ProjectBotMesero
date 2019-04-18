<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categorias', function (Blueprint $table) {
            $table->increments('id')->primary();$table->increments('id')->primary();
            $table->string('nombre')->unique();
            $table->string('descripcion');
            $table->unsignedBigInteger('restaurante_id');
            $table->timestamps();
            $table->foreign('restaurante_id')->references('id')->on('restaurantes')>onUpdate('cascade')->onDelete('cascade');
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categorias');
    }
}
