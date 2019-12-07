<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Redirect;
use App\Models\User;
use App\Models\Report;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $rol_admin = $request->user()->authorizeRoles('admin');
        $rol_user = $request->user()->authorizeRoles('employee');
        if ($rol_admin) {
            $usuarios = User::paginate(20);
            return view('usuarios.index', compact('usuarios'));
        } elseif ($rol_user) {
            $reportes = Report::where('user_id', Session::get('id_us'))->paginate(20);
            return view('reportes.index',  compact('reportes'));
        } else {
            abort(403, 'Unauthorized user');
        }
        
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index_reportes(Request $request)
    {
        $rol_admin = $request->user()->authorizeRoles('admin');
        $rol_user = $request->user()->authorizeRoles('employee');
        if ($rol_admin) {
            $reportes = Report::paginate(20);
            return view('reportes.index', compact('reportes'));
        } elseif ($rol_user) {
            $reportes = Report::where('user_id', Session::get('id_us'))->paginate(20);
            return view('reportes.index',  compact('reportes'));
        } else {
            abort(403, 'Unauthorized user');
        }
        
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index_usuarios(Request $request)
    {
        $rol_admin = $request->user()->authorizeRoles('admin');
        if ($rol_admin) {
            $usuarios = User::paginate(20);
            return view('usuarios.index', compact('usuarios'));
        } else {
            abort(403, 'Unauthorized user');
        }
        
    }

    /**
     * Función que permite validar los campos que pueden estar presentes en un formulario, para permitir o no su almacenamiento en la base de datos
     *
     * @return \Illuminate\Http\Response
     */
    public function validar_formulario(Request $req){
        $nom = $req->nom;
        $valor = $req->valor;
        //Validación del campo nombre del formulario
        if(strcmp($nom,'nombre') == 0){
            if(strlen($valor) > 0){
                echo "Correcto";
            }  
            else{
                echo "El campo no puede estar vacío";
            }
        }
        //Validación del campo email del formulario
        if(strcmp($nom,'email') == 0){
            if(filter_var($valor, FILTER_VALIDATE_EMAIL)){
                $us = User::where('email','=',$valor)->first();
                if($us == null){
                    echo 'Correcto';
                }
                else{
                    echo 'Alguien más ha usado ese correo para registrarse. Intente de nuevo';
                }
            }
            else{
                echo 'El formato del correo electrónico es nombre@servidor.info';
            }

        }
        //Validación del campo categoria del formulario
        if(strcmp($nom,'tipo_usuario') == 0){
            if($valor > 0){
                echo 'Correcto';
            }  
            else{
                echo "Debe seleccionar una opción";
            }
        }
        //Validación del campo descripcion del formulario
        if(strcmp($nom,'descripcion') == 0){
            if(strlen($valor) > 0){
                echo "Correcto";
            }  
            else{
                echo "El campo no puede estar vacío";
            }
        }
        //Validación del campo contrasena del formulario
        if(strcmp($nom,'contrasena') == 0 || strcmp($nom,'contrasena_conf') == 0){
            if(strlen($valor) < 6){
                echo 'La nueva clave debe tener más de 6 caracteres. Intente de nuevo';
            }
            else{
                //Comprobar que la nueva contraseña no tiene más de 16 caracteres
                if(strlen($valor) > 25){
                    echo 'La nueva clave no debe tener más de 25 caracteres. Intente de nuevo';
                }   
                else{
                    //Comprobar que la nueva contraseña tiene al menos una letra minúscula
                    if (!preg_match('`[a-z]`',$valor)){
                        echo 'La nueva clave debe tener al menos una letra minúscula. Intente de nuevo';
                    }
                    else{
                        //Comprobar que la nueva contraseña tiene al menos una letra mayúscula
                        if (!preg_match('`[A-Z]`',$valor)){
                            echo 'La nueva clave debe tener al menos una letra mayúscula. Intente de nuevo';
                        }    
                        else{
                            //Comprobar que la nueva contraseña tiene al menos un número
                            if (!preg_match('`[0-9]`',$valor)){
                                echo 'La nueva clave debe tener al menos un número. Intente de nuevo';
                            }
                            else{
                                if(Session::has('id_cuenta')){
                                    $val = User::where('id','=',Session::get('id_us'))->first();
                                    $comp = Hash::check($valor, $val->contrasena);
                                    if($comp == 1){
                                        echo 'La nueva contraseña es igual a la configurada actualmente en la cuenta. Intente de nuevo';
                                    }
                                    else{
                                        echo 'Correcto';
                                    }

                                }
                                else{
                                    echo 'Correcto';
                                }
                                
                            }
                        }
                    }
                }
            }
        }
        //Validación del campo status del formulario
        if(strcmp($nom,'status') == 0){
            echo "Correcto";
        }
    }
    /**
     * Función que permite contar los errores de un formulario, para evitar enviarlo
     *
     * @return \Illuminate\Http\Response
     */
    public function contar_errores_form(Request $request, $mensaje){
        if(!Session::has('errores_form')){
            Session::put('errores_form',0);
        }
        if(Session::get('errores_form') < 0){
            Session::put('errores_form',0);
        }
        if($mensaje == 1){
            if(Session::get('errores_form') > 0){
                Session::put('errores_form',Session::get('errores_form')-1);
            }
            echo Session::get('errores_form');
        }
        else if($mensaje == 2){
            Session::put('errores_form',Session::get('errores_form')+1);
            echo Session::get('errores_form');
        }
        else if($mensaje == 3){
            Session::put('errores_form',0);
            echo Session::get('errores_form');
        }
    }
}
