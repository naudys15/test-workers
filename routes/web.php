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
//Index del sitio
Route::get('/', function () {
    return view('welcome');
});
//Login y registro
Auth::routes();
//Home de Autenticación
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/homeReportes', 'HomeController@index_reportes')->name('home.reportes');
Route::get('/homeUsuarios', 'HomeController@index_usuarios')->name('home.usuarios');
//Reportes
Route::post('/reportes/actualizar/{id}',['as' => 'reportes.actualizar', 'uses' => 'ReporteController@actualizar']);
Route::get('/reportes/obtener/{id}',['as' => 'reportes.obtener_por_id', 'uses' => 'ReporteController@obtenerPorId']);
Route::delete('/reportes/borrar',['as' => 'reportes.borrar', 'uses' => 'ReporteController@borrar']);
Route::resource('reportes', 'ReporteController');
//Usuarios
Route::post('/usuarios/actualizar/{id}',['as' => 'usuarios.actualizar', 'uses' => 'UsuarioController@actualizar']);
Route::get('/usuarios/obtener/{id}',['as' => 'usuarios.obtener_por_id', 'uses' => 'UsuarioController@obtenerPorId']);
Route::get('/usuarios/obtenerTodos',['as' => 'usuarios.index', 'uses' => 'UsuarioController@index']);
Route::delete('/usuarios/borrar',['as' => 'usuarios.borrar', 'uses' => 'UsuarioController@borrar']);
Route::resource('usuarios', 'UsuarioController');

//Contar los errores de un formulario, para prevenir enviar un formulario con datos erroneos
Route::post('/contar_errores_form/{mensaje}',['as' => 'contar_errores_form', 'uses' => 'HomeController@contar_errores_form']);
//Validar los campos de los formularios
Route::post('/validar_formulario',['as' => 'validar_formulario', 'uses' => 'HomeController@validar_formulario']);
