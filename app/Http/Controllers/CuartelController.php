<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cuartel;
class CuartelController extends Controller
{
    //

    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
 
        $buscar = $request->buscar;
        $criterio = $request->criterio;
         
        if ($buscar==''){
            $cuarteles = Cuartel::orderBy('id', 'desc')->paginate(5);
        }
        else{
            $cuarteles = Cuartel::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(5);
        }
         
 
        return [
            'pagination' => [
                'total'        => $cuarteles->total(),
                'current_page' => $cuarteles->currentPage(),
                'per_page'     => $cuarteles->perPage(),
                'last_page'    => $cuarteles->lastPage(),
                'from'         => $cuarteles->firstItem(),
                'to'           => $cuarteles->lastItem(),
            ],
            'cuarteles' => $cuarteles
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
        $Cuartel = new Cuartel();
        $Cuartel->nombreCuartel = $request->nombre;
        $Cuartel->Tipo_uva = $request->uva;
        $Cuartel->Tipo_riego = $request->riego;
        $Cuartel->condicion = '1';
        
        $Cuartel->save();
    }
   
 
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $Cuartel = Cuartel::findOrFail($request->id);
        $Cuartel->nombre = $request->nombre;
        $Cuartel->Tipo_uva = $request->uva;
        $Cuartel->Tipo_riego = $request->riego;
        $Cuartel->condicion = '1';
        
        $Cuartel->save();
    }
 
    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $Cuartel = Cuartel::findOrFail($request->id);
        $Cuartel->condicion = '0';
        $Cuartel->save();
    }

    public function selectcuartel(Request $request){
       if (!$request->ajax()) return redirect('/');
   
        $cuarteles=Cuartel::where('condicion','=','1')
        
        ->select('id','nombreCuartel')->orderBy('nombreCuartel','asc')->get();

        return ['cuarteles'=>$cuarteles];


    }
    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $Cuartel = Cuartel::findOrFail($request->id);
        $Cuartel->condicion = '1';
        $Cuartel->save();
    }
 
  public function informecuarteles(){
    $cuarteles = Cuartel::orderBy('id', 'desc')->paginate(5);
    $pdf=\PDF::loadView('pdf.cuarteles',['cuarteles'=>$cuarteles]);
    return $pdf->download('cuarteles.pdf');
  }

}
