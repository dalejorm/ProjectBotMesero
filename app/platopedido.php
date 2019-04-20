<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class platopedido extends Model
{
    public $table = "platospedidos";
    protected $fillable = ['pedido_id', 'plato_id'];

    public function plato(){return $this->belongsTo('App\plato');}

    public function pedido(){return $this->belongsTo('App\pedido');}

}
