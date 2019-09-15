<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gasto extends Model
{
    

    protected $fillable =[
        'idCuartel',
        'idCategoria',
        'nombre',
        
       
        'Total',
        'fecha-hora',
        'estado'

    ];

    public function Cuartel(){

        return $this->belongsTo('App\Cuartel');
    }

    public function Categoria(){

        return $this->belongsTo('App\Categoria');
    }
}
