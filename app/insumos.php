<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class insumos extends Model
{
    //
    protected $fillable =['nombre','unidad_de_medida','Precio_Unitario','tipo_insumo','detalle'];
}
