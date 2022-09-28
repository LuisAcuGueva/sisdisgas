<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Person;
use App\Movimiento;
use App\Producto;
use App\Tipodocumento;
use App\Turnorepartidor;
use App\Detalleturnopedido;
use App\Detallemovalmacen;
use App\Detallepagos;
use App\Sucursal;
use App\Almacen;
use App\Kardex;
use App\Stock;
use App\Empresa;
use App\Configgeneral;
use App\Metodopago;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class VentaController extends Controller
{

    protected $folderview      = 'app.venta';
    protected $tituloAdmin     = 'Registrar Pedido';
    protected $tituloCliente   = 'Registrar Nuevo Cliente';
    protected $rutas           = array(
            'guardarventa'       => 'venta.guardarventa',
            'guardardetalle'     => 'venta.guardardetalle',
            'serieventa'         => 'venta.serieventa',
            'permisoRegistrar'   => 'venta.permisoRegistrar',
            'cliente'            => 'venta.cliente',
            'guardarcliente'     => 'venta.guardarcliente',
        );

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $entidad          = 'Venta';
        $title            = $this->tituloAdmin;
        $titulo_cliente   = $this->tituloCliente;
        $ruta             = $this->rutas;
        $cboSucursal      = Sucursal::pluck('nombre', 'id')->all();
        $cboTipoDocumento = Tipodocumento::where('tipomovimiento_id','2')->pluck('descripcion', 'id')->all();
        $anonimo = Person::where('id','=',1)->first();
        $descuento_fise = Configgeneral::find('1');
        $cboMetodoPago = Metodopago::pluck('nombre', 'id')->all();
        return view($this->folderview.'.admin')->with(compact('descuento_fise', 'cboTipoDocumento','anonimo' , 'cboSucursal', 'cboMetodoPago','entidad', 'title', 'titulo_cliente', 'ruta'));
    }

    public function guardarventa(Request $request){
        $error = DB::transaction(function() use($request){
            $mov_pedido = new Movimiento();
            $mov_pedido->tipomovimiento_id = 2; //* Pedido
            $mov_pedido->tipodocumento_id = $request->input('tipodocumento_id');
            $mov_pedido->num_venta = $request->input('serieventa');  
            $mov_pedido->concepto_id = 3;
            $total = $request->input('total');
            $mov_pedido->total = $total;
            $subtotal = round($total/(1.18),2);
            $mov_pedido->subtotal = $subtotal;
            $mov_pedido->igv = round($total - $subtotal,2);
            $mov_pedido->vuelto = $request->input('vuelto');
            $mov_pedido->estado = 1;
            $mov_pedido->persona_id = $request->input('cliente_id');            
            $user = Auth::user();
            $mov_pedido->usuario_id = $user->id;
            $mov_pedido->sucursal_id = $request->input('sucursal_id');
            $mov_pedido->comentario = strtoupper($request->input('comentario'));

            //* Vale balon fise
            if($request->input('vale_balon_fise') == 'on'){
                $mov_pedido->vale_balon_fise = 1;
                $mov_pedido->codigo_vale_fise = $request->input('codigo_vale_fise');
                $mov_pedido->monto_vale_fise = $request->input('monto_vale_fise');
            }else{
                $mov_pedido->vale_balon_fise = 0;
            }
            //* Vale balon monto
            if($request->input('vale_balon_monto') == 'on'){
                $mov_pedido->vale_balon_monto = 1;
                $mov_pedido->codigo_vale_monto = $request->input('codigo_vale_monto');
                $mov_pedido->monto_vale_balon = $request->input('monto_vale_balon');
            }else{
                $mov_pedido->vale_balon_monto = 0;
            }
            //* Vale balon subcafae
            if($request->input('vale_balon_subcafae') == 'on'){
                $mov_pedido->vale_balon_subcafae = 1;
                $mov_pedido->codigo_vale_subcafae = $request->input('codigo_vale_subcafae');
            }else{
                $mov_pedido->vale_balon_subcafae = 0;
            }

            //* Pedido sucursal || Pedido repartidor
            $mov_pedido->trabajador_id = $request->input('venta_sucursal') == 'on' ? $user->person_id : $request->input('empleado_id');
            $mov_pedido->pedido_sucursal = $request->input('venta_sucursal') == 'on' ? 1 : 0;

            //* Pedido a credito 
            $mov_pedido->balon_a_cuenta = $request->input('pedido_credito') == 'on' ? 1 : 0 ;
            $mov_pedido->save();

            //* PAGOS 
            foreach (json_decode($request->input('det_pagos')) as $pago) {
                $detalle_pagos = new Detallepagos();
                $detalle_pagos->monto = $pago->monto;
                $detalle_pagos->credito = 0; //* Montos pagados
                $detalle_pagos->tipo = $request->input('venta_sucursal') != 'on' ? 'R' : 'S';
                $detalle_pagos->pedido_id = $mov_pedido->id;
                $detalle_pagos->metodo_pago_id = $pago->metodopago_id;
                $detalle_pagos->save();
            }

            if($request->input('venta_sucursal') != 'on'){ //* Venta con repartidor 
                $trabajador = $request->input('empleado_id');
                $max_turno = Turnorepartidor::where('trabajador_id', $trabajador)->max('id');
                $turno_maximo = Turnorepartidor::find($max_turno);
                $detalle_turno_pedido =  new Detalleturnopedido();
                $detalle_turno_pedido->pedido_id = $mov_pedido->id;
                $detalle_turno_pedido->turno_id = $turno_maximo->id;
                $detalle_turno_pedido->save();
            }else{ //* Venta en sucursal
                $num_caja = Movimiento::where('tipomovimiento_id', 1)
                                        ->where('sucursal_id', $request->input('sucursal_id'))
                                        ->where('estado', "=", 1)
                                        ->max('num_caja');
                $num_caja = $num_caja + 1;
                $movimientocaja                       = new Movimiento();
                $movimientocaja->tipomovimiento_id    = 1;
                $movimientocaja->concepto_id          = 3;
                $movimientocaja->num_caja             = $num_caja;
                $movimientocaja->total                = $request->input('total');
                $subtotal                             = round($total/(1.18),2);
                $movimientocaja->subtotal             = $subtotal;
                $movimientocaja->estado               = 1;
                $movimientocaja->persona_id           = $request->input('cliente_id');
                $user           = Auth::user();
                $movimientocaja->trabajador_id        = $user->person_id;
                $movimientocaja->usuario_id           = $user->id;
                $movimientocaja->sucursal_id          = $request->input('sucursal_id');
                $movimientocaja->venta_id             = $mov_pedido->id;
                if($request->input('tipodocumento_id') == 1){
                    $movimientocaja->comentario           = "Pago de: BV".$request->input('serieventa');  
                }else if($request->input('tipodocumento_id') == 2){
                    $movimientocaja->comentario           = "Pago de: FV".$request->input('serieventa');  
                }else if($request->input('tipodocumento_id') == 3){
                    $movimientocaja->comentario           = "Pago de: TK".$request->input('serieventa');  
                }
                $movimientocaja->save();
            }

            //* Guardar detalle de productos y eliminar stock

            $detalles = json_decode($request->input('det_productos'));
            
            foreach ($detalles as $detalle) {
                $producto_id = $detalle->id ;
                $cantidad = $detalle->cantidad;
                $precio = $detalle->precio;
                $subtotal = $detalle->total;
                $cantidad_envase = $detalle->cantidad_envase ? $detalle->cantidad_envase : null;
                $precio_envase = $detalle->precio_envase ? $detalle->precio_envase : null;

                $detalleMovAlmacen = new Detallemovalmacen();
                $detalleMovAlmacen->cantidad = $cantidad;
                $detalleMovAlmacen->precio = $precio;
                $detalleMovAlmacen->cantidad_envase = $cantidad_envase;
                $detalleMovAlmacen->precio_envase = $precio_envase;
                $detalleMovAlmacen->subtotal = $subtotal;
                $detalleMovAlmacen->movimiento_id = $mov_pedido->id;
                $detalleMovAlmacen->producto_id = $producto_id;
                $detalleMovAlmacen->save();

                $stockanterior = 0;
                $stockactual = 0;

                $ultimokardex = Kardex::join('detalle_mov_almacen', 'kardex.detalle_mov_almacen_id', '=', 'detalle_mov_almacen.id')
                                        ->join('movimiento', 'detalle_mov_almacen.movimiento_id', '=', 'movimiento.id')
                                        ->where('detalle_mov_almacen.producto_id', '=', $producto_id)
                                        ->where('kardex.sucursal_id', '=',$mov_pedido->sucursal_id)
                                        ->where('movimiento.estado','=',1)
                                        ->orderBy('kardex.id', 'DESC')
                                        ->first();

                if ($ultimokardex === NULL) {
                    $stockactual = $cantidad + $cantidad_envase;
                    $kardex = new Kardex();
                    $kardex->tipo = 'E';
                    $kardex->fecha =  $request->input('fecha');
                    $kardex->stock_anterior = $stockanterior;
                    $kardex->stock_actual = $stockactual;
                    $kardex->cantidad = $cantidad;
                    $kardex->cantidad_envase = $cantidad_envase;
                    $kardex->precio_venta = $precio;
                    $kardex->precio_venta_envase = $precio_envase;
                    $kardex->sucursal_id = $mov_pedido->sucursal_id;
                    $kardex->detalle_mov_almacen_id = $detalleMovAlmacen->id;
                    $kardex->save();
                    
                }else{
                    $stockanterior = $ultimokardex->stock_actual;
                    $stockactual = $ultimokardex->stock_actual - $cantidad - $cantidad_envase;
                    $kardex = new Kardex();
                    $kardex->tipo = 'E';
                    $kardex->fecha =  $request->input('fecha');
                    $kardex->stock_anterior = $stockanterior;
                    $kardex->stock_actual = $stockactual;
                    $kardex->cantidad = $cantidad;
                    $kardex->cantidad_envase = $cantidad_envase;
                    $kardex->precio_venta = $precio;
                    $kardex->precio_venta_envase = $precio_envase;
                    $kardex->sucursal_id = $mov_pedido->sucursal_id;
                    $kardex->detalle_mov_almacen_id = $detalleMovAlmacen->id;
                    $kardex->save();    

                }

                //Reducir Stock

                $stock = Stock::where('producto_id', $producto_id )->where('sucursal_id', $mov_pedido->sucursal_id)->first();
                if (count($stock) == 0) {
                    $stock = new Stock();
                    $stock->producto_id = $producto_id;
                    $stock->sucursal_id = $mov_pedido->sucursal_id;
                }

                $producto = Producto::find( $producto_id );

                if($producto->recargable == 1){
                    $stock->cantidad -= $cantidad;
                    $stock->cantidad -= $cantidad_envase;
                    $stock->envases_total -= $cantidad_envase;
                    $stock->envases_llenos -= $cantidad;
                    $stock->envases_llenos -= $cantidad_envase;
                    $stock->envases_vacios += $cantidad;
                    $stock->save();
                }else{
                    $stock->cantidad -= $cantidad;
                    $stock->save();
                }

            } 

        });
        return is_null($error) ? "OK" : $error;
    }

    public function guardardetalle(Request $request){
        $txt = "";
        foreach (json_decode($request->input('det_productos')) as $detalle) {
            $txt += $detalle->cantidad;
        }
        return $txt;
    }

    public function serieventa(Request $request){
        $user = Auth::user();
        $sucursal_id  = $request->input('sucursal_id');   
        $tipodocumento_id  = $request->input('tipodocumento_id');  

        $ultimaventa_id = Movimiento::where('sucursal_id', $sucursal_id)
                                ->where('estado', "=", 1)
                                ->where('tipomovimiento_id', 2)
                                ->where('tipodocumento_id', $tipodocumento_id)
                                    ->max('id');

        $ultimaventa = Movimiento::find($ultimaventa_id);

        $num_venta = null;

        if($ultimaventa == null){
            $num_venta = 0;
            $num_venta = $num_venta + 1;
            $num_venta = (string) $num_venta;
            $cant = strlen($num_venta);
            $ceros = 7 - $cant; 
            while($ceros != 0){
                $num_venta = "0". $num_venta;
                $ceros = $ceros - 1;
            }
        }else{
            $num_venta = $ultimaventa->num_venta;
            list($serie, $num_venta) = explode("-", $num_venta);
            $num_venta = (int) $num_venta;
            $num_venta = $num_venta + 1;
            $cant = strlen($num_venta);
            $ceros = 7 - $cant; 
            while($ceros != 0){
                $num_venta = "0". $num_venta;
                $ceros = $ceros - 1;
            }
        }

        $serieventa = "0001";
        $num_venta = $serieventa.'-'. $num_venta;
        return $num_venta;
    }

    public function permisoRegistrar(Request $request){//registrar solo si hay apertura de caja sin cierre

        $sucursal_id  = $request->input('sucursal_id');

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

        return $aperturaycierre;

    }

