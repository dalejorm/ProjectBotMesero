<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class pedido extends Model
{
    protected $fillable = ['estado', 'fecha','usuario_id'];

    public function platopedido(){return $this->hasMany('App\platopedido');}

    public function usuario(){return $this->belongsTo('App\usuario');}

    public function orden(){return $this->belongsTo('App\orden');}

}
