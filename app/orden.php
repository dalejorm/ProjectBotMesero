<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class orden extends Model
{
    protected $fillable = ['fecha_inicial', 'fecha_final','estado', 'pedido', 'pedido_id'];
}
