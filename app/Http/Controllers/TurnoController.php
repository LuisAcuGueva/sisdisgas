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

            //* Restar vueltos a Ingresos efectivo
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

            //* VUELTOS DE LOS PEDIDOS
            $vueltos_pedidos_repartidor = Detalleturnopedido::where('turno_id', '=', $turno_repartidor->id)
                                                        ->join('movimiento', 'detalle_turno_pedido.pedido_id', '=', 'movimiento.id')
                                                        ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                                        ->where('estado',1)
                                                        ->where(function($subquery)
                                                            {
                                                                $subquery->where('concepto.id','=', 3);
                                                            })
                                                        ->sum('vuelto');

            $ingresos_efectivo = Detallepagos::join('movimiento', 'detalle_pagos.pedido_id', '=', 'movimiento.id')
                                            ->join('detalle_turno_pedido', 'detalle_turno_pedido.pedido_id', '=', 'movimiento.id')
                                            ->where('detalle_turno_pedido.turno_id', '=', $turno_repartidor->id)
                                            ->where('movimiento.estado',1)
                                            ->where('detalle_pagos.credito',0) //* Pagados al repartidor
                                            ->where('detalle_pagos.metodo_pago_id', 1) //* Efectivo
                                            ->where(function($subquery)
                                                {
                                                    $subquery->where('movimiento.concepto_id','=', 3);
                                                })
                                            ->sum('detalle_pagos.monto');

            $ingresos_efectivo -= $vueltos_pedidos_repartidor;


            //* VUELTOS PARA REPARTIDOR
            $vueltos_repartidor = Detalleturnopedido::where('turno_id', '=', $turno_repartidor->id)
                                                        ->join('movimiento', 'detalle_turno_pedido.pedido_id', '=', 'movimiento.id')
                                                        ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                                        ->where('estado',1)
                                                        ->where(function($subquery)
                                                            {
                                                                $subquery->where('concepto.id','=', 12)->orwhere('concepto.id','=', 15);
                                                            })
                                                        ->sum('total');

            //* INGRESOS DE PEDIDOS A CRÉDITO - PEDIDO PASADO                             
            $ingresos_credito = Detalleturnopedido::where('turno_id', '=', $turno_repartidor->id)
                                                        ->join('movimiento', 'detalle_turno_pedido.pedido_id', '=', 'movimiento.id')
                                                        ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                                        ->where('estado',1)
                                                        ->where(function($subquery)
                                                            {
                                                                $subquery->where('concepto.id','=', 16);
                                                            })
                                                        ->sum('total');

            //* EGRESOS A CAJA
            $egresos_repartidor = Detalleturnopedido::where('turno_id', '=', $turno_repartidor->id)
                                                        ->join('movimiento', 'detalle_turno_pedido.pedido_id', '=', 'movimiento.id')
                                                        ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                                        ->where('estado',1)
                                                        ->where(function($subquery)
                                                            {
                                                                $subquery->where('concepto.id','=', 13)->orwhere('concepto.id','=', 14);
                                                            })
                                                        ->sum('total');

            //* GASTOS DE REPARTIDOR
            $gastos_repartidor = Detalleturnopedido::where('turno_id', '=', $turno_repartidor->id)
                                                        ->join('movimiento', 'detalle_turno_pedido.pedido_id', '=', 'movimiento.id')
                                                        ->where('estado',1)
                                                        ->where('tipomovimiento_id',6)
                                                        ->sum('total');

            $saldo_repartidor = ($ingresos_efectivo + $vueltos_repartidor + $ingresos_credito) - ($egresos_repartidor + $gastos_repartidor);
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

            if($movimiento->concepto_id == 15){ //* Monto vuelto al iniciar turno
                $detalle_turno = Detalleturnopedido::where('pedido_id',$movimiento->id)->first();
                $turno = Turnorepartidor::find($detalle_turno->turno_id);
                $turno->delete();
            }

            if($movimiento->concepto_id == 16){ //* Pago de deuda de pédido a crédito
                $pago_credito = Detallepagos::where('pago_credito_id',$movimiento->id)->first();
                $pago_credito->delete();
            }
            
            //ToDo: Anular pago de deuda de pedido a credito

            //* Si es venta en sucursal -- venta repartidor
            if($movimiento->concepto_id == 3){ //* Si es Pedido, hacer devolucion almacen
                //ToDo: Crear movimiento devolucion
                $devolucion = new Movimiento();
                $devolucion->tipomovimiento_id = 7; //* Devolucion
                $devolucion->estado = 1;
                $devolucion->venta_id = $movimiento->id;
                $devolucion->sucursal_id = $movimiento->sucursal_id;
                $devolucion->usuario_id = $movimiento->usuario_id;
                $devolucion->save();

                //ToDo: Crear detalle mov almacen

                $det_almacen = Detallemovalmacen::where('movimiento_id', $movimiento->id)->get();

                foreach ($det_almacen as $key => $det_alm_anul) {
                    $det_alm_devol = new Detallemovalmacen();
                    $det_alm_devol->movimiento_id = $devolucion->id; //* id devolucion
                    $det_alm_devol->producto_id = $det_alm_anul->producto_id;
                    $det_alm_devol->cantidad = $det_alm_anul->cantidad;
                    $det_alm_devol->precio = $det_alm_anul->precio;
                    $det_alm_devol->subtotal = $det_alm_anul->subtotal;
                    $det_alm_devol->cantidad_envase = $det_alm_anul->cantidad_envase;
                    $det_alm_devol->precio_envase = $det_alm_anul->precio_envase;
                    $det_alm_devol->save();

                    $stock = Stock::where('producto_id', $det_alm_devol->producto_id)->where('sucursal_id', $movimiento->sucursal_id)->first();
                    
                    $cantidad_envase = ($det_alm_devol->cantidad_envase == 0 || $det_alm_devol->cantidad_envase == null) ? 0 : $det_alm_devol->cantidad_envase;

                    //* Aumentar stock
                    $stock->cantidad += ($det_alm_devol->cantidad + $cantidad_envase);

                    //* Actualizar cantidad de balones en stock
                    $producto = Producto::find($det_alm_devol->producto_id);
                    if($producto->recargable == 1){
                        $stock->envases_total += $cantidad_envase;
                        $stock->envases_llenos += ($det_alm_devol->cantidad + $cantidad_envase);
                        $stock->envases_vacios -= $det_alm_devol->cantidad;
                    }
                    $stock->save();

                    $kardex_anul = Kardex::join('detalle_mov_almacen', 'kardex.detalle_mov_almacen_id', '=', 'detalle_mov_almacen.id')
                                        ->where('detalle_mov_almacen.producto_id', '=', $det_alm_devol->producto_id)
                                        ->where('kardex.sucursal_id','=', $movimiento->sucursal_id)
                                        ->orderBy('kardex.id', 'desc')
                                        ->first();

                    $kardex_devol = new Kardex();
                    $kardex_devol->detalle_mov_almacen_id = $det_alm_devol->id;
                    $kardex_devol->tipo = 'I';
                    $kardex_devol->sucursal_id = $kardex_anul->sucursal_id;
                    $kardex_devol->stock_anterior = $kardex_anul->stock_actual;
                    $kardex_devol->stock_actual = $kardex_anul->stock_actual + ($det_alm_devol->cantidad + $cantidad_envase);
                    $kardex_devol->cantidad = $kardex_anul->cantidad;
                    $kardex_devol->precio_venta = $kardex_anul->precio_venta;
                    $kardex_devol->cantidad_envase = $kardex_anul->cantidad_envase;
                    $kardex_devol->precio_venta_envase = $kardex_anul->precio_venta_envase;
                    $kardex_devol->save();   
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
