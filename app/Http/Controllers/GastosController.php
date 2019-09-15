<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Gasto;
use App\Cuartel;
use App\CategoriaInsumo;
use Carbon\Carbon;
use App\detalleGasto;
class GastosController extends Controller
{
    
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
 
        $buscar = $request->buscar;
        $criterio = $request->criterio;
         
        if ($buscar==''){
            $gastos = Gasto::join('cuartels','gastos.idCuartel','=','cuartels.id')
            ->join('categorias','gastos.idCategoria','=','categorias.id')
            ->select('gastos.id','gastos.nombreG','cuartels.nombreCuartel','categorias.nombreCategoria'
            ,'gastos.Total','gastos.fecha_hora','gastos.estado')
            ->orderBy('gastos.fecha_hora', 'desc')->paginate(6);
        }
        else{
            $gastos = Gasto::join('cuartels','gastos.idCuartel','=','cuartels.id')
            ->join('categorias','gastos.idCategoria','=','categorias.id')
            ->select('gastos.id','gastos.nombreG','cuartels.nombreCuartel','categorias.nombreCategoria'
            ,'gastos.Total','gastos.fecha_hora','gastos.estado')
            ->where('gastos.'.$criterio, 'like', '%'. $buscar . '%')->orderBy('gastos.id', 'desc')->paginate(6);
        }
         
 
        return [
            'pagination' => [
                'total'        => $gastos->total(),
                'current_page' => $gastos->currentPage(),
                'per_page'     => $gastos->perPage(),
                'last_page'    => $gastos->lastPage(),
                'from'         => $gastos->firstItem(),
                'to'           => $gastos->lastItem(),
            ],
            'gastos' => $gastos
        ];
    }
 
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
       try{

          DB::beginTransaction();

          $gasto = new Gasto();
          $gasto->idCuartel = $request->cuartel;
          $gasto->idCategoria = $request->categoria;
          $gasto->nombreG= $request->nombre;
          
          
          $gasto->Total=$request->total;
          $gasto->fecha_hora=$request->fecha;
          $gasto->estado='creado';
          $gasto->save();

          $detalles = $request->data;  // array de insumos
          
          foreach($detalles as $ep=>$det){
            $detalle = new detalleGasto();
            $detalle->idGasto = $gasto->id;
            $detalle->idInsumo= $det['idInsumo'];
            $detalle->cantidad= $det['Cantidad'];
            $detalle->costo =$det['precio']*$det['Cantidad'];
            $detalle->save();
          }



      DB::commit();
       } catch(Exception $e){
           DB::rollBack();
       }




    }
   
 
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function obtenerCabecera(Request $request){
       
        if (!$request->ajax()) return redirect('/');
 
        $id = $request->id;
       
         
       
        $gasto = Gasto::join('cuartels','gastos.idCuartel','=','cuartels.id')
        ->join('categorias','gastos.idCategoria','=','categorias.id')
        ->select('gastos.id','gastos.nombreG','cuartels.nombreCuartel','categorias.nombreCategoria'
        ,'gastos.Total','gastos.fecha_hora','gastos.estado')
        ->where('gastos.id','=',$id)
        ->orderBy('gastos.id', 'desc')->take(1)->get();
        
        
         
 
        return[
                 
            'gasto' => $gasto
        ];

    }
    
    public function obtenerDetalles(Request $request){
        
        if (!$request->ajax()) return redirect('/');
 
        $id = $request->id;
       
         
       
        $detalles = detalleGasto::join('insumos','detalle_gastos.idInsumo','=','insumos.id')
        ->select('detalle_gastos.cantidad as Cantidad','detalle_gastos.costo as precio','insumos.nombre as insumo','insumos.unidad_de_medida as unidad')
        ->where('detalle_gastos.idGasto','=',$id)
        ->orderBy('detalle_gastos.id', 'desc')->get();
        
        
         
 
        return [
                 
            'detalles' => $detalles
        ];

    }

    
    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $Gasto = Gasto::findOrFail($request->id);
        $Gasto->delete();
    }

    public function gastoHistoricoPDF(){
        $time_start = microtime(true);

        $gastos = Gasto::join('cuartels','gastos.idCuartel','=','cuartels.id')
        ->join('categorias','gastos.idCategoria','=','categorias.id')
        ->select('gastos.id','gastos.nombreG','cuartels.nombreCuartel','categorias.nombreCategoria'
        ,'gastos.Total','gastos.fecha_hora','gastos.estado')
        ->orderBy('gastos.fecha_hora', 'desc')->get();

        $cont=Gasto::count();
        $total=Gasto::select('Total')->sum('Total');
        $time_end = microtime(true);

        $time_total = $time_end - $time_start;
       $pdf=\PDF::loadView('pdf.gastoHistoricoPDF',['gastos'=>$gastos,'cont'=>$cont,'total'=>$total,'time'=>$time_total]);

       return $pdf->download('GastoHistorico.pdf');
       
    


    }
    public function gastoTemporadaPDF(Request $request){
        $inicio=$request->fecha_hora_inicio;
        $termino=$request->fecha_hora_termino;
        $gastos = Gasto::join('cuartels','gastos.idCuartel','=','cuartels.id')
        ->join('categorias','gastos.idCategoria','=','categorias.id')
        ->select('gastos.id','gastos.nombreG','cuartels.nombreCuartel','categorias.nombreCategoria'
        ,'gastos.Total','gastos.fecha_hora','gastos.estado')
        ->whereBetween('gastos.fecha_hora',[$inicio,$termino])
        ->orderBy('gastos.fecha_hora', 'desc')->get();

        $cont=Gasto::whereBetween('fecha_hora',[$inicio,$termino])->count();
        $total=Gasto::select('Total')
        ->whereBetween('fecha_hora',[$inicio,$termino])
        ->sum('Total');
          
       $pdf2=\PDF::loadView('pdf.GastoTemporada',['gastos'=>$gastos,'cont'=>$cont,'total'=>$total,'inicio'=>$inicio,'termino'=>$termino]);
      
       return $pdf2->download('GastoTemporada.pdf');
    }

    public function inicio_temporada(Request $request){

        if (!$request->ajax()) return redirect('/');
        $Cuartel = Gasto::findOrFail($request->id);
        $Cuartel->estado = 'Inicio';
        $Cuartel->save();

        $estado =Gasto::where('id','!=',$request->id)
        ->update(['estado'=>'creado']);


    }

    public function total_temporada(){
           
        $fechaInicio = DB::table('gastos')->where('estado','=','Inicio')->first();
        
        $now=Carbon::now();
        
    
        $totalTemporada=Gasto::select('Total')
        ->whereBetween('fecha_hora',[$fechaInicio->fecha_hora, $now->format('y-m-d')])
        ->sum('Total');

        $totalH=Gasto::orderBy('gastos.fecha_hora', 'desc')->get()->first();
        $nombre=Gasto::orderBy('gastos.fecha_hora', 'desc')->get()->first();
        return ['Total'=>$totalTemporada,'TotalH'=>$totalH->Total,'nombre'=>$nombre->nombreG];


    }
    public function gastos_cuartel(Request $request){
        $inicio=$request->fecha_hora_inicio;
        $termino=$request->fecha_hora_termino;
        $gastos = Gasto::join('cuartels','gastos.idCuartel','=','cuartels.id')
            ->join('categorias','gastos.idCategoria','=','categorias.id')
            ->select('gastos.id','gastos.nombreG','cuartels.nombreCuartel','categorias.nombreCategoria'
            ,'gastos.Total','gastos.fecha_hora','gastos.estado')
            ->where('cuartels.id',$request->idCuartel)
            ->whereBetween('fecha_hora',[$inicio,$termino])
            ->orderBy('gastos.id', 'desc')->get();
            

            $total = Gasto::join('cuartels','gastos.idCuartel','=','cuartels.id')
            ->join('categorias','gastos.idCategoria','=','categorias.id')
            ->select('gastos.Total')
            ->where('cuartels.id',$request->idCuartel)
            ->whereBetween('fecha_hora',[$inicio,$termino])
            ->sum('gastos.Total');

            $cont = Gasto::join('cuartels','gastos.idCuartel','=','cuartels.id')
            ->join('categorias','gastos.idCategoria','=','categorias.id')
            ->where('cuartels.id',$request->idCuartel)
            ->whereBetween('fecha_hora',[$inicio,$termino])
            ->count();


            $nombre = DB::table('cuartels')->where('id','=',$request->idCuartel)->first();
             

            $pdf2=\PDF::loadView('pdf.GastoCuartel',['gastos'=>$gastos,'cont'=>$cont,'nombre'=>$nombre->nombreCuartel,'total'=>$total,'inicio'=>$inicio,'termino'=>$termino]);
            return $pdf2->download('Gasto_Cuartel.pdf');
    }
     
    public function gastos_labor(Request $request){
        $inicio=$request->fecha_hora_inicio_L;
        $termino=$request->fecha_hora_termino_L;
        $gastos = Gasto::join('cuartels','gastos.idCuartel','=','cuartels.id')
            ->join('categorias','gastos.idCategoria','=','categorias.id')
            ->select('gastos.id','gastos.nombreG','cuartels.nombreCuartel','categorias.nombreCategoria'
            ,'gastos.Total','gastos.fecha_hora','gastos.estado')
            ->where('categorias.id',$request->idCategoria)
            ->whereBetween('fecha_hora',[$inicio,$termino])
            ->orderBy('gastos.id', 'desc')->get();
            

            $total = Gasto::join('cuartels','gastos.idCuartel','=','cuartels.id')
            ->join('categorias','gastos.idCategoria','=','categorias.id')
            ->select('gastos.Total')
            ->where('categorias.id',$request->idCategoria)
            ->whereBetween('fecha_hora',[$inicio,$termino])
            ->sum('gastos.Total');

            $cont = Gasto::join('cuartels','gastos.idCuartel','=','cuartels.id')
            ->join('categorias','gastos.idCategoria','=','categorias.id')
            ->where('categorias.id',$request->idCategoria)
            ->whereBetween('fecha_hora',[$inicio,$termino])
            ->count();


            $nombre = DB::table('categorias')->where('id','=',$request->idCategoria)->first();
             

            $pdf2=\PDF::loadView('pdf.GastoLabor',['gastos'=>$gastos,'cont'=>$cont,'nombre'=>$nombre->nombreCategoria,'total'=>$total,'inicio'=>$inicio,'termino'=>$termino]);
            return $pdf2->download('Gasto_Labor.pdf');

    }

    public function GraficoGastos(Request $request){
        $inicio=$request->fecha_hora_inicio;
        $termino=$request->fecha_hora_termino;
    $gastos=DB::table('gastos as i')
    ->select(DB::raw('MONTH(i.fecha_hora) as mes'),
    DB::raw('YEAR(i.fecha_hora) as anio'),
    DB::raw('SUM(i.Total) as total'))
    ->whereBetween('i.fecha_hora',[$inicio,$termino])
    ->groupBy(DB::raw('MONTH(i.fecha_hora)'),DB::raw('YEAR(i.fecha_hora)'))
    ->get();

    return ['gastos'=>$gastos];
    

    }
    public function GraficoGastos2(Request $request){
        $inicio=$request->fecha_hora_inicio2;
        $termino=$request->fecha_hora_termino2;
    $gastos=DB::table('gastos as i')
    ->select(DB::raw('MONTH(i.fecha_hora) as mes'),
    DB::raw('YEAR(i.fecha_hora) as anio'),
    DB::raw('SUM(i.Total) as total'))
    ->whereBetween('i.fecha_hora',[$inicio,$termino])
    ->groupBy(DB::raw('MONTH(i.fecha_hora)'),DB::raw('YEAR(i.fecha_hora)'))
    ->get();

    return ['gastos'=>$gastos];
    

    }

    public function GraficoCuartel(){
        $fechaInicio = DB::table('gastos')->where('estado','=','Inicio')->first();
        
        $now=Carbon::now();

        $count = Gasto::join('cuartels','gastos.idCuartel','=','cuartels.id')
            ->join('categorias','gastos.idCategoria','=','categorias.id')
            ->whereBetween('fecha_hora',[$fechaInicio->fecha_hora, $now->format('y-m-d')])
            ->count(DB::raw('DISTINCT cuartels.nombreCuartel'));

            
            

        $gastos = Gasto::join('cuartels','gastos.idCuartel','=','cuartels.id')
            ->join('categorias','gastos.idCategoria','=','categorias.id')
            ->select('cuartels.nombreCuartel'
            ,
            DB::raw('SUM(gastos.Total) as total'))
            ->whereBetween('fecha_hora',[$fechaInicio->fecha_hora, $now->format('y-m-d')])
            ->groupBy(DB::raw('cuartels.nombreCuartel WITH ROLLUP'))
            ->take($count)
            ->get();

 return ['gastos'=>$gastos];
    }

    public function graficoLabores(){
        $fechaInicio = DB::table('gastos')->where('estado','=','Inicio')->first();
        
        $now=Carbon::now();

        $count = Gasto::join('cuartels','gastos.idCuartel','=','cuartels.id')
            ->join('categorias','gastos.idCategoria','=','categorias.id')
            ->whereBetween('fecha_hora',[$fechaInicio->fecha_hora, $now->format('y-m-d')])
            ->count(DB::raw('DISTINCT categorias.nombreCategoria'));

            
            

        $gastos = Gasto::join('cuartels','gastos.idCuartel','=','cuartels.id')
            ->join('categorias','gastos.idCategoria','=','categorias.id')
            ->select('categorias.nombreCategoria'
            ,
            DB::raw('SUM(gastos.Total) as total'))
            ->whereBetween('fecha_hora',[$fechaInicio->fecha_hora, $now->format('y-m-d')])
            ->groupBy(DB::raw('categorias.nombreCategoria WITH ROLLUP'))
            ->take($count)
            ->get();

 return ['gastos'=>$gastos];
    }
        public function graficoInsumosTemporada(){
            $fechaInicio = DB::table('gastos')->where('estado','=','Inicio')->first();
        
            $now=Carbon::now();
    
            $count = CategoriaInsumo::join('insumos','categoria_insumos.id','=','insumos.id_categoria')
            ->join('detalle_gastos','insumos.id','=','detalle_gastos.idInsumo')
            ->join('gastos','detalle_gastos.idGasto','=','gastos.id')
                ->whereBetween('fecha_hora',[$fechaInicio->fecha_hora, $now->format('y-m-d')])
                ->count(DB::raw('DISTINCT categoria_insumos.nombreCategoriaInsumo'));
    
                
                
    
            $gastos = CategoriaInsumo::join('insumos','categoria_insumos.id','=','insumos.id_categoria')
                ->join('detalle_gastos','insumos.id','=','detalle_gastos.idInsumo')
                ->join('gastos','detalle_gastos.idGasto','=','gastos.id')
                ->select('categoria_insumos.nombreCategoriaInsumo',
                DB::raw('SUM(detalle_gastos.costo) as total'))
                ->whereBetween('fecha_hora',[$fechaInicio->fecha_hora, $now->format('y-m-d')])
            ->groupBy(DB::raw('categoria_insumos.nombreCategoriaInsumo WITH ROLLUP'))
            ->take($count)
            ->get();
                 
    
            return ['gastos'=>$gastos];

        }

        public function pdfTemporadaActual(){
            $fechaInicio = DB::table('gastos')->where('estado','=','Inicio')->first();
        
            $now=Carbon::now();
            
            $gastos=Gasto::join('cuartels','gastos.idCuartel','=','cuartels.id')
            ->join('categorias','gastos.idCategoria','=','categorias.id')
            ->select('gastos.id','gastos.nombreG','cuartels.nombreCuartel','categorias.nombreCategoria'
            ,'gastos.Total','gastos.fecha_hora','gastos.estado')
            ->whereBetween('fecha_hora',[$fechaInicio->fecha_hora, $now->format('y-m-d')])
            ->orderBy('gastos.fecha_hora', 'desc')->get();

            $cont=Gasto::join('cuartels','gastos.idCuartel','=','cuartels.id')
            ->join('categorias','gastos.idCategoria','=','categorias.id')
            ->select('gastos.id','gastos.nombreG','cuartels.nombreCuartel','categorias.nombreCategoria'
            ,'gastos.Total','gastos.fecha_hora','gastos.estado')
            ->whereBetween('fecha_hora',[$fechaInicio->fecha_hora, $now->format('y-m-d')])
            ->count();

            $total=Gasto::select('Total')
            ->whereBetween('fecha_hora',[$fechaInicio->fecha_hora, $now->format('y-m-d')])
            ->sum('Total');
    
           
            $pdf2=\PDF::loadView('pdf.TemporadaActual',['gastos'=>$gastos,'cont'=>$cont,'total'=>$total,'inicio'=>$fechaInicio->fecha_hora]);
            return $pdf2->download('Gasto_Temporada_Actual.pdf');

        }

        public function obtenerFechaTempActual(){

            $fechaInicio = DB::table('gastos')->where('estado','=','Inicio')->first();

            return ['fechaInicio'=>$fechaInicio->fecha_hora];
        }

        
    }

