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
        $turnos_iniciados = Turnorepartidor::join('person', 'person.id', '=', 'turno_repartidor.trabajador_id')
                                            ->where('turno_repartidor.estado','I')
                                            ->where('person.sucursal_id', 1)
                                            ->get();
        // TRABAJADORES EN TURNO
        $empleados = array();
        foreach ($turnos_iniciados as $key => $value) {
            $trabajador = Person::find($value->trabajador_id);
            array_push($empleados, $trabajador);
        }
        $cboSucursal      = Sucursal::pluck('nombre', 'id')->all();
        $cboTipoDocumento = Tipodocumento::where('tipomovimiento_id','2')->pluck('descripcion', 'id')->all();
        $anonimo = Person::where('id','=',1)->first();

        $productos = Stock::join('producto', 'stock.producto_id', '=', 'producto.id')
                                ->where('frecuente',1)
                                ->where('stock.sucursal_id',1)
                                ->orderBy('descripcion', 'ASC')->get();
        
        return view($this->folderview.'.admin')->with(compact('productos', 'empleados', 'cboTipoDocumento','anonimo' , 'cboSucursal' ,'entidad', 'title', 'titulo_cliente', 'ruta'));
    }

    public function guardarventa(Request $request){
        $reglas     = array(
                            'serieventa' => 'required',
                            'cliente_id' => 'required',
                            //'montoefectivo' => 'required',
                           );
        $mensajes   = array();
        $validacion = Validator::make($request->all(), $reglas, $mensajes);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        } 
        
        $error = DB::transaction(function() use($request){

            //guardar pedido

            $movimiento                       = new Movimiento();
            $movimiento->tipomovimiento_id    = 2;
            $movimiento->tipodocumento_id     = $request->input('tipodocumento_id');
            //$movimiento->num_caja             = $num_caja;  
            $movimiento->concepto_id          = 3;
            $movimiento->num_venta            = $request->input('serieventa');  
            $total                            = $request->input('total');
            $movimiento->total                = $total;
            $subtotal                         = round($total/(1.18),2);
            $movimiento->subtotal             = $subtotal;
            $movimiento->igv                  = round($total - $subtotal,2);

            if($request->input('montoefectivo') != null){
                $movimiento->montoefectivo        = $request->input('montoefectivo') - $request->input('vuelto');
            }else{
                $movimiento->montoefectivo        = 0.00;
            }

            if($request->input('montovisa') != null){
                $movimiento->montovisa        = $request->input('montovisa');
            }else{
                $movimiento->montovisa        = 0.00;
            }

            if($request->input('montomaster') != null){
                $movimiento->montomaster        = $request->input('montomaster');
            }else{
                $movimiento->montomaster        = 0.00;
            }

            if($request->input('vuelto') != null){
                $movimiento->vuelto        = $request->input('vuelto');
            }else{
                $movimiento->vuelto        = 0.00;
            }

            $movimiento->estado               = 1;

            $balon_a_cuenta                = $request->input('balon_a_cuenta');
            if($balon_a_cuenta == true){
                $movimiento->balon_a_cuenta    = 1;
            }else{
                $movimiento->balon_a_cuenta    = 0;
            }

            $vale_balon_monto                = $request->input('vale_balon_monto');
            if($vale_balon_monto == true){
                $movimiento->vale_balon_monto    = 1;
                $movimiento->codigo_vale_monto        = $request->input('codigo_vale_monto');
                $movimiento->monto_vale_balon        = $request->input('monto_vale_balon');
            }else{
                $movimiento->vale_balon_monto    = 0;
            }

            $vale_balon_subcafae                = $request->input('vale_balon_subcafae');
            if($vale_balon_subcafae == true){
                $movimiento->vale_balon_subcafae    = 1;
                $movimiento->codigo_vale_subcafae        = $request->input('codigo_vale_subcafae');
            }else{
                $movimiento->vale_balon_subcafae    = 0;
            }

            $vale_balon_fise                = $request->input('vale_balon_fise');
            if($vale_balon_fise == true){
                $movimiento->vale_balon_fise    = 1;
                $movimiento->codigo_vale_fise        = $request->input('codigo_vale_fise');
                $movimiento->monto_vale_fise        = $request->input('monto_vale_fise');
            }else{
                $movimiento->vale_balon_fise    = 0;
            }

            $movimiento->persona_id           = $request->input('cliente_id');            
            $user           = Auth::user();
            $movimiento->usuario_id           = $user->id;

            $venta_sucursal = $request->input('venta_sucursal');
            if($venta_sucursal != true){ //venta repartidor
                $movimiento->trabajador_id        = $request->input('empleado_id');
                $movimiento->pedido_sucursal = 0;
            }else{ //venta sucursal
                $movimiento->trabajador_id        = $user->person_id;
                $movimiento->pedido_sucursal = 1;
            }

            $movimiento->sucursal_id          = $request->input('sucursal_id');
            $movimiento->comentario =  strtoupper ($request->input('comentario') );
            $movimiento->save();

            //ELIMINE CAMPOR baalon_nuevo de tabla movimiento

            // GUARDAR DETALLE TURNO PEDIDO

            if($balon_a_cuenta != true){ // sin credito

                if($venta_sucursal != true){ //venta con repartidor 

                    $trabajador = $request->input('empleado_id');
    
                    $max_turno = Turnorepartidor::where('trabajador_id', $trabajador)
                                        ->max('id');
    
                    $turno_maximo = Turnorepartidor::find($max_turno);
    
                    $detalle_turno_pedido =  new Detalleturnopedido();
                    $detalle_turno_pedido->pedido_id = $movimiento->id;
                    $detalle_turno_pedido->turno_id = $turno_maximo->id;
                    $detalle_turno_pedido->save();
    
                }else{ //venta en sucursal
    
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
                    $movimientocaja->venta_id             = $movimiento->id;

                    if($request->input('tipodocumento_id') == 1){
                        $movimientocaja->comentario           = "Pago de: BV".$request->input('serieventa');  
                    }else if($request->input('tipodocumento_id') == 2){
                        $movimientocaja->comentario           = "Pago de: FV".$request->input('serieventa');  
                    }else if($request->input('tipodocumento_id') == 3){
                        $movimientocaja->comentario           = "Pago de: TK".$request->input('serieventa');  
                    }
                    
                    $movimientocaja->save();

                }

            }else if($balon_a_cuenta == true){ //credito

                $montocredito = $request->input('montoefectivo');

                if($montocredito > 0){ //paga parte del credito

                    $movimientopago                       = new Movimiento();
                    $movimientopago->tipomovimiento_id    = 5;
                    $movimientopago->concepto_id          = 3;
                    $movimientopago->total                = $request->input('montoefectivo');
                    $movimientopago->subtotal             = $request->input('montoefectivo');
                    $movimientopago->estado               = 1;
                    $movimientopago->persona_id           = $request->input('cliente_id');
                    $user           = Auth::user();

                    if($venta_sucursal != true){ //venta repartidor
                        $movimientopago->trabajador_id        = $request->input('empleado_id');
                    }else{ //venta sucursal
                        $movimientopago->trabajador_id        = $user->person_id;
                    }

                    $movimientopago->usuario_id           = $user->id;
                    $movimientopago->sucursal_id          = $request->input('sucursal_id');
                    $movimientopago->venta_id             = $movimiento->id;
                    $movimientopago->comentario             = "PAGO DE PEDIDO A CRÉDITO: ". $movimiento->tipodocumento->abreviatura."-". $movimiento->num_venta;
                    $movimientopago->save();

                    $detalle_pagos = new Detallepagos();
                    $detalle_pagos->pedido_id = $movimiento->id;
                    $detalle_pagos->pago_id = $movimientopago->id;
                    $detalle_pagos->monto   = $request->input('montoefectivo');

                    if($venta_sucursal != true){ //venta repartidor
                        $detalle_pagos->tipo   =  'R';
                    }else{ //venta sucursal
                        $detalle_pagos->tipo   =  'S';
                    }

                    $detalle_pagos->save();

                    if($venta_sucursal != true){ //venta con repartidor 

                        $trabajador = $request->input('empleado_id');
        
                        $max_turno = Turnorepartidor::where('trabajador_id', $trabajador)
                                            ->max('id');
        
                        $turno_maximo = Turnorepartidor::find($max_turno);
        
                        $detalle_turno_pedido =  new Detalleturnopedido();
                        $detalle_turno_pedido->pedido_id = $movimientopago->id;
                        $detalle_turno_pedido->turno_id = $turno_maximo->id;
                        $detalle_turno_pedido->save();
        
                    }else{ //venta en sucursal
        
                        $num_caja = Movimiento::where('tipomovimiento_id', 1)
                                                ->where('sucursal_id', $request->input('sucursal_id'))
                                                ->where('estado', "=", 1)
                                                ->max('num_caja');
                        $num_caja = $num_caja + 1;
                            
                        $movimientocaja                       = new Movimiento();
                        $movimientocaja->tipomovimiento_id    = 1;
                        $movimientocaja->concepto_id          = 3;
                        $movimientocaja->num_caja             = $num_caja;
                        $movimientocaja->total                = $request->input('montoefectivo');
                        $movimientocaja->subtotal             = $request->input('montoefectivo');
                        $movimientocaja->estado               = 1;
                        $movimientocaja->persona_id           = $request->input('cliente_id');
                        $user           = Auth::user();
                        $movimientocaja->trabajador_id        = $user->person_id;
                        $movimientocaja->usuario_id           = $user->id;
                        $movimientocaja->sucursal_id          = $request->input('sucursal_id');
                        $movimientocaja->venta_id             = $movimiento->id;
    
                        if($request->input('tipodocumento_id') == 1){
                            $movimientocaja->comentario           = "Pago de: BV".$request->input('serieventa');  
                        }else if($request->input('tipodocumento_id') == 2){
                            $movimientocaja->comentario           = "Pago de: FV".$request->input('serieventa');  
                        }else if($request->input('tipodocumento_id') == 3){
                            $movimientocaja->comentario           = "Pago de: TK".$request->input('serieventa');  
                        }
                        
                        $movimientocaja->save();
    
                    }


                }else if($montocredito == 0){ // no pago nada del credito

                    $movimientopago                       = new Movimiento();
                    $movimientopago->tipomovimiento_id    = 5;
                    $movimientopago->concepto_id          = 16;
                    $movimientopago->total                = 0;
                    $movimientopago->subtotal             = 0;
                    $movimientopago->estado               = 1;
                    $movimientopago->persona_id           = $request->input('cliente_id');
                    if($venta_sucursal != true){
                        $movimientopago->trabajador_id        = $request->input('empleado_id');
                    }
                    $user           = Auth::user();
                    $movimientopago->usuario_id           = $user->id;
                    $movimientopago->sucursal_id          = $request->input('sucursal_id');
                    $movimientopago->venta_id             = $movimiento->id;
                    $movimientopago->comentario             = "Pedido a crédito: ". $movimiento->tipodocumento->abreviatura."-". $movimiento->num_venta;
                    $movimientopago->save();

                    if($venta_sucursal != true){ // venta repartidor

                        $trabajador = $request->input('empleado_id');

                        $max_turno = Turnorepartidor::where('trabajador_id', $trabajador)
                                            ->max('id');

                        $turno_maximo = Turnorepartidor::find($max_turno);

                        $detalle_turno_pedido =  new Detalleturnopedido();
                        $detalle_turno_pedido->pedido_id = $movimientopago->id;
                        $detalle_turno_pedido->turno_id = $turno_maximo->id;
                        $detalle_turno_pedido->save();

                    }

                }
            }

        });
        return is_null($error) ? "OK" : $error;
    }

    public function guardardetalle(Request $request){
        $detalles = json_decode($_POST["json"]);
        //var_dump($detalles->{"data"}[0]->{"cantidad"});
        $error = null;
        $venta_id = Movimiento::where('tipomovimiento_id', 2)
                            ->where('sucursal_id', $request->input('sucursal_id'))
                            ->max('id');
        $cantidad_servicios = $request->input('cantidad');
        foreach ($detalles->{"data"} as $detalle) {
            $error = DB::transaction(function() use($request, $venta_id, $detalle,$cantidad_servicios ){
                $cantidad           = $detalle->{"cantidad"};
                $precio             = $detalle->{"precio"};
                if( $detalle->{"cantidad_envase"} != ""){
                    $cantidad_envase    = $detalle->{"cantidad_envase"};
                }else{
                    $cantidad_envase = null;
                }
                if( $detalle->{"precio_envase"} != ""){
                    $precio_envase    = $detalle->{"precio_envase"};
                }else{
                    $precio_envase = null;
                }
                $subtotal           = round( ( ( ($cantidad - $cantidad_envase) * $precio ) + ( $cantidad_envase * $precio_envase) ) , 2);
                $producto_id        = $detalle->{"id"} ;


                $detalleMovAlmacen = new Detallemovalmacen();
                $detalleMovAlmacen->cantidad = $cantidad;
                $detalleMovAlmacen->precio = $precio;
                $detalleMovAlmacen->cantidad_envase = $cantidad_envase;
                $detalleMovAlmacen->precio_envase = $precio_envase;
                $detalleMovAlmacen->subtotal = $subtotal;
                $detalleMovAlmacen->movimiento_id = $venta_id;
                $detalleMovAlmacen->producto_id = $producto_id;
                $detalleMovAlmacen->save();

                $stockanterior = 0;
                $stockactual = 0;

                $venta = Movimiento::find($venta_id);

                $ultimokardex = Kardex::join('detalle_mov_almacen', 'kardex.detalle_mov_almacen_id', '=', 'detalle_mov_almacen.id')
                                        ->join('movimiento', 'detalle_mov_almacen.movimiento_id', '=', 'movimiento.id')
                                        ->where('detalle_mov_almacen.producto_id', '=', $producto_id)
                                        ->where('kardex.sucursal_id', '=',$venta->sucursal_id)
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
                    $kardex->sucursal_id = $venta->sucursal_id;
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
                    $kardex->sucursal_id = $venta->sucursal_id;
                    $kardex->detalle_mov_almacen_id = $detalleMovAlmacen->id;
                    $kardex->save();    

                }

                //Reducir Stock

                $stock = Stock::where('producto_id', $producto_id )->where('sucursal_id', $venta->sucursal_id)->first();
                if (count($stock) == 0) {
                    $stock = new Stock();
                    $stock->producto_id = $producto_id;
                    $stock->sucursal_id = $venta->sucursal_id;
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

            });
        }

        return is_null($error) ? "OK" : $error;
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
        $boton          = 'Registrar'; 
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
                $cliente->razon_social  = null;
            }else{
                $cliente->dni           = null;
                $cliente->nombres       = null;
                $cliente->apellido_pat  = null;
                $cliente->apellido_mat  = null;
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
