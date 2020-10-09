<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Person;
use App\Personamaestro;
use App\Movimiento;
use App\Detalleventa;
use App\Detallecomision;
use App\Serieventa;
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
        $sucursal_id  = $request->input('sucursal_id');
        $turnos_iniciados = Turnorepartidor::join('person', 'person.id', '=', 'turno_repartidor.trabajador_id')
                                            ->where('turno_repartidor.estado','I')
                                            ->where('person.sucursal_id', $sucursal_id)
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
        $productos = Producto::where('frecuente',1)->orderBy('descripcion', 'ASC')->get();
        
        return view($this->folderview.'.admin')->with(compact('productos', 'empleados', 'cboTipoDocumento','anonimo' , 'cboSucursal' ,'entidad', 'title', 'titulo_cliente', 'ruta'));
    }

    public function guardarventa(Request $request){
        $reglas     = array('empleado_id' => 'required',
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

            /*$num_caja = Movimiento::where('tipomovimiento_id', 1)
                                    ->where('sucursal_id', $request->input('sucursal_id'))
                                    ->where('estado', "=", 1)
                                    ->max('num_caja');
            $num_caja = $num_caja + 1;*/

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

            $balon_nuevo                = $request->input('balon_nuevo');
            if($balon_nuevo == true){
                $movimiento->balon_nuevo    = 1;
            }else{
                $movimiento->balon_nuevo    = 0;
            }

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
            $movimiento->trabajador_id        = $request->input('empleado_id');
            $user           = Auth::user();
            $movimiento->usuario_id           = $user->id;
            $movimiento->sucursal_id          = $request->input('sucursal_id');
            $movimiento->save();

            /*

            $movimientocaja                       = new Movimiento();
            $movimientocaja->tipomovimiento_id    = 1;
            $movimientocaja->concepto_id          = 3;
            $movimientocaja->num_caja             = $num_caja;
            $movimientocaja->total                = $request->input('total');
            $movimientocaja->subtotal             = $request->input('total');
            $movimientocaja->estado               = 1;
            $movimientocaja->persona_id           = $request->input('cliente_id');
            $movimientocaja->trabajador_id        = $request->input('empleado_id');
            $user           = Auth::user();
            $movimientocaja->usuario_id           = $user->id;
            $movimientocaja->sucursal_id          = $request->input('sucursal_id');
            $movimientocaja->venta_id             = $movimiento->id;

            if($request->input('tipodocumento_id') == 1){
                $movimientocaja->comentario           = "Pago de: B".$request->input('serieventa');  
            }else if($request->input('tipodocumento_id') == 2){
                $movimientocaja->comentario           = "Pago de: F".$request->input('serieventa');  
            }else if($request->input('tipodocumento_id') == 3){
                $movimientocaja->comentario           = "Pago de: T".$request->input('serieventa');  
            }
            
            $movimientocaja->save();*/

            // GUARDAR DETALLE TURNO PEDIDO

            if($balon_a_cuenta != true){

                $trabajador = $request->input('empleado_id');

                $max_turno = Turnorepartidor::where('trabajador_id', $trabajador)
                                    ->max('id');

                $turno_maximo = Turnorepartidor::find($max_turno);

                $detalle_turno_pedido =  new Detalleturnopedido();
                $detalle_turno_pedido->pedido_id = $movimiento->id;
                $detalle_turno_pedido->turno_id = $turno_maximo->id;
                $detalle_turno_pedido->save();
                
            }else{

                $montocredito = $request->input('montoefectivo');

                if($montocredito > 0){

                    $movimientopago                       = new Movimiento();
                    $movimientopago->tipomovimiento_id    = 5;
                    $movimientopago->concepto_id          = 16;
                    $movimientopago->total                = $request->input('montoefectivo');
                    $movimientopago->subtotal             = $request->input('montoefectivo');
                    $movimientopago->estado               = 1;
                    $movimientopago->persona_id           = $request->input('cliente_id');
                    $movimientopago->trabajador_id        = $request->input('empleado_id');
                    $user           = Auth::user();
                    $movimientopago->usuario_id           = $user->id;
                    $movimientopago->sucursal_id          = $request->input('sucursal_id');
                    $movimientopago->venta_id             = $movimiento->id;
                    $movimientopago->comentario             = "Pago de pedido a crédito: ". $movimiento->tipodocumento->abreviatura."-". $movimiento->num_venta;
                    $movimientopago->save();

                    $trabajador = $request->input('empleado_id');

                    $max_turno = Turnorepartidor::where('trabajador_id', $trabajador)
                                        ->max('id');

                    $turno_maximo = Turnorepartidor::find($max_turno);

                    $detalle_turno_pedido =  new Detalleturnopedido();
                    $detalle_turno_pedido->pedido_id = $movimientopago->id;
                    $detalle_turno_pedido->turno_id = $turno_maximo->id;
                    $detalle_turno_pedido->save();

                    $detalle_pagos = new Detallepagos();
                    $detalle_pagos->pedido_id = $movimiento->id;
                    $detalle_pagos->pago_id = $movimientopago->id;
                    $detalle_pagos->monto   = $request->input('montoefectivo');
                    $detalle_pagos->tipo   =  'R';
                    $detalle_pagos->save();

                }else if($montocredito == 0){

                    $movimientopago                       = new Movimiento();
                    $movimientopago->tipomovimiento_id    = 5;
                    $movimientopago->concepto_id          = 16;
                    $movimientopago->total                = 0;
                    $movimientopago->subtotal             = 0;
                    $movimientopago->estado               = 1;
                    $movimientopago->persona_id           = $request->input('cliente_id');
                    $movimientopago->trabajador_id        = $request->input('empleado_id');
                    $user           = Auth::user();
                    $movimientopago->usuario_id           = $user->id;
                    $movimientopago->sucursal_id          = $request->input('sucursal_id');
                    $movimientopago->venta_id             = $movimiento->id;
                    $movimientopago->comentario             = "Pedido a crédito: ". $movimiento->tipodocumento->abreviatura."-". $movimiento->num_venta;
                    $movimientopago->save();

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
        $almacen= Almacen::where('sucursal_id', $request->input('sucursal_id'))->first();
        foreach ($detalles->{"data"} as $detalle) {
            $error = DB::transaction(function() use($request, $venta_id, $detalle,$cantidad_servicios, $almacen ){
                $cantidad           = $detalle->{"cantidad"};
                $precio             = $detalle->{"precio"};
                $subtotal           = round(($cantidad*$precio), 2);
                $producto_id        = $detalle->{"id"} ;

                $detalleventa       = new Detalleventa();
                $detalleventa->cantidad  = $cantidad;
                $detalleventa->producto_id  = $producto_id;
                $detalleventa->venta_id  = $venta_id;
                $detalleventa->precio  = $precio;
                $detalleventa->save();

                $detalleMovAlmacen = new Detallemovalmacen();
                $detalleMovAlmacen->cantidad = $cantidad;
                $detalleMovAlmacen->precio = $precio;
                $detalleMovAlmacen->subtotal = $subtotal;
                $detalleMovAlmacen->movimiento_id = $venta_id;
                $detalleMovAlmacen->producto_id = $producto_id;
                $detalleMovAlmacen->save();

                $lote = null;
              
                /*if( $request->input('lote'.$i) != ""){
                    // Creamos el lote para el producto
                    $lote = new Lote();
                    $lote->nombre  = $request->input('lote'.$i);
                    $lote->fecha  = $request->input('fecha');
                    $lote->cantidad = $cantidad;
                    $lote->stock_restante = $cantidad;
                    $lote->producto_id = $request->input('producto_id'.$i);
                    $lote->almacen_id = $almacen_id;
                    $lote->save();

                    $detalleCompra->lote_id = $lote->id ;
                    $detalleCompra->save();
                }*/

                $stockanterior = 0;
                $stockactual = 0;

                $ultimokardex = Kardex::join('detalle_mov_almacen', 'kardex.detalle_mov_almacen_id', '=', 'detalle_mov_almacen.id')
                                        //->join('movimiento', 'detalle_mov_almacen.movimiento_id', '=', 'movimiento.id')
                                        ->where('detalle_mov_almacen.producto_id', '=', $producto_id)
                                        ->where('kardex.almacen_id', '=',$almacen->id)
                                        ->orderBy('kardex.id', 'DESC')
                                        ->first();

                if ($ultimokardex === NULL) {
                    $stockactual = $cantidad;
                    $kardex = new Kardex();
                    $kardex->tipo = 'E';
                    $kardex->fecha =  $request->input('fecha');
                    $kardex->stock_anterior = $stockanterior;
                    $kardex->stock_actual = $stockactual;
                    $kardex->cantidad = $cantidad;
                    $kardex->precio_venta = $precio;
                    $kardex->almacen_id = $almacen->id;
                    $kardex->detalle_mov_almacen_id = $detalleMovAlmacen->id;
                    if( $lote != null){
                        $kardex->lote_id = $lote->id;
                    }
                    $kardex->save();
                    
                }else{
                    $stockanterior = $ultimokardex->stock_actual;
                    $stockactual = $ultimokardex->stock_actual - $cantidad;
                    $kardex = new Kardex();
                    $kardex->tipo = 'E';
                    $kardex->fecha =  $request->input('fecha');
                    $kardex->stock_anterior = $stockanterior;
                    $kardex->stock_actual = $stockactual;
                    $kardex->cantidad = $cantidad;
                    $kardex->precio_compra = $precio;
                    $kardex->almacen_id = $almacen->id;
                    $kardex->detalle_mov_almacen_id = $detalleMovAlmacen->id;
                    if( $lote != null){
                        $kardex->lote_id = $lote->id;
                    }
                    $kardex->save();    

                }

                //Reducir Stock

                $stock = Stock::where('producto_id', $producto_id )->where('almacen_id', $almacen->id)->first();
                if (count($stock) == 0) {
                    $stock = new Stock();
                    $stock->producto_id = $producto_id;
                    $stock->almacen_id = $almacen->id;
                }
                $stock->cantidad -= $cantidad;
                $stock->save();

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
                'dni'       => 'required|unique:person,ruc,NULL,id,deleted_at,NULL|max:11',
                'razon_social'    => 'required|max:200',
                'direccion'    => 'required|max:400',
                'celular'       => 'required|numeric|digits:9',
                );
        }else{
            $reglas = array(
                'dni'       => 'required|unique:person,dni,NULL,id,deleted_at,NULL|max:8',
                'nombres'    => 'required|max:100',
                'apellido_pat'    => 'required|max:100',
                'apellido_mat'    => 'required|max:100',
                'direccion'    => 'required|max:400',
                'celular'       => 'required|numeric|digits:9',
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
}
