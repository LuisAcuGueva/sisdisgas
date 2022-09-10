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
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PedidosController extends Controller
{

    protected $folderview      = 'app.pedidos';
    protected $tituloAdmin     = 'Buscar pedidos';
    protected $tituloDetalle  = 'Detalle de pedido';
    protected $tituloAnulacion  = 'Anular pedido';
    protected $rutas           = array(
        'detalle' => 'pedidos.detalle',
        'prestar' => 'pedidos.prestar',
        'prestarbalon' => 'pedidos.prestarbalon',
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
        $sucursal_id      = Libreria::getParam($request->input('sucursal_id'));
        $cliente          = Libreria::getParam($request->input('cliente'));
        $fechainicio      = Libreria::getParam($request->input('fechai'));
        $fechafin         = Libreria::getParam($request->input('fechaf'));
        $tipo             = Libreria::getParam($request->input('tipo'));
        $tipodocumento    = Libreria::getParam($request->input('tipodocumento'));
        $trabajador_id    = Libreria::getParam($request->input('trabajador_id'));
        $tipovale         = Libreria::getParam($request->input('tipovale'));

        $resultado        = Movimiento::listarpedidos($fechainicio, $fechafin, $sucursal_id, $cliente, $trabajador_id, $tipo, $tipodocumento, $tipovale);
        $lista            = $resultado->get();

        $cabecera         = array();
        $cabecera[]       = array('valor' => 'VER', 'numero' => '1');
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
            return view($this->folderview . '.list')->with(compact('lista', 'paginacion', 'inicio', 'ingresos_credito', 'ingresos_repartidor', 'total_ingresos', 'egresos_repartidor', 'vueltos_repartidor', 'saldo_repartidor', 'fin', 'entidad', 'cabecera', 'tituloAnulacion', 'tituloDetalle', 'ruta'));
        }
        return view($this->folderview . '.list')->with(compact('lista', 'entidad'));
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
        return view($this->folderview . '.admin')->with(compact('entidad', 'cboTipo', 'cboTipoDocumento', 'cboTipoVale', 'cboSucursal', 'title', 'ruta'));
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
        if ($pedido->tipomovimiento_id == 5) {
            $pedido = Movimiento::find($pedido->venta_id);
            $detalles = Detallemovalmacen::where('movimiento_id', $pedido->id)->get();
        } else {
            $detalles = Detallemovalmacen::where('movimiento_id', $pedido->id)->get();
        }
        $entidad  = 'Pedidos';
        $formData = array('turno.store', $id);
        $formData = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento' . $entidad, 'autocomplete' => 'off');
        $boton    = 'Modificar';
        $detallespago = Detallepagos::where('pedido_id', '=', $id)
            ->join('movimiento', 'detalle_pagos.pago_id', '=', 'movimiento.id')
            ->where('estado', 1)
            ->get();


        return view($this->folderview . '.detalle')->with(compact('pedido', 'detallespago', 'detalles', 'formData', 'entidad', 'boton', 'listar'));
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
 * rango de fechas ya
 * sucursal ya 
 * cliente ya 
 * venta sucursal o repartidor ya
 * tipo comprobante ya
 * tipo de vale ya
 * repartidor autocompletar
 * 
 * prestamos y devoluciones 
 * 
 * rango fecha
 * cliente
 * repartidor
 * repartidor o venta sucursal
 * sucursal
 * 
 * 
 * agregar 
 * repartidor autocompletar
 * 
 * 
 */