/**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function cliente(Request $request)
    {
        $listar         = Libreria::getParam($request->input('listar'), 'NO');
        $entidad        = 'Cliente'; 
        $cliente        = null;
        $formData       = array('venta.guardarcliente');
        $formData       = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton          = 'Guardar'; 
        $accion = 0;
        return view($this->folderview.'.cliente')->with(compact('accion' ,'cliente', 'formData', 'entidad', 'boton', 'listar'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function guardarcliente(Request $request)
    {
        $listar     = Libreria::getParam($request->input('listar'), 'NO');
        $cant = $request->input('cantc');
        if($cant == 11){
            $reglas = array(
                'razon_social'    => 'required|max:200',
                'direccion'    => 'required|max:400',
                'celular'       => 'required|numeric|digits:9',
                );
        }else{
            $reglas = array(
                'apellido_pat'    => 'required|max:100',
                'direccion'    => 'required|max:400',
                );
        }
        $validacion = Validator::make($request->all(),$reglas);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $error = DB::transaction(function() use($request){
            $cliente                = new Person();
            $cant = $request->input('cantc');
            if($cant == 8){
                $cliente->dni           = $request->input('dni');
                $cliente->nombres       = strtoupper($request->input('nombres'));
                $cliente->apellido_pat  = strtoupper($request->input('apellido_pat'));
                $cliente->apellido_mat  = strtoupper($request->input('apellido_mat'));
                $cliente->ruc           = null;
                $cliente->razon_social  = strtoupper($request->input('razon_social'));
            }else{
                $cliente->dni           = null;
                $cliente->nombres       = strtoupper($request->input('nombres'));
                $cliente->apellido_pat  = strtoupper($request->input('apellido_pat'));
                $cliente->apellido_mat  = strtoupper($request->input('apellido_mat'));
                $cliente->ruc           = $request->input('dni');
                $cliente->razon_social  = strtoupper($request->input('razon_social'));
            }
            $cliente->tipo_persona  = "C";
            $cliente->direccion  = $request->input('direccion');
            $cliente->celular  = $request->input('celular');
            $cliente->save();
        });
        return is_null($error) ? "OK" : $error;
    }

    public function cargarproductos(Request $request){
        $sucursal_id  = $request->input('sucursal_id');

        $productos = Stock::join('producto', 'stock.producto_id', '=', 'producto.id')
                                ->where('frecuente',1)
                                ->where('stock.sucursal_id', $sucursal_id)
                                ->orderBy('descripcion', 'ASC')->get();

        return $productos;
    }
}
