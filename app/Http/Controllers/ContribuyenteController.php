<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Hash;
use Validator;
use App\Http\Requests;
use App\Distrito;
use App\Provincia;
use App\Departamento;
use App\Contribuyente;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ContribuyenteController extends Controller
{
    protected $folderview      = 'app.contribuyente';
    protected $tituloAdmin     = 'Contribuyentes';
    protected $tituloRegistrar = 'Registrar Contribuyente';
    protected $tituloModificar = 'Modificar Contribuyente';
    protected $tituloEliminar  = 'Eliminar Contribuyente';
    protected $rutas           = array('create' => 'contribuyente.create', 
            'edit'   => 'contribuyente.edit', 
            'delete' => 'contribuyente.eliminar',
            'search' => 'contribuyente.buscar',
            'index'  => 'contribuyente.index',
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
        $entidad          = 'Contribuyente';
        $contribuyente    = Libreria::getParam($request->input('contribuyente'));
        $ruc              = Libreria::getParam($request->input('ruc'));
        $resultado        = Contribuyente::listar($contribuyente,$ruc);
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => 'EDIT', 'numero' => '1');
        $cabecera[]       = array('valor' => 'ELIM', 'numero' => '1');
        $cabecera[]       = array('valor' => 'RUC', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Contribuyente', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Teléfono', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Dirección', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Distrito', 'numero' => '1');
        
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
        $entidad          = 'Contribuyente';
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
        $entidad        = 'Contribuyente'; 
        $contribuyente        = null;
        $formData       = array('contribuyente.store');
        $formData       = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton          = 'Guardar'; 
        $accion = 0;
        return view($this->folderview.'.mant')->with(compact('accion' ,'contribuyente', 'formData', 'entidad', 'boton', 'listar'));
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
            'ruc'       => 'required|max:11|unique:contribuyente,ruc,NULL,id,deleted_at,NULL',
            'contribuyente'    => 'required|max:150',
            'telefono'    => 'required|max:9',
            'direccion'    => 'required|max:150',
            );
        $validacion = Validator::make($request->all(),$reglas);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $error = DB::transaction(function() use($request){
            $contribuyente                    = new Contribuyente();
            $contribuyente->ruc               = $request->input('ruc');
            $contribuyente->contribuyente     = strtoupper($request->input('contribuyente'));
            $contribuyente->telefono          = strtoupper($request->input('telefono'));
            $contribuyente->direccion         = strtoupper($request->input('direccion'));
            $contribuyente->save();
            
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
        $existe = Libreria::verificarExistencia($id, 'contribuyente');
        if ($existe !== true) {
            return $existe;
        }
        $listar         = Libreria::getParam($request->input('listar'), 'NO');
        $contribuyente        = Contribuyente::find($id);
        $entidad        = 'Contribuyente';
        $formData       = array('contribuyente.update', $id);
        $formData       = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton          = 'Modificar';
        $accion = 1;
        return view($this->folderview.'.mant')->with(compact( 'accion' , 'contribuyente', 'formData', 'entidad', 'boton', 'listar'));
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
        $existe = Libreria::verificarExistencia($id, 'contribuyente');
        if ($existe !== true) {
            return $existe;
        }
        $listar     = Libreria::getParam($request->input('listar'), 'NO');
        $reglas = array(
            'ruc'       => 'required|max:11',
            'contribuyente'    => 'required|max:150',
            'telefono'    => 'required|max:9',
            'direccion'    => 'required|max:150',
            );
        $validacion = Validator::make($request->all(),$reglas);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $error = DB::transaction(function() use($request, $id){
            $contribuyente                = Contribuyente::find($id);
            $contribuyente->ruc               = $request->input('ruc');
            $contribuyente->contribuyente     = strtoupper($request->input('contribuyente'));
            $contribuyente->telefono          = strtoupper($request->input('telefono'));
            $contribuyente->direccion         = strtoupper($request->input('direccion'));
            $contribuyente->save();
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
        $existe = Libreria::verificarExistencia($id, 'contribuyente');
        if ($existe !== true) {
            return $existe;
        }
        $error = DB::transaction(function() use($id){
            $contribuyente = Contribuyente::find($id);
            $contribuyente->delete();
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
        $existe = Libreria::verificarExistencia($id, 'contribuyente');
        if ($existe !== true) {
            return $existe;
        }
        $listar = "NO";
        if (!is_null(Libreria::obtenerParametro($listarLuego))) {
            $listar = $listarLuego;
        }
        $modelo   = Contribuyente::find($id);
        $entidad  = 'Contribuyente';
        $formData = array('route' => array('contribuyente.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Eliminar';
        return view('app.confirmarEliminar')->with(compact('modelo', 'formData', 'entidad', 'boton', 'listar'));
    }

}
