<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Movimiento;
use App\Detallemovalmacen;
use App\Detallepagos;
use App\Detalleturnopedido;
use App\Turnorepartidor;
use App\Sucursal;
use App\Metodopago;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BaloncreditoController extends Controller
{

    protected $folderview      = 'app.baloncredito';
    protected $tituloAdmin     = 'Pedidos a Crédito';
    protected $tituloDetalle  = 'Detalle de pedido';
    protected $tituloPagos  = 'Detalle de pagos';
    protected $tituloPagar  = 'Cobrar deuda de cliente';
    protected $rutas           = array(
            'search'   => 'baloncredito.buscar',
            'index'    => 'baloncredito.index',
            'detalle'    => 'baloncredito.detalle',
            'pagos'    => 'baloncredito.pagos',
            'pagar'    => 'baloncredito.pagar',
            'pagardeuda'    => 'baloncredito.pagardeuda',
        );

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function buscar(Request $request)
    {
        $pagina           = $request->input('page');
        $filas            = $request->input('filas');
        $entidad          = 'Movimiento';
        $sucursal_id         = Libreria::getParam($request->input('sucursal_id'));
        $desde            = Libreria::getParam($request->input('desde'));
        $hasta            = Libreria::getParam($request->input('hasta'));
        $resultado        = Movimiento::balonescredito($desde, $hasta, $sucursal_id);
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => 'VER', 'numero' => '1');
        $cabecera[]       = array('valor' => 'FECHA Y HORA', 'numero' => '1');
        $cabecera[]       = array('valor' => 'NRO DOC', 'numero' => '1');
        $cabecera[]       = array('valor' => 'CLIENTE', 'numero' => '1');
        $cabecera[]       = array('valor' => 'DIRECCIÓN', 'numero' => '1');
        $cabecera[]       = array('valor' => 'TOTAL', 'numero' => '1');
        $cabecera[]       = array('valor' => 'DEBE', 'numero' => '1');
        $cabecera[]       = array('valor' => 'PAGÓ', 'numero' => '1');
        $cabecera[]       = array('valor' => 'DETALLE DE PAGOS', 'numero' => '1');
        $cabecera[]       = array('valor' => 'PAGAR', 'numero' => '1');
        
        $tituloDetalle = $this->tituloDetalle;
        $tituloPagos = $this->tituloPagos;
        $tituloPagar = $this->tituloPagar;
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
            return view($this->folderview.'.list')->with(compact('lista', 'tituloPagos', 'tituloPagar', 'tituloDetalle','paginacion', 'inicio', 'fin', 'entidad', 'cabecera', 'ruta'));
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
        $entidad          = 'Movimiento';
        $title            = $this->tituloAdmin;
        $ruta             = $this->rutas;
        $cboSucursal      = Sucursal::pluck('nombre', 'id')->all();
        return view($this->folderview.'.admin')->with(compact('entidad', 'cboSucursal','title', 'ruta'));
    }

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
        $total_productos = Detallemovalmacen::where('movimiento_id',$pedido->id)->sum('subtotal');
        $entidad  = 'Pedidos';
        $formData = array('turno.store', $id);
        $formData = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento' . $entidad, 'autocomplete' => 'off');
        $boton    = 'Modificar';
        $detallespago = Detallepagos::where('pedido_id', '=', $id)->where('credito',0)->get();  
        $detallespago_credito = Detallepagos::where('pedido_id', '=', $id)->where('credito',1)->get();  
        $total_pagado = Detallepagos::where('pedido_id', '=', $id)->sum('monto');  
        return view($this->folderview . '.detalle')->with(compact('pedido', 'total_pagado', 'total_productos', 'detallespago', 'detallespago_credito', 'detalles', 'formData', 'entidad', 'boton', 'listar'));
    }

    public function pagos(Request $request, $id)
    {
        $existe = Libreria::verificarExistencia($id, 'movimiento');
        if ($existe !== true) {
            return $existe;
        }
        $listar   = Libreria::getParam($request->input('listar'), 'NO');
        $pedido = Movimiento::find($id);
        $detalles = Detallepagos::where('pedido_id', '=', $id)
                    ->join('movimiento', 'detalle_pagos.pedido_id', '=', 'movimiento.id')
                    ->where('estado',1)
                    ->get();    
        $entidad  = 'Movimiento';
        $formData = array('turno.store', $id);
        $formData = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Modificar';
        return view($this->folderview.'.pagos')->with(compact('pedido', 'detalles','formData', 'entidad', 'boton', 'listar'));
    }

    public function pagar(Request $request, $id)
    {
        $existe = Libreria::verificarExistencia($id, 'movimiento');
        if ($existe !== true) {
            return $existe;
        }
        $listar   = Libreria::getParam($request->input('listar'), 'NO');
        $pedido = Movimiento::find($id);
        $detalles = Detallemovalmacen::where('movimiento_id',$pedido->id)->get();
        $total_pagos = Detallepagos::where('pedido_id', '=', $id)
                                    ->join('movimiento', 'detalle_pagos.pedido_id', '=', 'movimiento.id')
                                    ->where('estado',1)
                                    ->sum('monto');
        round($total_pagos,2);
        $saldo = $pedido->total - $total_pagos;
        round($saldo,2);
        $entidad  = 'Movimiento';
        $cboSucursal      = Sucursal::pluck('nombre', 'id')->all();
        $turnos_iniciados = Turnorepartidor::where('estado','I')->get();
        $cboMetodoPago = Metodopago::pluck('nombre', 'id')->all();
        $formData = array('baloncredito.pagardeuda', $id);
        $formData = array('route' => $formData, 'method' => 'POST', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Guardar';
        return view($this->folderview.'.pagar')->with(compact( 'turnos_iniciados', 'cboMetodoPago','saldo','pedido', 'cboSucursal', 'detalles','formData', 'entidad', 'boton', 'listar'));
    }

    public function pagardeuda(Request $request){
        $existe = Libreria::verificarExistencia($request->input('pedido_id'), 'movimiento');
        if ($existe !== true) {
            return $existe;
        }
        $listar   = Libreria::getParam($request->input('listar'), 'NO');
        if($request->input('pago_repartidor') == "on"){
        $reglas     = array(
                            'repartidor'      => 'required',    
                            'monto'      => 'required|numeric',
                        );
        }else if($request->input('pago_sucursal') == "on"){                
        $reglas     = array(
                            'monto'      => 'required|numeric',
                        );
        }
        $mensajes   = array('repartidor.required' => 'El campo repartidor es obligatorio.');
        $validacion = Validator::make($request->all(), $reglas, $mensajes);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $pedido = Movimiento::find($request->input('pedido_id'));
        $error = DB::transaction(function() use($request, $pedido){

            $tipo_pago = $request->input('tipo_pago');
            $movimiento = new Movimiento();
            $user = Auth::user();

            if($request->input('pago_repartidor') == "on"){
                $turno = Turnorepartidor::find($request->input('repartidor'));
                $movimiento->tipomovimiento_id    = 5;
                $movimiento->trabajador_id        = $turno->person->id;
                $movimiento->sucursal_id          = $pedido->sucursal_id;
                
            }else if($request->input('pago_sucursal') == "on"){   
                $num_caja = Movimiento::where('tipomovimiento_id', 1)
                                    ->where('sucursal_id', $request->input('sucursal'))
                                    //->where('estado', "=", 1)
                                    ->max('num_caja') + 1;
                $movimiento->tipomovimiento_id    = 1;
                $movimiento->trabajador_id        = $pedido->trabajador_id;
                $movimiento->sucursal_id          = $request->input('sucursal');
                $movimiento->num_caja             = $num_caja;
            }

            $movimiento->estado               = 1;
            $movimiento->concepto_id          = 16;
            $movimiento->persona_id           = $pedido->persona_id;
            $movimiento->total                = $request->input('monto');
            $movimiento->subtotal             = $request->input('monto');
            $movimiento->usuario_id           = $user->id;
            $movimiento->venta_id             = $pedido->id;
            $movimiento->comentario             = "PAGO DE PEDIDO A CRÉDITO: ". $pedido->tipodocumento->abreviatura. $pedido->num_venta;
            $movimiento->save();

            $detalle_pagos = new Detallepagos();
            $detalle_pagos->pedido_id = $pedido->id;
            $detalle_pagos->metodo_pago_id = $request->input('metodopago_id');
            $detalle_pagos->monto = $request->input('monto');
            $detalle_pagos->credito = 1;
            if($request->input('pago_repartidor') == "on"){
                $detalle_pagos->tipo = 'R';
            }else if($request->input('pago_sucursal') == "on"){   
                $detalle_pagos->tipo = 'S';
            }
            $detalle_pagos->pago_credito_id = $movimiento->id;
            $detalle_pagos->save();

            if($request->input('pago_repartidor') == "on"){
                $detalle_turno_pedido =  new Detalleturnopedido();
                $detalle_turno_pedido->pedido_id = $movimiento->id;
                $detalle_turno_pedido->turno_id = $request->input('repartidor');
                $detalle_turno_pedido->save();
            }
        });
        return is_null($error) ? "OK" : $error;
    }
   
}
