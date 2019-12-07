<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Report;
use Session;

class ReporteController extends Controller
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
            $reportes = Report::with('user');
            return response()->json([
                'validate' => true,
                'data' => $reportes,
                'status' => 200
            ], 200);   
        } elseif ($rol && Session::get('role_us') == 'employee') {
            $reportes = Report::where('user_id', Session::get('id_us'))->with('user')->get();
            return response()->json([
                'validate' => true,
                'data' => $reportes,
                'status' => 200
            ], 200);  
        } else {
            Session::flash('type_message', 'error');
            Session::flash('message', 'No puedes acceder a esa sección del sitio');
            return Redirect('/homeReportes');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $req)
    {
        $rol = $req->user()->authorizeRoles(Session::get('role_us'));
        if ($rol && Session::get('role_us') == 'employee') {
            $reporte = null;
            return view('reportes.add-edit', compact('reporte'));
        } else {
            Session::flash('type_message', 'error');
            Session::flash('message', 'No puedes crear reportes, solo puedes consultarlos');
            return Redirect('/homeReportes');
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
        if ($rol) {
            $reporte = new Report();
            $reporte->name = $request->name;
            $reporte->description = $request->description;
            $reporte->user_id = $request->user_id;
            $usuario = User::where('id', Session::get('id_us'))->first();
            $reporte->user()->associate($usuario)->save();
            return response()->json([
                'validate' => true,
                'msj'=>'Reporte creado correctamente',
                'status' => 200
            ], 200);  
        } else {
            Session::flash('type_message', 'error');
            Session::flash('message', 'No puedes acceder a esa sección del sitio');
            return Redirect('/homeReportes');
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
    public function edit($id, Request $req)
    {
        $rol = $req->user()->authorizeRoles(Session::get('role_us'));
        if ($rol) {
            $reporte = Report::find($id);
            return view('reportes.add-edit', compact('reporte'));
        } else {
            Session::flash('type_message', 'error');
            Session::flash('message', 'No puedes acceder a esa sección del sitio');
            return Redirect('/homeReportes');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
        if ($rol) {
            $reporte = Report::find($id);
            if ($req->name != null) {
                $reporte->name = $req->name;
            }
            if ($req->description != null) {
                $reporte->description = $req->description;
            }
            $reporte->save();
        
            return response()->json([
                'validate' => true,
                'msj'=>'Reporte actualizado correctamente',
                'status' => 200
            ], 200);  
        } else {
            Session::flash('type_message', 'error');
            Session::flash('message', 'No puedes acceder a esa sección del sitio');
            return Redirect('/homeReportes');
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
        if ($rol) {
            $report = Report::find($req->id);
            $report->user()->dissociate();
            $report->delete();

            Session::flash('type_message', 'exito');
            Session::flash('message', 'El reporte ha sido borrado con éxito');
            return response()->json([
                'validate' => true,
                'msj'=>'Reporte eliminado correctamente',
                'status' => 200
            ], 200);    
        } else {
            Session::flash('type_message', 'error');
            Session::flash('message', 'No puedes acceder a esa sección del sitio');
            return Redirect('/homeReportes');
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
            $report = Report::find($id);
            return response()->json([
                'validate' => true,
                'data'=> $report,
                'status' => 200
            ], 200);    
        } else {
            Session::flash('type_message', 'error');
            Session::flash('message', 'No puedes acceder a esa sección del sitio');
            return Redirect('/homeReportes');
        }
    }
}
