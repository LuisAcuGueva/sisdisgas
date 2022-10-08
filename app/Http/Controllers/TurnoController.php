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
use App\Detallemovalmacen;
use App\Detallepagos;
use App\Kardex;
use App\Stock;
use App\Metodopago;
use App\Producto;
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
    protected $tituloGastos= 'Gastos extras del repartidor';
    protected $tituloCierreTurno = 'Cerrar turno de repartidor';
    protected $tituloDetalle  = 'Detalle de pedido';
    protected $tituloAnulacion  = 'Anular pedido';
    protected $rutas           = array('detalle' => 'turno.detalle', 
            'vuelto'     => 'turno.vuelto', 
            'gastos'     => 'turno.gastos', 
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
        $gastos_repartidor = 0.00;
        $saldo_repartidor = 0.00;
        if($turno_id != null){
            $resultado        = Detalleturnopedido::where('turno_id', '=', $turno_id)
                                                ->join('movimiento', 'detalle_turno_pedido.pedido_id', '=', 'movimiento.id')
                                                ->orderby('fecha', 'DESC');
            $lista            = $resultado->get();

            //* INGRESOS DE PEDIDOS
            $ingresos_repartidor = Detalleturnopedido::where('turno_id', '=', $turno_id)
                                                        ->join('movimiento', 'detalle_turno_pedido.pedido_id', '=', 'movimiento.id')
                                                        ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                                        ->where('estado',1)
                                                        ->where('movimiento.balon_a_cuenta',0)
                                                        ->where(function($subquery)
                                                            {
                                                                $subquery->where('concepto.id','=', 3);
                                                            })
                                                        ->sum('total');

            //* INGRESOS DE PEDIDOS A CRÉDITO - PEDIDO ACTUAL                                                   
            $ingresos_repartidor_credito = Detalleturnopedido::where('turno_id', '=', $turno_id)
                                                        ->join('movimiento', 'detalle_turno_pedido.pedido_id', '=', 'movimiento.id')
                                                        ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                                        ->where('estado',1)
                                                        ->where('movimiento.balon_a_cuenta',1)
                                                        ->where(function($subquery)
                                                            {
                                                                $subquery->where('concepto.id','=', 3);
                                                            })
                                                        ->sum('total_pagado');
                                                        
            $ingresos_repartidor += $ingresos_repartidor_credito;

            //* VUELTOS DE LOS PEDIDOS
            $vueltos_pedidos_repartidor = Detalleturnopedido::where('turno_id', '=', $turno_id)
                                                        ->join('movimiento', 'detalle_turno_pedido.pedido_id', '=', 'movimiento.id')
                                                        ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                                        ->where('estado',1)
                                                        ->where(function($subquery)
                                                            {
                                                                $subquery->where('concepto.id','=', 3);
                                                            })
                                                        ->sum('vuelto');

            //ToDo: Sacar total por metodo de pago    
            $metodos_pago = Metodopago::all();
            $ingresos_metodos = array();
            foreach ($metodos_pago as $key => $metodo_pago) {
                $ingresos_metodos[$metodo_pago->id] = Detallepagos::join('movimiento', 'detalle_pagos.pedido_id', '=', 'movimiento.id')
                                                        ->join('detalle_turno_pedido', 'detalle_turno_pedido.pedido_id', '=', 'movimiento.id')
                                                        ->where('detalle_turno_pedido.turno_id', '=', $turno_id)
                                                        ->where('movimiento.estado',1)
                                                        ->where('detalle_pagos.credito',0) //* Pagados al repartidor
                                                        ->where('detalle_pagos.metodo_pago_id',$metodo_pago->id)
                                                        ->where(function($subquery)
                                                            {
                                                                $subquery->where('movimiento.concepto_id','=', 3);
                                                            })
                                                        ->sum('detalle_pagos.monto');
            }

            if ($ingresos_metodos[1] > 0) {
                $ingresos_metodos[1] -= $vueltos_pedidos_repartidor;
            }

            //* INGRESOS DE PEDIDOS A CRÉDITO - PEDIDO PASADO                             
            $ingresos_credito = Detalleturnopedido::where('turno_id', '=', $turno_id)
                                                        ->join('movimiento', 'detalle_turno_pedido.pedido_id', '=', 'movimiento.id')
                                                        ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                                        ->where('estado',1)
                                                        ->where(function($subquery)
                                                            {
                                                                $subquery->where('concepto.id','=', 16);
                                                            })
                                                        ->sum('total');

            //* VUELTOS PARA REPARTIDOR
            $vueltos_repartidor = Detalleturnopedido::where('turno_id', '=', $turno_id)
                                                        ->join('movimiento', 'detalle_turno_pedido.pedido_id', '=', 'movimiento.id')
                                                        ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                                        ->where('estado',1)
                                                        ->where(function($subquery)
                                                            {
                                                                $subquery->where('concepto.id','=', 12)->orwhere('concepto.id','=', 15);
                                                            })
                                                        ->sum('total');

            //* EGRESOS A CAJA
            $egresos_repartidor = Detalleturnopedido::where('turno_id', '=', $turno_id)
                                                        ->join('movimiento', 'detalle_turno_pedido.pedido_id', '=', 'movimiento.id')
                                                        ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                                        ->where('estado',1)
                                                        ->where(function($subquery)
                                                            {
                                                                $subquery->where('concepto.id','=', 13)->orwhere('concepto.id','=', 14);
                                                            })
                                                        ->sum('total');

            //* GASTOS DE REPARTIDOR
            $gastos_repartidor = Detalleturnopedido::where('turno_id', '=', $turno_id)
                                                        ->join('movimiento', 'detalle_turno_pedido.pedido_id', '=', 'movimiento.id')
                                                        ->where('estado',1)
                                                        ->where('tipomovimiento_id',6)
                                                        ->sum('total');

            round($ingresos_repartidor,2);
            round($ingresos_credito,2);
            round($vueltos_repartidor,2);
            round($egresos_repartidor,2);
            round($gastos_repartidor,2);

            $total_ingresos = $ingresos_repartidor + $vueltos_repartidor + $ingresos_credito;

            $saldo_repartidor = ($ingresos_metodos[1] + $vueltos_repartidor + $ingresos_credito) - ($egresos_repartidor + $gastos_repartidor);

            round($saldo_repartidor,2);

        }
        $cabecera         = array();
        $cabecera[]       = array('valor' => 'VER', 'numero' => '1');
        $cabecera[]       = array('valor' => 'ANUL', 'numero' => '1');
        $cabecera[]       = array('valor' => 'FECHA Y HORA', 'numero' => '1');
        $cabecera[]       = array('valor' => 'CONCEPTO', 'numero' => '1');
        $cabecera[]       = array('valor' => 'NRO DOC', 'numero' => '1');
        $cabecera[]       = array('valor' => 'CLIENTE', 'numero' => '1');
        $cabecera[]       = array('valor' => 'DIRECCIÓN', 'numero' => '1');
        $cabecera[]       = array('valor' => 'VALE', 'numero' => '1');
        $cabecera[]       = array('valor' => 'SUCURSAL', 'numero' => '1');
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
            return view($this->folderview.'.list')->with(compact('lista', 'paginacion', 'inicio', 'ingresos_metodos', 'vueltos_pedidos_repartidor', 'ingresos_credito', 'ingresos_repartidor', 'gastos_repartidor','total_ingresos', 'egresos_repartidor', 'vueltos_repartidor','saldo_repartidor','fin', 'entidad', 'cabecera', 'tituloAnulacion', 'tituloDetalle', 'ruta'));
        }
        return view($this->folderview.'.list')->with(compact('lista', 'entidad'));
    }

    public function index()
    {
        $entidad          = 'Turnorepartidor';
        $title            = $this->tituloAdmin;
        $tituloMontoVuelto = $this->tituloMontoVuelto;
        $tituloDescargaDinero = $this->tituloDescargaDinero;
        $tituloCierreTurno = $this->tituloCierreTurno;
        $tituloGastos = $this->tituloGastos;
        $ruta             = $this->rutas;
        $turnos_iniciados = Turnorepartidor::where('estado','I')->get();
        // TRABAJADORES EN TURNO
        $empleados = array();
        foreach ($turnos_iniciados as $key => $value) {
            $trabajador = Person::find($value->trabajador_id);
            array_push($empleados, $trabajador);
        }
        $cboSucursal      = Sucursal::pluck('nombre', 'id')->all();
        return view($this->folderview.'.admin')->with(compact('entidad', 'turnos_iniciados', 'cboSucursal','title', 'tituloCierreTurno', 'tituloGastos','tituloMontoVuelto', 'tituloDescargaDinero','ruta'));
    }

    public function vuelto(Request $request)
    {
        $listar       = Libreria::getParam($request->input('listar'), 'NO');
        $entidad      = 'Turnorepartidor';
        $movimiento   = null;
        $formData     = array('turno.store');
        $formData     = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $num_caja   = null;
        $boton        = 'Guardar';
        $cboSucursal      = Sucursal::pluck('nombre', 'id')->all();
        return view($this->folderview.'.montovuelto')->with(compact('persona_id' , 'cboSucursal','num_caja', 'movimiento', 'formData', 'entidad', 'boton', 'listar'));
    }

    public function descargadinero(Request $request)
    {
        $listar       = Libreria::getParam($request->input('listar'), 'NO');
        $entidad      = 'Turnorepartidor';
        $movimiento   = null;
        $formData     = array('turno.store');
        $formData     = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $num_caja   = null;
        $boton        = 'Guardar';
        $cboSucursal      = Sucursal::pluck('nombre', 'id')->all();
        return view($this->folderview.'.descargadinero')->with(compact('persona_id' , 'cboSucursal','num_caja', 'movimiento', 'formData', 'entidad', 'boton', 'listar'));
    }

    public function gastos(Request $request)
    {
        $listar       = Libreria::getParam($request->input('listar'), 'NO');
        $entidad      = 'Turnorepartidor';
        $movimiento   = null;
        $formData     = array('turno.store');
        $formData     = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $num_caja   = null;
        $concepto5 = Concepto::find(5);
        $concepto7 = Concepto::find(7);
        $concepto8 = Concepto::find(8);
        $concepto9 = Concepto::find(9);
        $cboConcepto = array(
            "9" => $concepto9->concepto,
            "5" => $concepto5->concepto,
            "7" => $concepto7->concepto,
            "8" => $concepto8->concepto,
        );
        $boton        = 'Guardar';
        $cboSucursal      = Sucursal::pluck('nombre', 'id')->all();
        return view($this->folderview.'.gastos')->with(compact('persona_id' , 'cboSucursal', 'cboConcepto','num_caja', 'movimiento', 'formData', 'entidad', 'boton', 'listar'));
    }

    public function cierre(Request $request)
    {
        $listar       = Libreria::getParam($request->input('listar'), 'NO');
        $entidad      = 'Turnorepartidor';
        $movimiento   = null;
        $formData     = array('turno.store');
        $formData     = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $num_caja   = null;
        $boton        = 'Guardar';
        $cboSucursal      = Sucursal::pluck('nombre', 'id')->all();
        return view($this->folderview.'.cerrarturno')->with(compact('persona_id' , 'cboSucursal' ,'num_caja', 'movimiento', 'formData', 'entidad', 'boton', 'listar'));
    }

    public function store(Request $request)
    {
        $listar     = Libreria::getParam($request->input('listar'), 'NO');
        $reglas     = array(
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
            if($request->input('concepto_id') == 13 || $request->input('concepto_id') == 14 || $request->input('concepto_id') == 12){
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
            }else{
                $movimiento       = new Movimiento();
                $movimiento->tipomovimiento_id = 6;
                $movimiento->concepto_id    = $request->input('concepto_id');
                $movimiento->total             = $request->input('monto');
                $movimiento->subtotal          = $request->input('monto');
                $movimiento->estado         = 1;
                $movimiento->trabajador_id     = $request->input('persona_id');
                $user           = Auth::user();
                $movimiento->usuario_id     = $user->id;
                $movimiento->sucursal_id   = $request->input('sucursal_id');
                $movimiento->comentario     = strtoupper($request->input('comentario'));
                $movimiento->save();

                $turno_repartidor_id = Turnorepartidor::where('trabajador_id', $request->input('persona_id'))
                                                        ->where('estado', 'I')
                                                        ->max("id");

                $turno_repartidor = Turnorepartidor::find($turno_repartidor_id);
                
                $detalle_turno_pedido =  new Detalleturnopedido();
                $detalle_turno_pedido->pedido_id = $movimiento->id;
                $detalle_turno_pedido->turno_id = $turno_repartidor_id;
                $detalle_turno_pedido->save();
            }
        });
        return is_null($error) ? "OK" : $error;
    }

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
        }
        $detalles = Detallemovalmacen::where('movimiento_id',$pedido->id)->get();
        $detallespago = Detallepagos::where('pedido_id', '=', $pedido->id)->where('credito',0)->get();  
        $detallespago_credito = Detallepagos::where('pedido_id', '=', $pedido->id)->where('credito',1)->get();
        $total_pagado = Detallepagos::where('pedido_id', '=', $pedido->id)->sum('monto');  
        $total_productos = Detallemovalmacen::where('movimiento_id',$pedido->id)->sum('subtotal');
        $entidad  = 'Turnorepartidor';
        $formData = array('turno.store', $id);
        $formData = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Modificar';
        return view($this->folderview.'.detalle')->with(compact('pedido', 'total_pagado', 'total_productos','detallespago', 'detallespago_credito', 'detalles','formData', 'entidad', 'boton', 'listar'));
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

            $gastos_repartidor = Detalleturnopedido::where('turno_id', '=', $turno_repartidor->id)
                                                        ->join('movimiento', 'detalle_turno_pedido.pedido_id', '=', 'movimiento.id')
                                                        ->where('estado',1)
                                                        ->where('tipomovimiento_id',6)
                                                        ->sum('total');

            $saldo_repartidor = $ingresos_repartidor - $egresos_repartidor - $gastos_repartidor;

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

            $kardexs = Kardex::rightjoin('detalle_mov_almacen', 'kardex.detalle_mov_almacen_id', '=', 'detalle_mov_almacen.id')
                            ->where('detalle_mov_almacen.movimiento_id', $id)
                            ->get();

            foreach ($kardexs as $key => $value) {
                $stock = Stock::where('producto_id', $value->producto_id )->where('sucursal_id', $movimiento->sucursal_id)->first();
                
                if( $value->cantidad_envase == 0 || $value->cantidad_envase == null ){
                    $cantidad_envase = 0;
                }else{
                    $cantidad_envase = $value->cantidad_envase;
                }

                //actualizar stock
                if( $value->tipo == "I"){
                    $stock->cantidad -= ($value->cantidad + $cantidad_envase);
                }else{
                    $stock->cantidad += ($value->cantidad + $cantidad_envase);
                }

                //actualizar cantidad de balones
                $producto = Producto::find($value->producto_id);
                if($producto->recargable == 1){
                    if( $value->tipo == "I"){
                        $stock->envases_total -= $cantidad_envase;
                        $stock->envases_llenos -= ($value->cantidad + $cantidad_envase);
                        $stock->envases_vacios += $value->cantidad;
                    }else{
                        $stock->envases_total += $cantidad_envase;
                        $stock->envases_llenos += ($value->cantidad + $cantidad_envase);
                        $stock->envases_vacios -=  $value->cantidad;
                    }
                }

                $stock->save();

                $kardexs_producto = Kardex::join('detalle_mov_almacen', 'kardex.detalle_mov_almacen_id', '=', 'detalle_mov_almacen.id')
                                            ->where('detalle_mov_almacen.producto_id', '=', $value->producto_id)
                                            ->where('detalle_mov_almacen.id','>', $value->id)
                                            ->get();
                                
                foreach ($kardexs_producto as $key => $value2) {

                    $kardex_edit = Kardex::where('detalle_mov_almacen_id', $value2->id )->first();

                    //actualizar stocks en kardex
                    if( $value->tipo == "I"){
                        $kardex_edit->stock_anterior -= ($value->cantidad + $cantidad_envase);
                        $kardex_edit->stock_actual -= ($value->cantidad + $cantidad_envase);
                    }else{
                        $kardex_edit->stock_anterior += ($value->cantidad + $cantidad_envase);
                        $kardex_edit->stock_actual += ($value->cantidad + $cantidad_envase);
                    }

                    $kardex_edit->save();

                }

            }

            if($movimiento->concepto_id == 15){ // monto vuelto al iniciar turno
                $detalle_turno = Detalleturnopedido::where('pedido_id',$movimiento->id)->first();
                $turno = Turnorepartidor::find($detalle_turno->turno_id);
                $turno->delete();
            }

            if($movimiento->venta_id != null){ // pedido

                $pagos = Detallepagos::where('pedido_id', $movimiento->venta_id)
                                        ->leftjoin('movimiento', 'detalle_pagos.pago_id', '=', 'movimiento.id')                       
                                        ->where('movimiento.estado', 1)
                                        ->get();

                if( count($pagos) == 1 ) { //pedido a crédito con un pago

                    $movimientoventa = Movimiento::find($movimiento->venta_id);
                    $movimientoventa->estado = 0;
                    $movimientoventa->comentario_anulado  = strtoupper($request->input('motivo'));  
                    $movimientoventa->save();

                    $kardexs = Kardex::join('detalle_mov_almacen', 'kardex.detalle_mov_almacen_id', '=', 'detalle_mov_almacen.id')
                            ->where('detalle_mov_almacen.movimiento_id', $movimientoventa->id)
                            ->get();

                    foreach ($kardexs as $key => $value) {
                        $stock = Stock::where('producto_id', $value->producto_id )->where('sucursal_id', $movimientoventa->sucursal_id)->first();
                        
                        if( $value->cantidad_envase == 0 || $value->cantidad_envase == null ){
                            $cantidad_envase = 0;
                        }else{
                            $cantidad_envase = $value->cantidad_envase;
                        }
        
                        //actualizar stock
                        if( $value->tipo == "I"){
                            $stock->cantidad -= ($value->cantidad + $cantidad_envase);
                        }else{
                            $stock->cantidad += ($value->cantidad + $cantidad_envase);
                        }
        
                        //actualizar cantidad de balones
                        $producto = Producto::find($value->producto_id);
                        if($producto->recargable == 1){
                            if( $value->tipo == "I"){
                                $stock->envases_total -= $cantidad_envase;
                                $stock->envases_llenos -= ($value->cantidad + $cantidad_envase);
                                $stock->envases_vacios += $value->cantidad;
                            }else{
                                $stock->envases_total += $cantidad_envase;
                                $stock->envases_llenos += ($value->cantidad + $cantidad_envase);
                                $stock->envases_vacios -=  $value->cantidad;
                            }
                        }

                        $stock->save();

                        $kardexs_producto = Kardex::join('detalle_mov_almacen', 'kardex.detalle_mov_almacen_id', '=', 'detalle_mov_almacen.id')
                                            ->where('detalle_mov_almacen.producto_id', '=', $value->producto_id)
                                            ->where('detalle_mov_almacen.id','>', $value->id)
                                            ->get();
                
                        foreach ($kardexs_producto as $key => $value2) {

                            $kardex_edit = Kardex::where('detalle_mov_almacen_id', $value2->id )->first();
        
                            //actualizar stocks en kardex
                            if( $value->tipo == "I"){
                                $kardex_edit->stock_anterior -= ($value->cantidad + $cantidad_envase);
                                $kardex_edit->stock_actual -= ($value->cantidad + $cantidad_envase);
                            }else{
                                $kardex_edit->stock_anterior += ($value->cantidad + $cantidad_envase);
                                $kardex_edit->stock_actual += ($value->cantidad + $cantidad_envase);
                            }
        
                            $kardex_edit->save();
        
                        }
                    }

                }
            }

            $movimiento->save();

        });
        return is_null($error) ? "OK" : $error;
    }

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
