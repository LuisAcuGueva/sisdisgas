<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Hash;
use Validator;
use App\Http\Requests;
use App\Distrito;
use App\Provincia;
use App\Infraccion;
use App\Esquela;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class EsquelaController extends Controller
{
    protected $folderview      = 'app.esquela';
    protected $tituloAdmin     = 'Esquelas Inductivas';
    protected $tituloRegistrar = 'Registrar Esquela Inductiva';
    protected $tituloModificar = 'Modificar Esquela Inductiva';
    protected $tituloEliminar  = 'Eliminar Esquela Inductiva';
    protected $rutas           = array('create' => 'esquela.create', 
            'edit'   => 'esquela.edit', 
            'delete' => 'esquela.eliminar',
            'search' => 'esquela.buscar',
            'index'  => 'esquela.index',
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
        $entidad          = 'Esquela';
        $numero           = Libreria::getParam($request->input('numero'));
        $ruc              = Libreria::getParam($request->input('ruc'));
        $resultado        = Esquela::listar($numero,$ruc, 2);
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => 'EDIT', 'numero' => '1');
        $cabecera[]       = array('valor' => 'ELIM', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Número', 'numero' => '1');
        $cabecera[]       = array('valor' => 'RUC', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Contribuyente', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Descripción de la carga', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Fecha de notificación', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Fecha de cita', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Fecha de vencimiento', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Infracción', 'numero' => '1');
        $cabecera[]       = array('valor' => '# Expediente', 'numero' => '1');
        
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
        $entidad          = 'Esquela';
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
        $entidad        = 'Esquela'; 
        $esquela        = null;
        $formData       = array('esquela.store');
        $cboEstado = [
            "" => "Selecciones estado",
            "1" => "Generada",
            "2" => "Notificada",
            "3" => "En proceso",
        ];
        $cboInfraccion = Infraccion::all();
        $cboInfraccion2 = array();
        foreach ($cboInfraccion as $key => $infraccion) {
            $id = $infraccion->id;
            $label = "Art. ".$infraccion->articulo." Num. ".$infraccion->numeral." - ".$infraccion->descripcion;
            $cboInfraccion2 = $cboInfraccion2 + [ $id => $label ];
        }
        $cboInfraccion = array('' => 'Seleccione infracción') + $cboInfraccion2;
        $formData       = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton          = 'Registrar'; 
        $accion = 0;
        return view($this->folderview.'.mant')->with(compact('accion', 'cboEstado', 'cboInfraccion','esquela', 'formData', 'entidad', 'boton', 'listar'));
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
            'fecha_cita'    => 'required',
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
            $esquela                               = new Esquela();
            $esquela->tipo                         = 2; //ESQUELA
            $esquela->numero                       = $request->input('numero');
            $esquela->estado                       = $request->input('estado');
            $esquela->fecha_generacion             = $request->input('fecha_generacion');
            $esquela->fecha_notificacion           = $request->input('fecha_notificacion');
            $esquela->fecha_cita                   = $request->input('fecha_cita');
            //horacita
            //diferencia dias notificacion y cita (en cuestion)
            $esquela->fecha_vencimiento            = $request->input('fecha_vencimiento');
            $esquela->fecha_limite_tolerancia      = $request->input('fecha_limite_tolerancia');
            $esquela->resultados           = strtoupper($request->input('resultados'));
            $esquela->observaciones                = strtoupper($request->input('observaciones'));
            $esquela->asistencia_invitacion        = $request->input('asistencia');
            $esquela->regularizacion               = $request->input('regularizacion');
            $esquela->levanta_incosistencia        = $request->input('inconsistencia');
            $esquela->realiza_pago                 = $request->input('pago');
            //reiterativo
            $esquela->carga_id                     = 1;
            $esquela->verificador_id               = $request->input('verificador_id');
            $esquela->supervisor_id                = $request->input('supervisor_id');
            $esquela->infraccion_id                = $request->input('infraccion_id');
            $esquela->save();
            
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
        $esquela        = Esquela::find($id);
        $entidad        = 'Esquela';
        $formData       = array('esquela.update', $id);
        $formData       = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton          = 'Modificar';
        $cboEstado = [
            "" => "Selecciones estado",
            "1" => "Generada",
            "2" => "Notificada",
            "3" => "En proceso",
        ];
        $cboInfraccion = Infraccion::all();
        $cboInfraccion2 = array();
        foreach ($cboInfraccion as $key => $infraccion) {
            $id = $infraccion->id;
            $label = "Art. ".$infraccion->articulo." Num. ".$infraccion->numeral." - ".$infraccion->descripcion;
            $cboInfraccion2 = $cboInfraccion2 + [ $id => $label ];
        }
        $cboInfraccion = array('' => 'Seleccione infracción') + $cboInfraccion2;
        $accion = 1;
        return view($this->folderview.'.mant')->with(compact( 'accion' , 'cboEstado', 'cboInfraccion', 'esquela', 'formData', 'entidad', 'boton', 'listar'));
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
            'fecha_cita'    => 'required',
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
            $esquela                               = Esquela::find($id);
            $esquela->tipo                         = 2; //ESQUELA
            $esquela->numero                       = $request->input('numero');
            $esquela->estado                       = $request->input('estado');
            $esquela->fecha_generacion             = $request->input('fecha_generacion');
            $esquela->fecha_notificacion           = $request->input('fecha_notificacion');
            $esquela->fecha_cita                   = $request->input('fecha_cita');
            //horacita
            //diferencia dias notificacion y cita
            $esquela->fecha_vencimiento            = $request->input('fecha_vencimiento');
            $esquela->fecha_limite_tolerancia      = $request->input('fecha_limite_tolerancia');
            $esquela->resultados                   = strtoupper($request->input('resultados'));
            $esquela->observaciones                = strtoupper($request->input('observaciones'));
            $esquela->asistencia_invitacion        = $request->input('asistencia');
            $esquela->regularizacion               = $request->input('regularizacion');
            $esquela->levanta_incosistencia        = $request->input('inconsistencia');
            $esquela->realiza_pago                 = $request->input('pago');
            //reiterativo
            $esquela->carga_id                     = 1;
            $esquela->verificador_id               = $request->input('verificador_id');
            $esquela->supervisor_id                = $request->input('supervisor_id');
            $esquela->infraccion_id                = $request->input('infraccion_id');
            $esquela->save();
            
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
            $esquela = Esquela::find($id);
            $esquela->delete();
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
        $modelo   = Esquela::find($id);
        $entidad  = 'Esquela';
        $formData = array('route' => array('esquela.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Eliminar';
        return view('app.confirmarEliminar')->with(compact('modelo', 'formData', 'entidad', 'boton', 'listar'));
    }

}


