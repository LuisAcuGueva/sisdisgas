<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Movimiento;
use App\Detallepagos;
use App\Detalleturnopedido;
use App\Detallemovalmacen;
use App\Turnorepartidor;
use App\Sucursal;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CompraspagarController extends Controller
{

    protected $folderview      = 'app.compraspagar';
    protected $tituloAdmin     = 'Compras por pagar';
    protected $tituloDetalle  = 'Detalle de compra';
    protected $tituloPagos  = 'Detalle de pagos';
    protected $tituloPagar  = 'Pagar deuda';
    protected $rutas           = array(
            'search'   => 'compraspagar.buscar',
            'index'    => 'compraspagar.index',
            'detalle'    => 'compraspagar.detalle',
            'pagos'    => 'compraspagar.pagos',
            'pagar'    => 'compraspagar.pagar',
            'pagardeuda'    => 'compraspagar.pagardeuda',
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
        $sucursal_id      = Libreria::getParam($request->input('sucursal_id'));
        $desde            = Libreria::getParam($request->input('desde'));
        $hasta            = Libreria::getParam($request->input('hasta'));
        $proveedor_id     = Libreria::getParam($request->input('proveedor_idb'));
        $resultado        = Movimiento::comprascredito($desde, $hasta, $sucursal_id ,$proveedor_id);
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => 'VER', 'numero' => '1');
        $cabecera[]       = array('valor' => 'FECHA', 'numero' => '1');
        $cabecera[]       = array('valor' => 'PROVEEDOR', 'numero' => '1');
        $cabecera[]       = array('valor' => 'NRO DOC', 'numero' => '1');
        $cabecera[]       = array('valor' => 'SUCURSAL', 'numero' => '1');
        $cabecera[]       = array('valor' => 'TOTAL', 'numero' => '1');
        $cabecera[]       = array('valor' => 'DEBO', 'numero' => '1');
        $cabecera[]       = array('valor' => 'PAGUÉ', 'numero' => '1');
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
        $compra = Movimiento::find($id);
        $detalles = Detallemovalmacen::where('movimiento_id',$compra->id)->get();
        $entidad  = 'Movimiento';
        $formData = array('compraspagar.store', $id);
        $formData = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Modificar';
        $detallespago = Detallepagos::where('pedido_id', '=', $id)
                    ->join('movimiento', 'detalle_pagos.pago_id', '=', 'movimiento.id')
                    ->where('estado',1)
                    ->get();  
        return view($this->folderview.'.detalle')->with(compact('compra', 'detallespago','detalles','formData', 'entidad', 'boton', 'listar'));
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
                    ->join('movimiento', 'detalle_pagos.pago_id', '=', 'movimiento.id')
                    ->where('estado',1)
                    ->get();    
        $entidad  = 'Movimiento';
        $formData = array('compraspagar.store', $id);
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
                                    ->join('movimiento', 'detalle_pagos.pago_id', '=', 'movimiento.id')
                                    ->where('estado',1)
                                    ->sum('monto');
        round($total_pagos,2);
        $saldo = $pedido->total - $total_pagos;
        round($saldo,2);
        $entidad  = 'Movimiento';
        $cboSucursal      = Sucursal::pluck('nombre', 'id')->all();
        $formData = array('compraspagar.pagardeuda', $id);
        $formData = array('route' => $formData, 'method' => 'POST', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Pagar';
        return view($this->folderview.'.pagar')->with(compact( 'turnos_iniciados', 'saldo','pedido', 'cboSucursal', 'detalles','formData', 'entidad', 'boton', 'listar'));
    }

    public function pagardeuda(Request $request){
        $existe = Libreria::verificarExistencia($request->input('pedido_id'), 'movimiento');
        if ($existe !== true) {
            return $existe;
        }
        $listar   = Libreria::getParam($request->input('listar'), 'NO');
        $reglas     = array(
                            'monto'      => 'required|numeric',
                        );
        $mensajes   = array();
        $validacion = Validator::make($request->all(), $reglas, $mensajes);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $compra = Movimiento::find($request->input('pedido_id'));
        $error = DB::transaction(function() use($request, $compra){

            $sucursal_id = $request->input('sucursal');

            $num_caja   = Movimiento::where('sucursal_id', '=' , $sucursal_id)->max('num_caja') + 1;

            $movimientocaja = new Movimiento();
            $movimientocaja->sucursal_id        = $sucursal_id; 
            $movimientocaja->compra_id          = $compra->id;
            $movimientocaja->tipomovimiento_id  = 1;
            $movimientocaja->concepto_id        = 4;
            $movimientocaja->num_caja           = $num_caja;
            $movimientocaja->total              = $request->input('monto');
            $movimientocaja->subtotal           = $request->input('monto');
            $movimientocaja->estado             = 1;
            $movimientocaja->persona_id         = $compra->persona->id;
            $user           = Auth::user();
            $movimientocaja->comentario         = "PAGO DE COMPRA: ". $compra->tipodocumento->abreviatura."-". $compra->num_compra;
            $movimientocaja->usuario_id     = $user->id;
            $movimientocaja->save();

            $detalle_pagos = new Detallepagos();
            $detalle_pagos->pedido_id = $compra->id;
            $detalle_pagos->pago_id = $movimientocaja->id;
            $detalle_pagos->monto   = $request->input('monto');
            $detalle_pagos->tipo   =  'C';
            $detalle_pagos->save();
            
        });
        return is_null($error) ? "OK" : $error;
    }
   
}
