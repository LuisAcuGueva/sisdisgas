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
use App\Kardex;
use App\Producto;
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
        $cabecera[]       = array('valor' => 'ANULAR', 'numero' => '1');
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
        $total_productos = Detallemovalmacen::where('movimiento_id',$pedido->id)->sum('subtotal');
        $entidad  = 'Pedidos';
        $formData = array('pedidos_actual.store', $id);
        $formData = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Modificar';
        $detallespago = Detallepagos::where('pedido_id', '=', $id)->where('credito',0)->get();  
        $detallespago_credito = Detallepagos::where('pedido_id', '=', $id)->where('credito',1)->get();  
        $total_pagado = Detallepagos::where('pedido_id', '=', $id)->sum('monto');  
        return view($this->folderview.'.detalle')->with(compact('pedido', 'total_pagado', 'total_productos','detallespago', 'detallespago_credito','detalles','formData', 'entidad', 'boton', 'listar'));
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
        $movimiento = Movimiento::find($id);
        $error = DB::transaction(function() use($request, $movimiento){
            $movimiento->estado = 0;
            $movimiento->comentario_anulado  = strtoupper($request->input('motivo'));  
            $movimiento->save();

            if($movimiento->pedido_sucursal == 1){
                //Todo: Borrar movimiento de caja
                $mov_cajas = Movimiento::where('tipomovimiento_id', '=', 1)
                                        ->where('venta_id', '=', $movimiento->id)
                                        ->get();
                foreach ($mov_cajas as $key => $mov_caja) {
                    $mov_caja->estado = 0;
                    $mov_caja->comentario_anulado  = strtoupper($request->input('motivo'));  
                    $mov_caja->save();
                }
            }

            //* Si es venta en sucursal -- venta repartidor

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
        $entidad  = 'Pedidos';
        $formData = array('route' => array('pedidos_actual.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Anular';
        $mensaje  = '<blockquote><p class="text-danger">¿Está seguro de anular el pedido?</p></blockquote>';
        return view('app.caja.confirmarAnular')->with(compact( 'mensaje' ,'modelo', 'formData', 'entidad', 'boton', 'listar'));
    }
}