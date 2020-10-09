<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Concepto;
use App\Turnorepartidor;
use App\Person;
use App\Sucursal;
use App\Movimiento;
use App\Detalleturnopedido;
use App\Detalleventa;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TurnoController extends Controller
{

    protected $folderview      = 'app.turno';
    protected $tituloAdmin     = 'Repartidores en turno';
    protected $tituloMontoVuelto = 'Dar monto vuelto a repartidor';
    protected $tituloDescargaDinero = 'Ingreso de dinero a caja';
    protected $tituloCierreTurno = 'Cerrar turno de repartidor';
    protected $tituloDetalle  = 'Detalle de pedido';
    protected $tituloAnulacion  = 'Anular pedido';
    protected $rutas           = array('detalle' => 'turno.detalle', 
            'vuelto'     => 'turno.vuelto', 
            'descargadinero'     => 'turno.descargadinero', 
            'cierre'   => 'turno.cierre',
            'search'   => 'turno.buscar',
            'index'    => 'turno.index',
            'delete'   => 'turno.eliminar',
        );

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function buscar(Request $request)
    {
        $pagina           = $request->input('page');
        $filas            = $request->input('filas');
        $entidad          = 'Turnorepartidor';
        $turno_id         = Libreria::getParam($request->input('turno_id'));
        $lista            = array();
        $ingresos_repartidor = 0.00;
        $ingresos_credito = 0.00;
        $vueltos_repartidor = 0.00;
        $total_ingresos = 0.00;
        $egresos_repartidor = 0.00;
        $saldo_repartidor = 0.00;
        if($turno_id != null){
            $resultado        = Detalleturnopedido::where('turno_id', '=', $turno_id)
                                                ->join('movimiento', 'detalle_turno_pedido.pedido_id', '=', 'movimiento.id')
                                                ->orderby('fecha', 'DESC');
            $lista            = $resultado->get();

            $ingresos_repartidor = Detalleturnopedido::where('turno_id', '=', $turno_id)
                                                        ->join('movimiento', 'detalle_turno_pedido.pedido_id', '=', 'movimiento.id')
                                                        ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                                        ->where('estado',1)
                                                        ->where(function($subquery)
                                                            {
                                                                $subquery->where('concepto.id','=', 3);
                                                            })
                                                        ->sum('total');
                                                    
            $ingresos_credito = Detalleturnopedido::where('turno_id', '=', $turno_id)
                                                        ->join('movimiento', 'detalle_turno_pedido.pedido_id', '=', 'movimiento.id')
                                                        ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                                        ->where('estado',1)
                                                        ->where(function($subquery)
                                                            {
                                                                $subquery->where('concepto.id','=', 16);
                                                            })
                                                        ->sum('total');

            $vueltos_repartidor = Detalleturnopedido::where('turno_id', '=', $turno_id)
                                                        ->join('movimiento', 'detalle_turno_pedido.pedido_id', '=', 'movimiento.id')
                                                        ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                                        ->where('estado',1)
                                                        ->where(function($subquery)
                                                            {
                                                                $subquery->where('concepto.id','=', 12)->orwhere('concepto.id','=', 15);
                                                            })
                                                        ->sum('total');

            $egresos_repartidor = Detalleturnopedido::where('turno_id', '=', $turno_id)
                                                        ->join('movimiento', 'detalle_turno_pedido.pedido_id', '=', 'movimiento.id')
                                                        ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                                        ->where('estado',1)
                                                        ->where(function($subquery)
                                                            {
                                                                $subquery->where('concepto.id','=', 13)->orwhere('concepto.id','=', 14);
                                                            })
                                                        ->sum('total');

            round($ingresos_repartidor,2);
            round($ingresos_credito,2);
            round($vueltos_repartidor,2);
            round($egresos_repartidor,2);

            $total_ingresos = $ingresos_repartidor + $vueltos_repartidor + $ingresos_credito;

            $saldo_repartidor = $ingresos_repartidor + $ingresos_credito + $vueltos_repartidor - $egresos_repartidor;

            round($saldo_repartidor,2);

        }
        $cabecera         = array();
        $cabecera[]       = array('valor' => 'VER', 'numero' => '1');
        $cabecera[]       = array('valor' => 'ANUL', 'numero' => '1');
        $cabecera[]       = array('valor' => 'FECHA Y HORA', 'numero' => '1');
        $cabecera[]       = array('valor' => 'CONCEPTO', 'numero' => '1');
        $cabecera[]       = array('valor' => 'CLIENTE', 'numero' => '1');
        $cabecera[]       = array('valor' => 'VALE', 'numero' => '1');
        $cabecera[]       = array('valor' => 'SUCURSAL', 'numero' => '1');
        $cabecera[]       = array('valor' => 'COMENTARIO', 'numero' => '1');
        $cabecera[]       = array('valor' => 'TOTAL', 'numero' => '1');

        $tituloDetalle = $this->tituloDetalle;
        $tituloAnulacion = $this->tituloAnulacion;
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
            return view($this->folderview.'.list')->with(compact('lista', 'paginacion', 'inicio', 'ingresos_credito', 'ingresos_repartidor', 'total_ingresos', 'egresos_repartidor', 'vueltos_repartidor','saldo_repartidor','fin', 'entidad', 'cabecera', 'tituloAnulacion', 'tituloDetalle', 'ruta'));
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
        $entidad          = 'Turnorepartidor';
        $title            = $this->tituloAdmin;
        $tituloMontoVuelto = $this->tituloMontoVuelto;
        $tituloDescargaDinero = $this->tituloDescargaDinero;
        $tituloCierreTurno = $this->tituloCierreTurno;
        $ruta             = $this->rutas;
        $turnos_iniciados = Turnorepartidor::where('estado','I')->get();
        // TRABAJADORES EN TURNO
        $empleados = array();
        foreach ($turnos_iniciados as $key => $value) {
            $trabajador = Person::find($value->trabajador_id);
            array_push($empleados, $trabajador);
        }
        $cboSucursal      = Sucursal::pluck('nombre', 'id')->all();
        return view($this->folderview.'.admin')->with(compact('entidad', 'turnos_iniciados', 'cboSucursal','title', 'tituloCierreTurno', 'tituloMontoVuelto', 'tituloDescargaDinero','ruta'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function vuelto(Request $request)
    {
        $listar       = Libreria::getParam($request->input('listar'), 'NO');
        $entidad      = 'Turnorepartidor';
        $movimiento   = null;
        $formData     = array('turno.store');
        $formData     = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');

        $num_caja   = null;

        $turnos_iniciados = Turnorepartidor::join('person', 'person.id', '=', 'turno_repartidor.trabajador_id')
                                            ->where('turno_repartidor.estado','I')
                                            ->where('person.sucursal_id', 1)
                                            ->get();
        // TRABAJADORES EN TURNO
        $trabajadores_iniciados = array();
        foreach ($turnos_iniciados as $key => $value) {
            $trabajador = Person::find($value->trabajador_id);
            array_push($trabajadores_iniciados, $trabajador);
        }
        $boton        = 'Guardar';
        $cboSucursal      = Sucursal::pluck('nombre', 'id')->all();
        return view($this->folderview.'.montovuelto')->with(compact('persona_id' , 'cboSucursal', 'trabajadores_iniciados' ,'num_caja', 'movimiento', 'formData', 'entidad', 'boton', 'listar'));
    }

    public function descargadinero(Request $request)
    {
        $listar       = Libreria::getParam($request->input('listar'), 'NO');
        $entidad      = 'Turnorepartidor';
        $movimiento   = null;
        $formData     = array('turno.store');
        $formData     = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');

        $num_caja   = null;

        $turnos_iniciados = Turnorepartidor::join('person', 'person.id', '=', 'turno_repartidor.trabajador_id')
                                            ->where('turno_repartidor.estado','I')
                                            ->where('person.sucursal_id', 1)
                                            ->get();
        // TRABAJADORES EN TURNO
        $trabajadores_iniciados = array();
        foreach ($turnos_iniciados as $key => $value) {
            $trabajador = Person::find($value->trabajador_id);
            array_push($trabajadores_iniciados, $trabajador);
        }
        $boton        = 'Guardar';
        $cboSucursal      = Sucursal::pluck('nombre', 'id')->all();
        return view($this->folderview.'.descargadinero')->with(compact('persona_id' , 'cboSucursal', 'trabajadores_iniciados' ,'num_caja', 'movimiento', 'formData', 'entidad', 'boton', 'listar'));
    }

    public function cierre(Request $request)
    {
        $listar       = Libreria::getParam($request->input('listar'), 'NO');
        $entidad      = 'Turnorepartidor';
        $movimiento   = null;
        $formData     = array('turno.store');
        $formData     = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');

        $num_caja   = null;

        $turnos_iniciados = Turnorepartidor::join('person', 'person.id', '=', 'turno_repartidor.trabajador_id')
                                            ->where('turno_repartidor.estado','I')
                                            ->where('person.sucursal_id', 1)
                                            ->get();
        // TRABAJADORES EN TURNO
        $trabajadores_iniciados = array();
        foreach ($turnos_iniciados as $key => $value) {
            $trabajador = Person::find($value->trabajador_id);
            array_push($trabajadores_iniciados, $trabajador);
        }
        $boton        = 'Guardar';
        $cboSucursal      = Sucursal::pluck('nombre', 'id')->all();
        return view($this->folderview.'.cerrarturno')->with(compact('persona_id' , 'cboSucursal', 'trabajadores_iniciados' ,'num_caja', 'movimiento', 'formData', 'entidad', 'boton', 'listar'));
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
        $reglas     = array('num_caja' => 'required|numeric',
                            'fecha'      => 'required',
                            'concepto_id'   => 'required',
                            'persona_id' => 'required',
                            'monto'      => 'required|numeric',
                        );
        $mensajes   = array();
        $validacion = Validator::make($request->all(), $reglas, $mensajes);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $error = DB::transaction(function() use($request){
            $movimiento       = new Movimiento();
            $movimiento->tipomovimiento_id = 1;
            $movimiento->concepto_id    = $request->input('concepto_id');
            $movimiento->num_caja   = $request->input('num_caja');
            $movimiento->total             = $request->input('monto');
            $movimiento->subtotal          = $request->input('monto');
            $movimiento->estado         = 1;
            $trabajador = Person::find($request->input('persona_id'));
            if($request->input('concepto_id') == 13 || $request->input('concepto_id') == 14 || $request->input('concepto_id') == 12){
                if($trabajador->tipo_persona == "T"){
                    $movimiento->trabajador_id     = $request->input('persona_id');
                }
            }else{
                $movimiento->persona_id     = $request->input('persona_id');
            }
            $user           = Auth::user();
            $movimiento->usuario_id     = $user->id;
            $movimiento->sucursal_id   = $request->input('sucursal_id');
            $movimiento->comentario     = strtoupper($request->input('comentario'));
            $movimiento->save();

            if($request->input('concepto_id') == 13 || $request->input('concepto_id') == 14  || $request->input('concepto_id') == 12){

                $sucursal_id = $request->input('sucursal_id');
                
                $maxapertura = Movimiento::where('concepto_id', 1)
                ->where('sucursal_id', "=", $sucursal_id)
                ->where('estado', "=", 1)
                ->max('id');
                
                $apertura = Movimiento::find($maxapertura);
                
                $turno_repartidor_id = Turnorepartidor::where('trabajador_id', $request->input('persona_id'))
                                                    ->where('estado', 'I')
                                                    ->max("id");

                $turno_repartidor = Turnorepartidor::find($turno_repartidor_id);

                if(is_null($turno_repartidor)){
                    $turno_repartidor = new Turnorepartidor();
                    $turno_repartidor->estado    = "I";
                    $turno_repartidor->apertura_id = $maxapertura;
                    //$turno_repartidor->vuelto_id = $movimiento->id;
                    $turno_repartidor->trabajador_id = $request->input('persona_id');
                    $turno_repartidor->save();
                }else{
                    if($request->input('concepto_id') == 14 ){
                        $turno_repartidor->fin    = date('Y-m-d H:i:s');
                        $turno_repartidor->estado    = "C";
                        $turno_repartidor->save();
                    }
                }

                $detalle_turno_pedido =  new Detalleturnopedido();
                $detalle_turno_pedido->pedido_id = $movimiento->id;
                $detalle_turno_pedido->turno_id = $turno_repartidor_id;
                $detalle_turno_pedido->save();
            }
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
    public function detalle(Request $request, $id)
    {
        $existe = Libreria::verificarExistencia($id, 'movimiento');
        if ($existe !== true) {
            return $existe;
        }
        $listar   = Libreria::getParam($request->input('listar'), 'NO');
        $pedido = Movimiento::find($id);
        if($pedido->tipomovimiento_id == 5){
            $pedido = Movimiento::find($pedido->venta_id);
            $detalles = Detalleventa::where('venta_id',$pedido->id)->get();
        }else{
            $detalles = Detalleventa::where('venta_id',$pedido->id)->get();
        }
        $entidad  = 'Turnorepartidor';
        $formData = array('turno.store', $id);
        $formData = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Modificar';
        return view($this->folderview.'.detalle')->with(compact('pedido', 'detalles','formData', 'entidad', 'boton', 'listar'));
    }

    public function cargarnumerocaja(Request $request){
        $sucursal_id  = $request->input('sucursal_id');
        $num_caja   = Movimiento::where('sucursal_id', '=' , $sucursal_id)
        //->where('estado',1)
        ->max('num_caja') + 1;
        return $num_caja;
    }

    public function cargarempleados(Request $request){
        $sucursal_id  = $request->input('sucursal_id');

        $turnos_iniciados = Turnorepartidor::join('person', 'person.id', '=', 'turno_repartidor.trabajador_id')
                                            ->where('turno_repartidor.estado','I')
                                            ->where('person.sucursal_id', '=', $sucursal_id)
                                            ->get();

        // TRABAJADORES EN TURNO
        $trabajadores_iniciados = array();
        foreach ($turnos_iniciados as $key => $value) {
            $trabajador = Person::find($value->trabajador_id);
            array_push($trabajadores_iniciados, $trabajador);
        }

        return $trabajadores_iniciados;
    }

    public function generarSaldoRepartidor(Request $request){
        $persona_id         = Libreria::getParam($request->input('persona_id'));
        $ingresos_repartidor = 0.00;
        $egresos_repartidor = 0.00;
        $saldo_repartidor = 0.00;
        $turno_repartidor_max_id = Turnorepartidor::where('trabajador_id', $persona_id)->max("id");
        $turno_repartidor = Turnorepartidor::find($turno_repartidor_max_id);
        if($turno_repartidor != null){
            $resultado        = Detalleturnopedido::where('turno_id', '=', $turno_repartidor->id);
            $lista            = $resultado->get();

            $ingresos_repartidor = Detalleturnopedido::where('turno_id', '=', $turno_repartidor->id)
                                                        ->join('movimiento', 'detalle_turno_pedido.pedido_id', '=', 'movimiento.id')
                                                        ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                                        ->where('estado',1)
                                                        ->where(function($subquery)
                                                            {
                                                                $subquery->where('concepto.id','=', 3)->orwhere('concepto.id','=', 12)->orwhere('concepto.id','=', 15)->orwhere('concepto.id','=', 16);
                                                            })
                                                        ->sum('total');

            $egresos_repartidor = Detalleturnopedido::where('turno_id', '=', $turno_repartidor->id)
                                                        ->join('movimiento', 'detalle_turno_pedido.pedido_id', '=', 'movimiento.id')
                                                        ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                                        ->where('estado',1)
                                                        ->where(function($subquery)
                                                            {
                                                                $subquery->where('concepto.id','=', 13)->orwhere('concepto.id','=', 14);
                                                            })
                                                        ->sum('total');

            $saldo_repartidor = $ingresos_repartidor - $egresos_repartidor;

        }
        return $saldo_repartidor;
    }

    public function destroy(Request $request, $id)
    {
        $existe = Libreria::verificarExistencia($id, 'movimiento');
        if ($existe !== true) {
            return $existe;
        }
        $reglas     = array('motivo' => 'required|max:300');
        $mensajes   = array();
        $validacion = Validator::make($request->all(), $reglas, $mensajes);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $error = DB::transaction(function() use($request, $id){
            $movimiento = Movimiento::find($id);
            $movimiento->estado = 0;
            $movimiento->comentario_anulado  = strtoupper($request->input('motivo'));  
            $movimiento->save();

            if($movimiento->concepto_id == 15){
                $detalle_turno = Detalleturnopedido::where('pedido_id',$movimiento->id)->first();
                $turno = Turnorepartidor::find($detalle_turno->turno_id);
                $turno->delete();
            }

            if($movimiento->venta_id != null){
                $movimientoventa = Movimiento::find($movimiento->venta_id);
                $movimientoventa->estado = 0;
                $movimientoventa->comentario_anulado  = strtoupper($request->input('motivo'));  
                $movimientoventa->save();
            }
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
        $existe = Libreria::verificarExistencia($id, 'movimiento');
        if ($existe !== true) {
            return $existe;
        }
        $listar = "NO";
        if (!is_null(Libreria::obtenerParametro($listarLuego))) {
            $listar = $listarLuego;
        }
        $modelo   = Movimiento::find($id);
        $entidad  = 'Turnorepartidor';
        $formData = array('route' => array('turno.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Anular';
        $mensaje  = '<blockquote><p class="text-danger">¿Está seguro de anular el registro?</p></blockquote>';
        return view('app.caja.confirmarAnular')->with(compact( 'mensaje' ,'modelo', 'formData', 'entidad', 'boton', 'listar'));
    }

}
