<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class detalleGasto extends Model
{
    protected $table = 'detalle_gastos';
    protected $fillable = [

        'idGasto',
        'idInsumo',
        'cantidad',
        'costo'
    ];
    public $timestamps= false;
}
