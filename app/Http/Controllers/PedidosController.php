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
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PedidosController extends Controller
{

    protected $folderview      = 'app.pedidos';
    protected $tituloAdmin     = 'Pedidos';
    protected $tituloDetalle  = 'Detalle de pedido';
    protected $tituloAnulacion  = 'Anular pedido';
    protected $rutas           = array(
            'detalle' => 'pedidos.detalle', 
            'search'   => 'pedidos.buscar',
            'index'    => 'pedidos.index',
            'delete'   => 'pedidos.eliminar',
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

        if (!is_null($maxapertura) && !is_null($maxcierre)) { // Ya existe una apertura y un cierre
            if($aperturaycierre == 0){ //apertura y cierre iguales ---- mostrar desde apertura a cierre
                $resultado = Movimiento::listarpedidos($sucursal_id, $aperturaycierre, $maxapertura, $maxcierre, 2);
            }else if($aperturaycierre == 1){ //apertura y cierre diferentes ------- mostrar desde apertura hasta ultimo movimiento
                $resultado = Movimiento::listarpedidos($sucursal_id, $aperturaycierre, $maxapertura, $maxcierre, 2);
            }
            $lista            = $resultado->get();
        }else if(!is_null($maxapertura) && is_null($maxcierre)) { //existe apertura pero no existe cierre
            $resultado = Movimiento::listarpedidos($sucursal_id, $aperturaycierre, $maxapertura, $maxcierre, 2);
            $lista            = $resultado->get();
        }else{
            $lista = null;
        }
        
        $cabecera         = array();
        $cabecera[]       = array('valor' => 'VER', 'numero' => '1');
        $cabecera[]       = array('valor' => 'FECHA Y HORA', 'numero' => '1');
        $cabecera[]       = array('valor' => 'NRO DOC', 'numero' => '1');
        $cabecera[]       = array('valor' => 'CLIENTE', 'numero' => '1');
        $cabecera[]       = array('valor' => 'DIRECCIÓN', 'numero' => '1');
        $cabecera[]       = array('valor' => 'COMENTARIO', 'numero' => '1');
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

}
