<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class usuario extends Model
{
    protected $fillable = ['nombre'];

    public function pedido(){return $this->hasMany('App\pedido');}

}
