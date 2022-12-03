<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Unidadmedida;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UnidadmedidaController extends Controller
{
    protected $folderview      = 'app.unidadmedida';
    protected $tituloAdmin     = 'Unidad de medida';
    protected $tituloRegistrar = 'Registrar unidad de medida';
    protected $tituloModificar = 'Modificar unidad de medida';
    protected $tituloEliminar  = 'Eliminar unidad de medida';
    protected $rutas           = array('create' => 'unidadmedida.create', 
            'edit'     => 'unidadmedida.edit', 
            'delete'   => 'unidadmedida.eliminar',
            'search'   => 'unidadmedida.buscar',
            'index'    => 'unidadmedida.index',
        );

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function buscar(Request $request)
    {
        $pagina           = $request->input('page');
        $filas            = $request->input('filas');
        $entidad          = 'Unidadmedida';
        $nombre           = Libreria::getParam($request->input('nombre'));
        $resultado        = Unidadmedida::listar($nombre);
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => 'EDIT', 'numero' => '1');
        $cabecera[]       = array('valor' => 'ELIM', 'numero' => '1');
        $cabecera[]       = array('valor' => 'ABREVIATURA', 'numero' => '1');
        $cabecera[]       = array('valor' => 'NOMBRE', 'numero' => '1');
        $cabecera[]       = array('valor' => 'DECIMALES', 'numero' => '1');
        
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
        $entidad          = 'Unidadmedida';
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
        $entidad      = 'Unidadmedida';
        $unidadmedida  = null;
        $formData     = array('unidadmedida.store');
        $formData     = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton        = 'Guardar'; 
        return view($this->folderview.'.mant')->with(compact('unidadmedida', 'formData', 'entidad', 'boton', 'listar'));
    }

    public function store(Request $request)
    {
        $listar     = Libreria::getParam($request->input('listar'), 'NO');
        $reglas     = array(
            'abreviatura'   => 'required|max:5',
            'medida'        => 'required|max:100'
        );
        $mensajes   = array();
        $validacion = Validator::make($request->all(), $reglas, $mensajes);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        
        $error = DB::transaction(function() use($request){
            $unidadmedida               = new Unidadmedida();
            $unidadmedida->abreviatura  = strtoupper($request->input('abreviatura'));
            $unidadmedida->medida  = strtoupper($request->input('medida'));
            if($request->input('decimales')=='N'){
                $unidadmedida->decimal  = 0;
            }else{
                $unidadmedida->decimal  = 1;
            }
            $unidadmedida->save(); 

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
        $existe = Libreria::verificarExistencia($id, 'unidad_medida');
        if ($existe !== true) {
            return $existe;
        }
        $listar   = Libreria::getParam($request->input('listar'), 'NO');
        $unidadmedida = Unidadmedida::find($id);
        $entidad  = 'Unidadmedida';
        $formData = array('unidadmedida.update', $id);
        $formData = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Modificar';
        return view($this->folderview.'.mant')->with(compact('unidadmedida', 'formData', 'entidad', 'boton', 'listar'));
    }

    public function update(Request $request, $id)
    {
        $existe = Libreria::verificarExistencia($id, 'unidad_medida');
        if ($existe !== true) {
            return $existe;
        }
        $reglas     = array(
            'abreviatura'   => 'required|max:5',
            'medida'        => 'required|max:100'
        );
        $mensajes   = array();
        $validacion = Validator::make($request->all(), $reglas, $mensajes);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        } 
        $error = DB::transaction(function() use($request, $id){
            $unidadmedida               = Unidadmedida::find($id);
            $unidadmedida->abreviatura  = strtoupper($request->input('abreviatura'));
            $unidadmedida->medida  = strtoupper($request->input('medida'));
            if($request->input('decimales')=='N'){
                $unidadmedida->decimal  = 0;
            }else{
                $unidadmedida->decimal  = 1;
            }
            $unidadmedida->save();
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
        $existe = Libreria::verificarExistencia($id, 'unidad_medida');
        if ($existe !== true) {
            return $existe;
        }
        $error = DB::transaction(function() use($id){
            $unidadmedida = Unidadmedida::find($id);
            $unidadmedida->delete();
        });
        return is_null($error) ? "OK" : $error;
    }

    public function eliminar($id, $listarLuego)
    {
        $existe = Libreria::verificarExistencia($id, 'unidad_medida');
        if ($existe !== true) {
            return $existe;
        }
        $listar = "NO";
        if (!is_null(Libreria::obtenerParametro($listarLuego))) {
            $listar = $listarLuego;
        }
        $modelo   = Unidadmedida::find($id);
        $entidad  = 'Unidadmedida';
        $formData = array('route' => array('unidadmedida.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Eliminar';
        return view('app.confirmarEliminar')->with(compact('modelo', 'formData', 'entidad', 'boton', 'listar'));
    }

}
