<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\insumos;
class insumosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       if (!$request->ajax()) return redirect('/');

       $buscar = $request->buscar;
       $criterio = $request->criterio;

       if($buscar==''){
        $insumos = insumos::join('categoria_insumos','insumos.id_categoria','=','categoria_insumos.id')
        ->select('insumos.id','insumos.nombre','insumos.unidad_de_medida','insumos.Precio_Unitario',
        'insumos.detalle','insumos.condicion','categoria_insumos.nombreCategoriaInsumo')
        ->orderBy('insumos.id','desc')->paginate(10);

       }else{
        $insumos = insumos::join('categoria_insumos','insumos.id_categoria','=','categoria_insumos.id')
        ->select('insumos.id','insumos.nombre','insumos.unidad_de_medida','insumos.Precio_Unitario',
        'insumos.detalle','insumos.condicion','categoria_insumos.nombreCategoriaInsumo')->where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(10);

       }
        
        return [
            'pagination' => [
                'total'        => $insumos->total(),
                'current_page' => $insumos->currentPage(),
                'per_page'     => $insumos->perPage(),
                'last_page'    => $insumos->lastPage(),
                'from'         => $insumos->firstItem(),
                'to'           => $insumos->lastItem(),
            ],
            'insumos' => $insumos
        ];
    }




    public function listarInsumos(Request $request)
    {
       //if (!$request->ajax()) return redirect('/');

       $buscar = $request->buscar;
       $criterio = $request->criterio;

       if($buscar==''){
        $insumos = insumos::join('categoria_insumos','insumos.id_categoria','=','categoria_insumos.id')
        ->select('insumos.id','insumos.nombre','insumos.unidad_de_medida','insumos.Precio_Unitario',
        'insumos.detalle','insumos.condicion','categoria_insumos.nombreCategoriaInsumo')->where('insumos.condicion','=','1')
        ->orderBy('insumos.id','desc')->paginate(10);

       }else{
        $insumos = insumos::join('categoria_insumos','insumos.id_categoria','=','categoria_insumos.id')
        ->select('insumos.id','insumos.nombre','insumos.unidad_de_medida','insumos.Precio_Unitario',
        'insumos.detalle','insumos.condicion','categoria_insumos.nombreCategoriaInsumo')->where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(10);

       }
        
        return [
            'insumos' => $insumos
        ];
    }
    public function store (Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $insumos=new insumos();
       
        $insumos->nombre=$request->input('nombre');
       
        $insumos->unidad_de_medida= $request->input('unidad');
        $insumos->Precio_Unitario= $request->input('precioU');
        $insumos->id_categoria= $request->input('tipo');
        $insumos->detalle= $request->input('detalle');
        $insumos->condicion='1';
       
       
        $insumos->save();
    }

    public function buscarInsumo(Request $request){
        if (!$request->ajax()) return redirect('/');

        $filtro =$request->filtro;
        $insumos = insumos::where('nombre','=',$filtro)
        ->select('id','nombre','Precio_Unitario','unidad_de_medida')->orderBy('nombre','asc')->take(1)->get();

          return['insumos'=>$insumos];
    }
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $insumos= insumos::findOrFail($request->id);
        $insumos->nombre=$request->input('nombre');
        
        $insumos->unidad_de_medida= $request->input('unidad');
        $insumos->Precio_Unitario= $request->input('precioU');
        $insumos->id_categoria= $request->input('tipo');
        $insumos->detalle= $request->input('detalle');
        $insumos->condicion='1';
        $insumos->save();
       
    }

   public function desactivar(Request $request){
    if (!$request->ajax()) return redirect('/');
    $insumos= insumos::findOrFail($request->id);
    
    $insumos->condicion= '0';
    $insumos->save();

   }

   public function activar (Request $request){
    if (!$request->ajax()) return redirect('/');
    $insumos= insumos::findOrFail($request->id);
    
    $insumos->condicion= '1';
    $insumos->save();


}
}
