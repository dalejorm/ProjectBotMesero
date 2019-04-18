<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class categoria extends Model
{
    protected $fillable = ['nombre', 'descripcion','restaurante_id'];

}
