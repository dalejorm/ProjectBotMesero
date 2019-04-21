<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class restaurante extends Model
{   
    public $table = "restaurantes";
    protected $fillable = ['name', 'direccion'];

    public function administrador(){return $this->belongsTo('App\administrador');}

    public function categoria(){return $this->hasMany('App\categoria');}

}
