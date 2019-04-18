<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class administrador extends Model
{
    protected $fillable = ['nombre', 'password','restaurante_id'];

    public function restaurante(){return $this->belongsTo('App\restaurante');}


}
