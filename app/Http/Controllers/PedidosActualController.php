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
use App\Detalleprestamo;
use App\Detallepagos;
use App\Stock;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PedidosActualController extends Controller
{

    protected $folderview      = 'app.pedidos_actual';
    protected $tituloAdmin     = 'Pedidos de Caja Actual';
    protected $tituloDetalle  = 'Detalle de pedido';
    protected $tituloAnulacion  = 'Anular pedido';
    protected $rutas           = array(
            'detalle' => 'pedidos_actual.detalle', 
            'prestar' => 'pedidos_actual.prestar', 
            'prestarbalon' => 'pedidos_actual.prestarbalon', 
            'search'   => 'pedidos_actual.buscar',
            'index'    => 'pedidos_actual.index',
            'delete'   => 'pedidos_actual.eliminar',
        );

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function buscar(Request $request)
    {
        $pagina           = $request->input('page');
        $filas            = $request->input('filas');
        $entidad          = 'Pedidos';
        $sucursal_id         = Libreria::getParam($request->input('sucursal_id'));
        $lista            = array();
        
        $ingresos_repartidor = 0.00;
        $ingresos_credito = 0.00;
        $vueltos_repartidor = 0.00;
        $total_ingresos = 0.00;
        $egresos_repartidor = 0.00;
        $saldo_repartidor = 0.00;

        //max apertura
        $maxapertura = Movimiento::where('concepto_id', 1)
                ->where('sucursal_id', "=", $sucursal_id)
                ->where('estado', "=", 1)
                ->max('num_caja');

        //max cierre
        $maxcierre = Movimiento::where('concepto_id', 2)
                ->where('sucursal_id', "=", $sucursal_id)
                ->where('estado', "=", 1)
                ->max('num_caja');

        $maxima_apertura = Movimiento::where('num_caja',$maxapertura)
                                    ->where('sucursal_id', "=", $sucursal_id)
                                    ->first();

        $maximo_cierre = Movimiento::where('num_caja',$maxcierre)
                                    ->where('sucursal_id', "=", $sucursal_id)
                                    ->first();

        //cantidad de aperturas
        $aperturas = Movimiento::where('concepto_id', 1)
        ->where('sucursal_id', "=", $sucursal_id)
        ->where('estado', "=", 1)
        ->count();

        //cantidad de cierres
        $cierres = Movimiento::where('concepto_id', 2)
            ->where('sucursal_id', "=", $sucursal_id)
            ->where('estado', "=", 1)
            ->count();

        $aperturaycierre = null;

        if($aperturas == $cierres){ // habilitar apertura de caja
        $aperturaycierre = 0;
        }else if($aperturas != $cierres){ //habilitar cierre de caja
        $aperturaycierre = 1;
        }
    
        if (is_null($maxapertura) && is_null($maxcierre)) {
            $lista = null;
        }else{
            $maxima_apertura_id = null;
            $maximo_cierre_id = null;
            if($maxima_apertura != null) $maxima_apertura_id = $maxima_apertura->id;
            if($maximo_cierre != null) $maximo_cierre_id = $maximo_cierre->id;
            $resultado = Movimiento::listarpedidosactual($sucursal_id, $aperturaycierre, $maxima_apertura_id, $maximo_cierre_id, 2);
            $lista            = $resultado->get();
        }

        $cabecera         = array();
        $cabecera[]       = array('valor' => 'VER', 'numero' => '1');
        $cabecera[]       = array('valor' => 'PRESTAR', 'numero' => '1');
        $cabecera[]       = array('valor' => 'FECHA Y HORA', 'numero' => '1');
        $cabecera[]       = array('valor' => 'NRO DOC', 'numero' => '1');
        $cabecera[]       = array('valor' => 'CLIENTE', 'numero' => '1');
        $cabecera[]       = array('valor' => 'DIRECCIÓN', 'numero' => '1');
        $cabecera[]       = array('valor' => 'REPARTIDOR / SUCURSAL', 'numero' => '1');
        $cabecera[]       = array('valor' => 'VALE', 'numero' => '1');
        $cabecera[]       = array('valor' => 'CRÉDITO', 'numero' => '1');
        //$cabecera[]       = array('valor' => 'COMENTARIO', 'numero' => '1');
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
        $entidad          = 'Pedidos';
        $title            = $this->tituloAdmin;
        $ruta             = $this->rutas;
        $cboSucursal      = Sucursal::pluck('nombre', 'id')->all();
        return view($this->folderview.'.admin')->with(compact('entidad', 'cboSucursal','title', 'ruta'));
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
            $detalles = Detallemovalmacen::where('movimiento_id',$pedido->id)->get();
        }else{
            $detalles = Detallemovalmacen::where('movimiento_id',$pedido->id)->get();
        }
        $entidad  = 'Pedidos';
        $formData = array('turno.store', $id);
        $formData = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Modificar';
        $detallespago = Detallepagos::where('pedido_id', '=', $id)
                    ->join('movimiento', 'detalle_pagos.pago_id', '=', 'movimiento.id')
                    ->where('estado',1)
                    ->get();  

        
        return view($this->folderview.'.detalle')->with(compact('pedido', 'detallespago','detalles','formData', 'entidad', 'boton', 'listar'));
    }

    public function prestar(Request $request, $id)
    {
        $existe = Libreria::verificarExistencia($id, 'movimiento');
        if ($existe !== true) {
            return $existe;
        }
        $listar   = Libreria::getParam($request->input('listar'), 'NO');
        $pedido = Movimiento::find($id);
        $detalles = Detallemovalmacen::where('movimiento_id',$pedido->id)->get();
        $detalles_prestamos = array();
        foreach ($detalles as $key => $value) {
            $detalleprestamo = Detalleprestamo::where('detalle_mov_almacen_id',$value->id)->where('tipo','P')->first();
            if($detalleprestamo != null) $detalles_prestamos[$value->id] = $detalleprestamo->cantidad;
        }
        $entidad  = 'Pedidos';
        $formData = array('pedidos_actual.prestarbalon', $id);
        $formData = array('route' => $formData, 'method' => 'POST', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Guardar';
        $detallespago = Detallepagos::where('pedido_id', '=', $id)
                    ->join('movimiento', 'detalle_pagos.pago_id', '=', 'movimiento.id')
                    ->where('estado',1)
                    ->get(); 
        return view($this->folderview.'.prestar')->with(compact('pedido', 'detalles_prestamos','detallespago','detalles','formData', 'entidad', 'boton', 'listar'));
    }

    public function prestarbalon(Request $request){
        $existe = Libreria::verificarExistencia($request->input('pedido_id'), 'movimiento');
        if ($existe !== true) {
            return $existe;
        }
        $listar   = Libreria::getParam($request->input('listar'), 'NO');
        $reglas     = array(
                            'envases_a_prestar'      => 'required',
                        );
        $mensajes   = array();
        $validacion = Validator::make($request->all(), $reglas, $mensajes);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $error = DB::transaction(function() use($request){
            $detalles = json_decode( $request->input('data') );
            //var_dump($detalles); die;
            $mov = true;
            foreach ($detalles as $detalle) {
                $detalle_prestamo = new Detalleprestamo();
                $detalle_prestamo->cantidad = $detalle->{"cantidad"};
                $detalle_prestamo->detalle_mov_almacen_id = $detalle->{"id"};
                $detalle_prestamo->tipo = 'P';
                $detalle_prestamo->save();
                    
                $detalle_mov = Detallemovalmacen::find($detalle->{"id"});
                $stock = Stock::where('producto_id', $detalle_mov->producto_id )->where('sucursal_id', $detalle_mov->movimiento->sucursal_id)->first();
                $stock->envases_prestados += $detalle->{"cantidad"};
                $stock->save(); 
            }
            $movimiento = Movimiento::find($detalle_mov->movimiento_id);
            $movimiento->balon_prestado=1;
            $movimiento->save();
        });
        return is_null($error) ? "OK" : $error;
    }
}

/**
 * Tabla pedido agregar campo balon_prestado y fecha hora prestamo
 * 
 * Crear detalle prestamo 
 id
 cantidad
 fecha hora
 detalle_mov_id
 * 
 * 
 * listar todos los pedidos
 * 
 * rango de fechas 
 * sucursal 
 * venta sucursal o repartidor
 * tipo comprobante 
 * cliente
 * tipo de vale
 * 
 * 
 * prestamos y devoluciones 
 * 
 * rango fecha
 * cliente
 * repartidor o venta sucursal
 * sucursal
 * 
 * 
 */