<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['middleware'=>['guest']],function(){
    Route::get('/','Auth\LoginController@showLoginForm');
    Route::post('/login', 'Auth\LoginController@login')->name('login');
    Route::get('/gasto/gastoHistoricoPDF', 'GastosController@gastoHistoricoPDF');
    Route::get('/cuartel/informe', 'cuartelController@informecuarteles');
    
});

Route::group(['middleware'=>['auth']],function(){
     Route::post('/logout','Auth\LoginController@logout')->name('logout');

    Route::get('/main', function () {
        return view('contenido/contenido');
    })->name('main');

    Route::group(['middleware'=>['Vinnatero']],function(){
     // rutas de insumos
     Route::get('/insumos','insumosController@index');
     Route::post('/insumos/ingresar','insumosController@store');
     Route::put('/insumos/actualizar','insumosController@update');
     Route::put('/insumos/desactivar','insumosController@desactivar');
     Route::put('/insumos/activar','insumosController@activar');
     Route::get('/insumos/buscarInsumo','insumosController@buscarInsumo');
     Route::get('/insumos/listarInsumos','insumosController@listarInsumos');
     // rutas categorias
     Route::get('/categoria', 'CategoriaController@index');
     Route::post('/categoria/registrar', 'CategoriaController@store');
     Route::put('/categoria/actualizar', 'CategoriaController@update');
     Route::put('/categoria/desactivar', 'CategoriaController@desactivar');
     Route::put('/categoria/activar', 'CategoriaController@activar');
     Route::get('/categoria/selectCategoria', 'CategoriaController@selectCategoria');
     // rutas categorias_insumos
     Route::get('/categoriaInsumos', 'CategoriaInsumosController@index');
     Route::post('/categoriaInsumos/registrar', 'CategoriaInsumosController@store');
     Route::put('/categoriaInsumos/actualizar', 'CategoriaInsumosController@update');
     Route::put('/categoriaInsumos/desactivar', 'CategoriaInsumosController@desactivar');
     Route::put('/categoriaInsumos/activar', 'CategoriaInsumosController@activar');
     Route::get('/categoriaInsumos/selectCategoriaInsumos', 'CategoriaInsumosController@selectcategoriaInsumos');
     //rutas Cuarteles
     Route::get('/cuartel', 'cuartelController@index');
     Route::post('/cuartel/registrar', 'cuartelController@store');
     Route::put('/cuartel/actualizar', 'cuartelController@update');
     Route::put('/cuartel/desactivar', 'cuartelController@desactivar');
     Route::put('/cuartel/activar', 'cuartelController@activar');
     Route::get('/cuartel/selectcuartel', 'cuartelController@selectcuartel');
     //rutas Gastos
     Route::get('/gasto', 'GastosController@index');
     Route::post('/gasto/ingresar','GastosController@store');
     Route::put('/gasto/desactivar','GastosController@desactivar');
     Route::get('/gasto/obtenerCabecera', 'GastosController@obtenerCabecera');
     Route::get('/gasto/obtenerDetalles', 'GastosController@obtenerDetalles');
     
     Route::get('/gasto/gastoTemporada','GastosController@gastoTemporadaPDF');
     Route::put('/gasto/inicio_temporada','GastosController@inicio_temporada');
     Route::get('/gasto/total_temporada','GastosController@total_temporada');
     Route::get('/gasto/gastos_cuartel','GastosController@gastos_cuartel');
     Route::get('/gasto/gastos_labor','GastosController@gastos_labor');
     Route::get('/gasto/GraficoGastos','GastosController@GraficoGastos');
     Route::get('/gasto/GraficoGastos2','GastosController@GraficoGastos2');
     Route::get('/gasto/GraficoCuartel','GastosController@GraficoCuartel');
     Route::get('/gasto/graficoLabores','GastosController@graficoLabores');
     Route::get('/gasto/graficoInsumosTemporada','GastosController@graficoInsumosTemporada');
     Route::get('/gasto/gastosTemporadaActual','GastosController@pdfTemporadaActual');
     Route::get('/gasto/obtenerFechaTempActual','GastosController@obtenerFechaTempActual');
     
     
     //dashboard
     Route::get('/dashboard','DashboardController');
});
   
Route::group(['middleware'=>['Administrador']],function(){
  

    //usuarios
    
    Route::get('/user','UserController@index');
    Route::post('/user/registrar','UserController@store');
    Route::put('/user/actualizar','UserController@update');
    Route::put('/user/desactivar','UserController@desactivar');
    Route::put('/user/activar','UserController@activar');
    
    //rol
    Route::get('/rol/selectrol','RolController@selectrol');
    Route::get('/rol', 'RolController@index');
});

});








Route::get('/home', 'HomeController@index')->name('home');

