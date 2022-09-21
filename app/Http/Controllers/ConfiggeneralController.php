<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Configgeneral;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ConfiggeneralController extends Controller
{

    protected $folderview      = 'app.configgeneral';
    protected $tituloAdmin     = 'Configuración general';
    protected $tituloRegistrar = 'Registrar parametro de configuración';
    protected $tituloModificar = 'Modificar parametro de configuración';
    protected $tituloEliminar  = 'Eliminar parametro de configuración';
    protected $rutas           = array('create' => 'configgeneral.create', 
            'edit'     => 'configgeneral.edit', 
            'delete'   => 'configgeneral.eliminar',
            'search'   => 'configgeneral.buscar',
            'index'    => 'configgeneral.index',
        );

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function buscar(Request $request)
    {
        $pagina           = $request->input('page');
        $filas            = $request->input('filas');
        $entidad          = 'Configgeneral';
        $nombre           = Libreria::getParam($request->input('nombre'));
        $resultado        = Configgeneral::listar($nombre);
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => 'EDIT', 'numero' => '1');
        $cabecera[]       = array('valor' => 'ELIM', 'numero' => '1');
        $cabecera[]       = array('valor' => 'NOMBRE', 'numero' => '1');
        $cabecera[]       = array('valor' => 'DESCRIPCIÓN', 'numero' => '1');
        $cabecera[]       = array('valor' => 'VALOR', 'numero' => '1');
        
        $titulo_modificar = $this->tituloModificar;
        $titulo_eliminar  = $this->tituloEliminar;
        $ruta             = $this->rutas;
        if (count($lista) > 0) {
            $clsLibreria     = new Libreria();
            $paramPaginacion = $clsLibreria->generarPaginacion($lista, $pagina, $filas, $entidad);
            $paginacion      = $paramPaginacion['cadenapaginacion'];
            $inicio          = $paramPaginacion['inicio'];
            $fin             = $paramPaginacion['fin'];
            $paginaactual    = $paramPaginacion['nuevapagina'];
            $lista           = $resultado->paginate($filas);
            $request->replace(array('page' => $paginaactual));
            return view($this->folderview.'.list')->with(compact('lista', 'paginacion', 'inicio', 'fin', 'entidad', 'cabecera', 'titulo_modificar', 'titulo_eliminar', 'ruta'));
        }
        return view($this->folderview.'.list')->with(compact('lista', 'entidad'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $entidad          = 'Configgeneral';
        $title            = $this->tituloAdmin;
        $titulo_registrar = $this->tituloRegistrar;
        $ruta             = $this->rutas;
        return view($this->folderview.'.admin')->with(compact('entidad', 'title', 'titulo_registrar', 'ruta'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $listar       = Libreria::getParam($request->input('listar'), 'NO');
        $entidad      = 'Configgeneral';
        $configgeneral  = null;
        $formData     = array('configgeneral.store');
        $formData     = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton        = 'Guardar'; 
        return view($this->folderview.'.mant')->with(compact('configgeneral', 'formData', 'entidad', 'boton', 'listar'));
    }

    public function store(Request $request)
    {
        $listar     = Libreria::getParam($request->input('listar'), 'NO');
        $reglas     = array('nombre' => 'required|max:100',
                            'descripcion' => 'required|max:250',
                            'valor' => 'required|max:250');
        $mensajes   = array();
        $validacion = Validator::make($request->all(), $reglas, $mensajes);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        
        $error = DB::transaction(function() use($request){
            $configgeneral       = new Configgeneral();
            $configgeneral->nombre = strtoupper($request->input('nombre'));
            $configgeneral->descripcion = strtoupper($request->input('descripcion'));
            $configgeneral->valor = strtoupper($request->input('valor'));
            $configgeneral->save(); 

        });
        return is_null($error) ? "OK" : $error;
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
        $existe = Libreria::verificarExistencia($id, 'metodo_pagos');
        if ($existe !== true) {
            return $existe;
        }
        $listar   = Libreria::getParam($request->input('listar'), 'NO');
        $configgeneral = Configgeneral::find($id);
        $entidad  = 'Configgeneral';
        $formData = array('configgeneral.update', $id);
        $formData = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Modificar';
        return view($this->folderview.'.mant')->with(compact('configgeneral', 'formData', 'entidad', 'boton', 'listar'));
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
        $existe = Libreria::verificarExistencia($id, 'metodo_pagos');
        if ($existe !== true) {
            return $existe;
        }
        $reglas     = array('nombre' => 'required|max:100',
                            'descripcion' => 'required|max:250',
                            'valor' => 'required|max:250');
        $mensajes   = array();
        $validacion = Validator::make($request->all(), $reglas, $mensajes);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        } 
        $error = DB::transaction(function() use($request, $id){
            $configgeneral       = Configgeneral::find($id);
            $configgeneral->nombre = strtoupper($request->input('nombre'));
            $configgeneral->descripcion = strtoupper($request->input('descripcion'));
            $configgeneral->valor = strtoupper($request->input('valor'));
            $configgeneral->save();
        });
        return is_null($error) ? "OK" : $error;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $existe = Libreria::verificarExistencia($id, 'metodo_pagos');
        if ($existe !== true) {
            return $existe;
        }
        $error = DB::transaction(function() use($id){
            $configgeneral = Configgeneral::find($id);
            $configgeneral->delete();
        });
        return is_null($error) ? "OK" : $error;
    }

    /**
     * Función para confirmar la eliminación de un registrlo
     * @param  integer $id          id del registro a intentar eliminar
     * @param  string $listarLuego consultar si luego de eliminar se listará
     * @return html              se retorna html, con la ventana de confirmar eliminar
     */
    public function eliminar($id, $listarLuego)
    {
        $existe = Libreria::verificarExistencia($id, 'metodo_pagos');
        if ($existe !== true) {
            return $existe;
        }
        $listar = "NO";
        if (!is_null(Libreria::obtenerParametro($listarLuego))) {
            $listar = $listarLuego;
        }
        $modelo   = Configgeneral::find($id);
        $entidad  = 'Configgeneral';
        $formData = array('route' => array('configgeneral.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Eliminar';
        return view('app.confirmarEliminar')->with(compact('modelo', 'formData', 'entidad', 'boton', 'listar'));
    }
}
