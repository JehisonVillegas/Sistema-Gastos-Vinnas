<?php

namespace App\Http\Controllers;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
      // if (!$request->ajax()) return redirect('/');

       $buscar = $request->buscar;
       $criterio = $request->criterio;

       if($buscar==''){
        $users = User::join('roles','users.idrol','=','roles.id')
        ->select('users.id','users.usuario','users.password','roles.nombre','users.condicion')
        ->orderBy('users.id','desc')->paginate(10);

       }else{
        $users = User::join('roles','users.idrol','=','roles.id')
        ->select('users.id','users.usuario','users.password','roles.nombre','users.condicion')
        ->where($criterio, 'like', '%'. $buscar . '%')->orderBy('users.id', 'desc')->paginate(10);

       }
        
        return [
            'pagination' => [
                'total'        => $users->total(),
                'current_page' => $users->currentPage(),
                'per_page'     => $users->perPage(),
                'last_page'    => $users->lastPage(),
                'from'         => $users->firstItem(),
                'to'           => $users->lastItem(),
            ],
            'users' => $users
        ];
    }




   // aqui quede
    public function store (Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $usuario=new User();
       
        $usuario->usuario=$request->input('usuario');
       
        $usuario->password= bcrypt( $request->password);
        $usuario->idrol= $request->input('idrol');
        $usuario->condicion='1';
        
       
       
        $usuario->save();
    }

    
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $usuarios= User::findOrFail($request->id);
        $usuario->usuario=$request->input('usuario');
       
        $usuario->password= $request->input('password');
        $usuario->rol= $request->input('idrol');
        $usuario->condicion='1';
        $insumos->save();
       
    }  

    
   public function desactivar(Request $request){
    if (!$request->ajax()) return redirect('/');
    $usuarios= User::findOrFail($request->id);
    
    
    $usuarios->delete();

   }

   public function activar (Request $request){
    if (!$request->ajax()) return redirect('/');
    $usuarios= User::findOrFail($request->id);
    
    $usuarios->condicion= '1';
    $usuarios->save();


}
}
