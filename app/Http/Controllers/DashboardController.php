<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class DashboardController extends Controller
{
  public function __invoke(Request $request){
    $anio=date('Y');
    $gastos=DB::table('gastos as i')
    ->select(DB::raw('MONTH(i.fecha_hora) as mes'),
    DB::raw('YEAR(i.fecha_hora) as anio'),
    DB::raw('SUM(i.Total) as total'))
    ->whereYear('i.fecha_hora',$anio)
    ->groupBy(DB::raw('MONTH(i.fecha_hora)'),DB::raw('YEAR(i.fecha_hora)'))
    ->get();

    return ['gastos'=>$gastos,'anio'=>$anio];
    
      
  }
}
