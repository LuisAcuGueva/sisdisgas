<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Hash;
use Validator;
use App\Http\Requests;
use App\Distrito;
use App\Provincia;
use App\Infraccion;
use App\Carta;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CartaController extends Controller
{
    protected $folderview      = 'app.carta';
    protected $tituloAdmin     = 'Cartas Inductivas';
    protected $tituloRegistrar = 'Registrar Carta Inductiva';
    protected $tituloModificar = 'Modificar Carta Inductiva';
    protected $tituloEliminar  = 'Eliminar Carta Inductiva';
    protected $rutas           = array('create' => 'carta.create', 
            'edit'   => 'carta.edit', 
            'delete' => 'carta.eliminar',
            'search' => 'carta.buscar',
            'index'  => 'carta.index',
        );

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
     * Mostrar el resultado de búsquedas
     * 
     * @return Response 
     */
    public function buscar(Request $request)
    {
        $pagina           = $request->input('page');
        $filas            = $request->input('filas');
        $entidad          = 'Carta';
        $numero    = Libreria::getParam($request->input('numero'));
        $ruc              = Libreria::getParam($request->input('ruc'));
        $resultado        = Carta::listar($numero,$ruc, 1);
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => 'EDIT', 'numero' => '1');
        $cabecera[]       = array('valor' => 'ELIM', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Número', 'numero' => '1');
        $cabecera[]       = array('valor' => 'RUC', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Contribuyente', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Descripción de la carga', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Fecha de notificación', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Fecha de vencimiento', 'numero' => '1');
        
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
        $entidad          = 'Carta';
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
        $listar         = Libreria::getParam($request->input('listar'), 'NO');
        $entidad        = 'Carta'; 
        $carta        = null;
        $formData       = array('carta.store');
        $cboEstado = [
            "" => "Selecciones estado",
            "1" => "Generada",
            "2" => "Notificada",
        ];
        $formData       = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton          = 'Guardar'; 
        $accion = 0;
        return view($this->folderview.'.mant')->with(compact('accion', 'cboEstado', 'carta', 'formData', 'entidad', 'boton', 'listar'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $listar     = Libreria::getParam($request->input('listar'), 'NO');
        $reglas = array(
            'numero'       => 'required|max:12|unique:accion_inductiva,numero,NULL,id,deleted_at,NULL',
            'estado'    => 'required',
            'fecha_generacion'    => 'required',
            'fecha_notificacion'    => 'required',
            'verificador_id'    => 'required',
            'supervisor_id'    => 'required',
            'fecha_vencimiento'    => 'required',
            'fecha_limite_tolerancia'    => 'required',
            );
        $validacion = Validator::make($request->all(),$reglas);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $error = DB::transaction(function() use($request){
            $carta                               = new Carta();
            $carta->tipo                         = 1; //CARTA
            $carta->numero                       = $request->input('numero');
            $carta->estado                       = $request->input('estado');
            $carta->fecha_generacion             = $request->input('fecha_generacion');
            $carta->fecha_notificacion           = $request->input('fecha_notificacion');
            $carta->fecha_vencimiento            = $request->input('fecha_vencimiento');
            $carta->fecha_limite_tolerancia      = $request->input('fecha_limite_tolerancia');
            $carta->resultados           = strtoupper($request->input('resultados'));
            $carta->observaciones                = strtoupper($request->input('observaciones'));
            $carta->carga_id                     = 1;
            $carta->verificador_id               = $request->input('verificador_id');
            $carta->supervisor_id                = $request->input('supervisor_id');
            $carta->save();
            
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
    public function edit($id, Request $request)
    {
        $existe = Libreria::verificarExistencia($id, 'accion_inductiva');
        if ($existe !== true) {
            return $existe;
        }
        $listar         = Libreria::getParam($request->input('listar'), 'NO');
        $carta          = Carta::find($id);
        $entidad        = 'Carta';
        $formData       = array('carta.update', $id);
        $formData       = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton          = 'Modificar';
        $cboEstado = [
            "" => "Selecciones estado",
            "1" => "Generada",
            "2" => "Notificada",
        ];
        $accion = 1;
        return view($this->folderview.'.mant')->with(compact( 'accion' , 'cboEstado', 'carta', 'formData', 'entidad', 'boton', 'listar'));
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
        $existe = Libreria::verificarExistencia($id, 'accion_inductiva');
        if ($existe !== true) {
            return $existe;
        }
        $listar     = Libreria::getParam($request->input('listar'), 'NO');
        $reglas = array(
            'numero'       => 'required|max:12',
            'estado'    => 'required',
            'fecha_generacion'    => 'required',
            'fecha_notificacion'    => 'required',
            'verificador_id'    => 'required',
            'supervisor_id'    => 'required',
            'fecha_vencimiento'    => 'required',
            'fecha_limite_tolerancia'    => 'required',
            );
        $validacion = Validator::make($request->all(),$reglas);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $error = DB::transaction(function() use($request, $id){
            $carta                               = Carta::find($id);
            $carta->tipo                         = 1; //CARTA
            $carta->numero                       = $request->input('numero');
            $carta->estado                       = $request->input('estado');
            $carta->fecha_generacion             = $request->input('fecha_generacion');
            $carta->fecha_notificacion           = $request->input('fecha_notificacion');
            $carta->fecha_vencimiento            = $request->input('fecha_vencimiento');
            $carta->fecha_limite_tolerancia      = $request->input('fecha_limite_tolerancia');
            $carta->resultados                   = strtoupper($request->input('resultados'));
            $carta->observaciones                = strtoupper($request->input('observaciones'));
            //contribuyente
            //carga
            $carta->carga_id                     = 1;
            $carta->verificador_id               = $request->input('verificador_id');
            $carta->supervisor_id                = $request->input('supervisor_id');
            $carta->save();
            
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
        $existe = Libreria::verificarExistencia($id, 'accion_inductiva');
        if ($existe !== true) {
            return $existe;
        }
        $error = DB::transaction(function() use($id){
            $carta = Carta::find($id);
            $carta->delete();
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
        $existe = Libreria::verificarExistencia($id, 'accion_inductiva');
        if ($existe !== true) {
            return $existe;
        }
        $listar = "NO";
        if (!is_null(Libreria::obtenerParametro($listarLuego))) {
            $listar = $listarLuego;
        }
        $modelo   = Carta::find($id);
        $entidad  = 'Carta';
        $formData = array('route' => array('carta.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Eliminar';
        return view('app.confirmarEliminar')->with(compact('modelo', 'formData', 'entidad', 'boton', 'listar'));
    }

}


