<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class pedido extends Model
{
    protected $fillable = ['estado', 'fecha','usuario_id'];
}
