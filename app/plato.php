<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class plato extends Model
{
    protected $fillable = ['nombre', 'descripcion','precio','categoria_id'];

    public function categoria(){return $this->belongsTo('App\categoria');}

    public function platopedido(){return $this->hasMany('App\platopedido');}

}
