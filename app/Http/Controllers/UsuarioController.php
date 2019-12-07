<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Report;
use Session;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        $rol = $req->user()->authorizeRoles(Session::get('role_us'));
        if ($rol && Session::get('role_us') == 'admin') {
            $users = User::with('roles')->get();
            return response()->json([
                'validate' => true,
                'data' => $users,
                'status' => 200
            ], 200);   
        } else {
            Session::flash('type_message', 'error');
            Session::flash('message', 'No puedes acceder a esa sección del sitio');
            return Redirect('/homeUsuarios');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $rol = $request->user()->authorizeRoles(Session::get('role_us'));
        if ($rol && Session::get('role_us') == 'admin') {
            $usuario = null;
            return view('usuarios.add-edit', compact('usuario'));
        } else {
            Session::flash('type_message', 'error');
            Session::flash('message', 'No puedes acceder a esa sección del sitio');
            return Redirect('/homeUsuarios');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rol = $request->user()->authorizeRoles(Session::get('role_us'));
        if ($rol && Session::get('role_us') == 'admin') {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->contrasena);
            $user->status = true;
            $user->save();
            $name_role = ($request->role_user == 1)?'admin':'employee';
            $role = Role::where('name', $name_role)->first();
            $user->roles()->attach($role);
            return response()->json([
                'validate' => true,
                'msj'=>'Usuario creado correctamente',
                'status' => 200
            ], 200);  
        } else {
            Session::flash('type_message', 'error');
            Session::flash('message', 'No puedes acceder a esa sección del sitio');
            return Redirect('/homeUsuarios');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $rol = $request->user()->authorizeRoles(Session::get('role_us'));
        if ($rol && Session::get('role_us') == 'admin') {
            $usuario = User::find($id);
            return view('usuarios.add-edit', compact('usuario'));
        } else {
            Session::flash('type_message', 'error');
            Session::flash('message', 'No puedes acceder a esa sección del sitio');
            return Redirect('/homeUsuarios');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function actualizar($id, Request $req)
    {
        $rol = $req->user()->authorizeRoles(Session::get('role_us'));
        if ($rol && Session::get('role_us') == 'admin') {
            $user = User::find($id);
            if ($req->name != null) {
                $user->name = $req->name;
            }
            if ($req->email != null) {
                $user->email = $req->email;
            }
            if ($req->password != null) {
                $user->password = bcrypt($req->password);
            }
            $user->status = true;
            $name_role = ($req->role_user == 1)?'admin':'employee';
            $role = Role::where('name', $name_role)->first();
            $user->roles()->detach();
            $user->roles()->attach($role);
            $user->save();
            return response()->json([
                'validate' => true,
                'msj'=>'Usuario actualizado correctamente',
                'status' => 200
            ], 200);  
        } else {
            Session::flash('type_message', 'error');
            Session::flash('message', 'No puedes acceder a esa sección del sitio');
            return Redirect('/homeUsuarios');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function borrar(Request $req)
    {
        $rol = $req->user()->authorizeRoles(Session::get('role_us'));
        if ($rol && Session::get('role_us') == 'admin') {
            $user = User::find($req->id);
            $user->roles()->detach();
            $user->delete();

            Session::flash('type_message', 'exito');
            Session::flash('message', 'El usuario ha sido borrado con éxito');
            return response()->json([
                'validate' => true,
                'msj'=>'Usuario eliminado correctamente',
                'status' => 200
            ], 200);    
        } else {
            Session::flash('type_message', 'error');
            Session::flash('message', 'No puedes acceder a esa sección del sitio');
            return Redirect('/homeUsuarios');
        }
    }

    /**
     * Get the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function obtenerPorId(Request $req, $id)
    {
        $rol = $req->user()->authorizeRoles(Session::get('role_us'));
        if ($rol) {
            $user = User::with('roles')->find($id);
            return response()->json([
                'validate' => true,
                'data'=> $user,
                'status' => 200
            ], 200);    
        } else {
            Session::flash('type_message', 'error');
            Session::flash('message', 'No puedes acceder a esa sección del sitio');
            return Redirect('/homeUsuarios');
        }
    }

}
