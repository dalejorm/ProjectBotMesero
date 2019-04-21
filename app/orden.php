<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class orden extends Model
{
    public $table = "ordenes";
    protected $fillable = ['fecha_inicial', 'fecha_final','estado', 'pedido', 'pedido_id'];

    public function pedido(){return $this->belongsTo('App\pedido');}

}
