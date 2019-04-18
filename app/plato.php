<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class plato extends Model
{
    protected $fillable = ['nombre', 'descripcion','precio','categoria_id'];
}
