<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class categoria extends Model
{
    protected $fillable = ['nombre', 'descripcion','restaurante_id'];

    public function restaurante(){return $this->belongsTo('App\restaurante');}

    public function plato(){return $this->hasMany('App\plato');}

}
