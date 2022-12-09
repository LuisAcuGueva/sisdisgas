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

class PrestamoController extends Controller
{

    protected $folderview      = 'app.prestamo';
    protected $tituloAdmin     = 'Balones prestados';
    protected $tituloDetalle  = 'Devolver envases prestados';
    protected $tituloAnulacion  = 'Anular prestamo o devolución de envases';
    protected $rutas           = array(
            'detalle' => 'prestamoenvase.detalle', 
            'prestar' => 'prestamoenvase.prestar', 
            'prestarbalon' => 'prestamoenvase.prestarbalon', 
            'search'   => 'prestamoenvase.buscar',
            'index'    => 'prestamoenvase.index',
            'delete'   => 'prestamoenvase.eliminar',
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
        $sucursal_id      = Libreria::getParam($request->input('sucursal_id'));
        $cliente          = Libreria::getParam($request->input('cliente'));
        $fechainicio      = Libreria::getParam($request->input('fechai'));
        $fechafin         = Libreria::getParam($request->input('fechaf'));
        $tipo             = Libreria::getParam($request->input('tipo'));
        $tipodocumento    = Libreria::getParam($request->input('tipodocumento'));
        $trabajador_id    = Libreria::getParam($request->input('trabajador_id'));
        $tipovale         = Libreria::getParam($request->input('tipovale'));

        $resultado        = Movimiento::listarprestamos($fechainicio, $fechafin, $sucursal_id, $cliente, $trabajador_id, $tipo, $tipodocumento, $tipovale);
        $lista            = $resultado->get();

        $cabecera         = array();
        $cabecera[]       = array('valor' => 'VER', 'numero' => '1');
        $cabecera[]       = array('valor' => 'DEVOLVER', 'numero' => '1');
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
        $cboTipo = array(
            '' => 'SELECCIONE',
            'S' => 'SUCURSAL',
            'R' => 'REPARTIDOR',
        );
        $cboTipoDocumento = array(
            '' => 'SELECCIONE',
            '1' => 'BOLETA DE VENTA',
            '2' => 'FACTURA DE VENTA',
            '3' => 'TICKET DE VENTA',
        );
        
        $cboTipoVale = array(
            '' => 'SELECCIONE',
            '1' => 'VALE FISE',
            '2' => 'VALE SUBCAFAE',
            '3' => 'VALE MONTO',
        );
        return view($this->folderview.'.admin')->with(compact('entidad', 'cboTipo', 'cboTipoDocumento', 'cboTipoVale','cboSucursal','title', 'ruta'));
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
        $detalles_devuelto = array();
        $balones_devueltos= array();
        $guardar = array();
        
        foreach ($detalles as $key => $value) {
            $detalleprestamo = Detalleprestamo::where('detalle_mov_almacen_id',$value->id)->where('tipo','P')->first();
            if($detalleprestamo != null) $detalles_prestamos[$value->id] = $detalleprestamo->cantidad;
            
            /* GERSON (09-11-22) */
            $sum_devuelto = Detalleprestamo::where('detalle_mov_almacen_id',$value->id)->where('tipo','D')->select(DB::raw('SUM(cantidad) as devueltos'))->first();
            if($sum_devuelto != null) $detalles_devuelto[$value->id] = $sum_devuelto->devueltos;
            $balones_devueltos[$value->id] = Detalleprestamo::select('detalle_prestamo.cantidad', 'detalle_prestamo.created_at', 'producto.descripcion')
                                ->where('detalle_mov_almacen_id',$value->id)
                                ->join('detalle_mov_almacen','detalle_prestamo.detalle_mov_almacen_id','detalle_mov_almacen.id')
                                ->join('producto','detalle_mov_almacen.producto_id','producto.id')
                                ->where('tipo','D')->orderBy('detalle_prestamo.id','ASC')->get();
            if($detalles_prestamos[$value->id]==$detalles_devuelto[$value->id]){
                $guardar[$value->id] = false;
            }else{
                $guardar[$value->id] = true;
            }
            /*  */
        }
        
        $entidad  = 'Pedidos';
        $formData = array('prestamoenvase.prestarbalon', $id);
        $formData = array('route' => $formData, 'method' => 'POST', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Guardar';
        $detallespago = Detallepagos::where('pedido_id', '=', $id)
                    ->join('movimiento', 'detalle_pagos.metodo_pago_id', '=', 'movimiento.id')
                    ->where('estado',1)
                    ->get(); 
        return view($this->folderview.'.prestar')->with(compact('pedido', 'detalles_prestamos', 'detalles_devuelto','detallespago','detalles','guardar','sum_prestamo','sum_devuelto','balones_devueltos','formData', 'entidad', 'boton', 'listar'));
    }

    public function prestarbalon(Request $request){
        $existe = Libreria::verificarExistencia($request->input('pedido_id'), 'movimiento');
        if ($existe !== true) {
            return $existe;
        }
        $listar   = Libreria::getParam($request->input('listar'), 'NO');
        $reglas     = array(
                            'devolver_envases'      => 'required',
                        );
        $mensajes   = array();
        $validacion = Validator::make($request->all(), $reglas, $mensajes);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $compra = Movimiento::find($request->input('pedido_id'));
        $error = DB::transaction(function() use($request, $compra){
            $detalles = json_decode( $request->input('data') );
            //var_dump($detalles); die;
            $mov = true;
            foreach ($detalles as $detalle) {
                if($detalle->{"cantidad"}>0){
                    $detalle_prestamo = new Detalleprestamo();
                    $detalle_prestamo->cantidad = $detalle->{"cantidad"};
                    $detalle_prestamo->detalle_mov_almacen_id = $detalle->{"id"};
                    $detalle_prestamo->tipo = "D";
                    $detalle_prestamo->save();
                    
                    $detalle_mov = Detallemovalmacen::find($detalle->{"id"});
                    $stock = Stock::where('producto_id', $detalle_mov->producto_id )->where('sucursal_id', $detalle_mov->movimiento->sucursal_id)->first();
                    $stock->envases_prestados -= $detalle->{"cantidad"};
                    $stock->save(); 
                }
            }
            /*$movimiento = Movimiento::find($detalle_mov->movimiento_id);
            $movimiento->balon_prestado=;
            $movimiento->save();*/
        });
        return is_null($error) ? "OK" : $error;
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $existe = Libreria::verificarExistencia($id, 'movimiento');
        if ($existe !== true) {
            return $existe;
        }
        $error = DB::transaction(function() use($request, $id){
            
            $prestados = Detalleprestamo::select('detalle_prestamo.id','detalle_prestamo.detalle_mov_almacen_id')
                                        ->join('detalle_mov_almacen', 'detalle_mov_almacen.id', '=', 'detalle_prestamo.detalle_mov_almacen_id')
                                        ->where('detalle_mov_almacen.movimiento_id',$id)
                                        ->where('detalle_prestamo.tipo','P')
                                        ->get();
            $devueltos = Detalleprestamo::select('detalle_prestamo.id','detalle_prestamo.detalle_mov_almacen_id')
                                            ->join('detalle_mov_almacen', 'detalle_mov_almacen.id', '=', 'detalle_prestamo.detalle_mov_almacen_id')
                                            ->where('detalle_mov_almacen.movimiento_id',$id)
                                            ->where('detalle_prestamo.tipo','D')
                                            ->get();
                                            
            $tipo_anulacion = $request->input('anul_prestamo');
            
            if( $tipo_anulacion == 'D' ){
                foreach ($devueltos as $key => $value) {
                    $detalle_prestamo = Detalleprestamo::find($value->id);
                    $detalle_prestamo->delete();

                    $detalle_mov = Detallemovalmacen::find($value->detalle_mov_almacen_id);
                    $stock = Stock::where('producto_id', $detalle_mov->producto_id )->where('sucursal_id', $detalle_mov->movimiento->sucursal_id)->first();
                    $stock->envases_prestados += $detalle_prestamo->cantidad;
                    $stock->save(); 
                }
            } 

            if( $tipo_anulacion == 'P' ){
                foreach ($prestados as $key => $value) {
                    $detalle_prestamo = Detalleprestamo::find($value->id);
                    $detalle_prestamo->delete();

                    $detalle_mov = Detallemovalmacen::find($value->detalle_mov_almacen_id);
                    $stock = Stock::where('producto_id', $detalle_mov->producto_id )->where('sucursal_id', $detalle_mov->movimiento->sucursal_id)->first();
                    $stock->envases_prestados -= $detalle_prestamo->cantidad;
                    $stock->save(); 
                }
                $pedido = Movimiento::find($id);
                $pedido->balon_prestado=0;
                $pedido->save();
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
        $existe = Libreria::verificarExistencia($id, 'movimiento'); //pedido
        if ($existe !== true) {
            return $existe;
        }
        $listar = "NO";
        if (!is_null(Libreria::obtenerParametro($listarLuego))) {
            $listar = $listarLuego;
        }
        $pedido   = Movimiento::find($id);
        $detalles = Detallemovalmacen::where('movimiento_id',$pedido->id)->get();
        $detalles_prestamos = array();
        $detalles_devuelto = array();
        foreach ($detalles as $key => $value) {
            $detalleprestamo = Detalleprestamo::where('detalle_mov_almacen_id',$value->id)->where('tipo','P')->first();
            if($detalleprestamo != null) $detalles_prestamos[$value->id] = $detalleprestamo->cantidad;
            $detalledevuelto = Detalleprestamo::where('detalle_mov_almacen_id',$value->id)->where('tipo','D')->first();
            if($detalledevuelto != null) $detalles_devuelto[$value->id] = $detalledevuelto->cantidad;
        }
        $entidad  = 'Pedidos';
        $prestados = Detalleprestamo::join('detalle_mov_almacen', 'detalle_mov_almacen.id', '=', 'detalle_prestamo.detalle_mov_almacen_id')
                                        ->where('detalle_mov_almacen.movimiento_id',$id)
                                        ->where('detalle_prestamo.tipo','P')
                                        ->get();
        $devueltos = Detalleprestamo::join('detalle_mov_almacen', 'detalle_mov_almacen.id', '=', 'detalle_prestamo.detalle_mov_almacen_id')
                                        ->where('detalle_mov_almacen.movimiento_id',$id)
                                        ->where('detalle_prestamo.tipo','D')
                                        ->get();
        $formData = array('route' => array('prestamoenvase.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Anular';
        return view($this->folderview.'.confirmarEliminar')->with(compact('pedido', 'detalles_prestamos', 'detalles_devuelto','detallespago','detalles', 'formData', 'entidad', 'boton', 'listar'));
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