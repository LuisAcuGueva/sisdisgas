<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Producto;
use App\Person;
use App\Sucursal;
use App\Movimiento;
use App\Kardex;
use App\Almacen;
use App\Stock;
use App\Lote;
use App\Tipodocumento;
use App\Detallemovalmacen;
use App\Detallepagos;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ComprasController extends Controller
{

    protected $folderview      = 'app.compras';
    protected $tituloAdmin     = 'Compras';
    protected $tituloDetalle  = 'Detalle de compra';
    protected $tituloRegistrar = 'Registrar compra';
    protected $tituloModificar = 'Modificar compra';
    protected $tituloEliminar  = 'Anular compra';
    protected $rutas           = array('create' => 'compras.create', 
            'edit'     => 'compras.edit', 
            'delete'   => 'compras.eliminar',
            'search'   => 'compras.buscar',
            'index'    => 'compras.index',
            'detalle'     => 'compras.detalle',
            'proveedor'            => 'compras.proveedor',
            'guardarproveedor'     => 'compras.guardarproveedor',
        );

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function buscar(Request $request)
    {
        $pagina           = $request->input('page');
        $filas            = $request->input('filas');
        $entidad          = 'Compras';
        $sucursal_id      = Libreria::getParam($request->input('sucursal_id'));
        $proveedor_id     = Libreria::getParam($request->input('proveedor_idb'));
        $fechainicio      = Libreria::getParam($request->input('fechai'));
        $fechafin         = Libreria::getParam($request->input('fechaf'));
        $resultado        = Movimiento::listarcompras($fechainicio,$fechafin, $sucursal_id ,$proveedor_id);
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => 'VER', 'numero' => '1');
        $cabecera[]       = array('valor' => 'ANUL', 'numero' => '1');
        $cabecera[]       = array('valor' => 'FECHA', 'numero' => '1');
        $cabecera[]       = array('valor' => 'PROVEEDOR', 'numero' => '1');
        $cabecera[]       = array('valor' => 'NRO DOC', 'numero' => '1');
        //$cabecera[]       = array('valor' => 'TIPO DOC', 'numero' => '1');
        $cabecera[]       = array('valor' => 'COMENTARIO', 'numero' => '1');
        $cabecera[]       = array('valor' => 'CRÉDITO', 'numero' => '1');
        $cabecera[]       = array('valor' => 'TOTAL', 'numero' => '1');
        
        $tituloDetalle    = $this->tituloDetalle;
        $tituloEliminar  = $this->tituloEliminar;
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
            return view($this->folderview.'.list')->with(compact('lista', 'paginacion', 'inicio', 'fin', 'entidad', 'cabecera', 'tituloDetalle', 'tituloEliminar', 'ruta'));
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
        $entidad          = 'Compras';
        $title            = $this->tituloAdmin;
        $titulo_registrar = $this->tituloRegistrar;
        $ruta             = $this->rutas;
        $cboSucursal      = Sucursal::pluck('nombre', 'id')->all();
        return view($this->folderview.'.admin')->with(compact('entidad', 'cboSucursal', 'title', 'titulo_registrar', 'ruta'));
    }

    public function create(Request $request)
    {
        $listar       = Libreria::getParam($request->input('listar'), 'NO');
        $entidad      = 'Compras';
        $compra  = null;
        $cboSucursal      = Sucursal::pluck('nombre', 'id')->all();
        $cboDocumento = array();
        $listdocument = Tipodocumento::where('tipomovimiento_id','=','3')->get();
        foreach ($listdocument as $key => $value) {
            $cboDocumento = $cboDocumento + array( $value->id => $value->abreviatura . " - " .$value->descripcion);
        }
        $formData     = array('compras.store');
        $formData     = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton        = 'Guardar'; 
        return view($this->folderview.'.mant')->with(compact('compra', 'cboSucursal','cboDocumento', 'formData', 'entidad', 'boton', 'listar'));
    }
    
    public function store(Request $request)
    {
        $listar     = Libreria::getParam($request->input('listar'), 'NO');
        $reglas     = array();
        $mensajes = array();
        $validacion = Validator::make($request->all(), $reglas, $mensajes);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $dat=array();
        $sucursal_id = $request->input('sucursal');
        $error = DB::transaction(function() use($request,$sucursal_id,&$dat){
            $total = str_replace(',', '', $request->input('total'));
            $compra = new Movimiento();
            $compra->sucursal_id = $sucursal_id; 
            $compra->tipodocumento_id = $request->input('tipodocumento_id');
            $compra->tipomovimiento_id = 3;
            $compra->persona_id = $request->input('proveedor_id');
            $compra->concepto_id = 4;
            $compra->num_compra = str_replace("_","", $request->input('serie')) . '-' . str_replace("_","", $request->input('numerodocumento'));
            $compra->estado = 1;
            $compra->total = $total;
            $compra->comentario =  strtoupper ($request->input('comentario') );
            $user = Auth::user();
            $compra->usuario_id = $user->id;
            $compra->trabajador_id = $user->person_id;
            $compra->save();

            $num_caja   = Movimiento::where('sucursal_id', '=' , $sucursal_id)->max('num_caja') + 1;

            $movimientocaja = new Movimiento();
            $movimientocaja->sucursal_id        = $sucursal_id; 
            $movimientocaja->compra_id          = $compra->id;
            $movimientocaja->tipomovimiento_id  = 1;
            $movimientocaja->concepto_id        = 4;
            $movimientocaja->num_caja           = $num_caja;
            $movimientocaja->total              = $request->input('totalcompra');
            $movimientocaja->subtotal           = $request->input('totalcompra');
            $movimientocaja->estado             = 1;
            $movimientocaja->persona_id         = $request->input('proveedor_id');
            $movimientocaja->comentario         = "PAGO DE COMPRA: ". $compra->tipodocumento->abreviatura."-". $compra->num_compra;
            $user           = Auth::user();    
            $movimientocaja->usuario_id     = $user->id;
            $movimientocaja->save();

            $a_cuenta                = $request->input('a_cuenta');
            //* Registrar si es compra a credito
            if($a_cuenta){
                $pago = $request->input('pago');
                $compra->balon_a_cuenta    = 1;
                if($pago == 0 || $pago == "" ){
                    //* Si todo es crédito borra movimiento de caja
                    $movimientocaja->delete();
                }else{
                    //* Actualiza total de movimiento caja con lo que se pago
                    $movimientocaja->total = $request->input('pago');
                    $movimientocaja->subtotal = $request->input('pago');
                    $movimientocaja->save();
                }
            }else{
                $compra->balon_a_cuenta = 0;
            }
            $compra->save();

            $detalle_pagos = new Detallepagos();
            $detalle_pagos->pedido_id = $compra->id;
            $detalle_pagos->metodo_pago_id = 1;
            $detalle_pagos->monto = $a_cuenta ? $request->input('pago') : $request->input('totalcompra');
            $detalle_pagos->tipo =  'C';
            $detalle_pagos->credito = 0;
            $detalle_pagos->save();

            $lista = (int) $request->input('cantproductos');
            for ($i=1; $i <= $lista; $i++) {
                $cantidad  = $request->input('cantidad'.$i);
                $cantidadenvase  = $request->input('cantidadenvase'.$i);
                $precioenvase    = $request->input('preciocompraenvase'.$i);
                $precio    = $request->input('preciocompra'.$i);
                
                if( $cantidadenvase == ""){
                    $cantidadenvase = 0;
                    $precioenvase = 0;
                }

                $subtotal  = round(( ($cantidad * $precio) + ( $cantidadenvase * $precioenvase )), 2);

                $detalleCompra = new Detallemovalmacen();
                $detalleCompra->cantidad = $cantidad;
                $detalleCompra->cantidad_envase = $cantidadenvase;
                $detalleCompra->precio = $precio;
                $detalleCompra->precio_envase = $precioenvase;
                $detalleCompra->subtotal = $subtotal;
                $detalleCompra->movimiento_id = $compra->id;
                $detalleCompra->producto_id = $request->input('producto_id'.$i);
                $detalleCompra->save();

                //* Editamos valores del producto
                $producto = Producto::find($request->input('producto_id'.$i));
                $producto->precio_compra = $request->input('preciocompra'.$i);
                $producto->precio_venta = $request->input('precioventa'.$i);
                $producto->precio_compra_envase = $request->input('preciocompraenvase'.$i);
                $producto->precio_venta_envase = $request->input('precioventaenvase'.$i);
                $producto->save();
                
                $stockanterior = 0;
                $stockactual = 0;
                $ultimokardex = Kardex::join('detalle_mov_almacen', 'kardex.detalle_mov_almacen_id', '=', 'detalle_mov_almacen.id')
                                        //->join('movimiento', 'detalle_mov_almacen.movimiento_id', '=', 'movimiento.id')
                                        ->where('detalle_mov_almacen.producto_id', '=',$request->input('producto_id'.$i))
                                        ->where('kardex.sucursal_id', '=',$sucursal_id)
                                        ->orderBy('kardex.id', 'DESC')
                                        ->first();

                //* Ingresamos nuevo kardex
                if ($ultimokardex === NULL) {
                    $stockactual = $cantidad + $cantidadenvase;
                }else{
                    $stockanterior = $ultimokardex->stock_actual;
                    $stockactual = $ultimokardex->stock_actual + $cantidad + $cantidadenvase;
                }
                $kardex = new Kardex();
                $kardex->tipo = 'I';
                $kardex->stock_anterior = $stockanterior;
                $kardex->stock_actual = $stockactual;
                $kardex->cantidad = $cantidad;
                $kardex->cantidad_envase = $cantidadenvase;
                $kardex->precio_compra = $precio;
                $kardex->precio_compra_envase = $precioenvase;
                $kardex->sucursal_id = $sucursal_id;
                $kardex->detalle_mov_almacen_id = $detalleCompra->id;
                $kardex->save();    

                //* Aumentar Stock
                $stock = Stock::where('producto_id', $request->input('producto_id'.$i))->where('sucursal_id', $sucursal_id)->first();
                if (count($stock) == 0) {
                    $stock = new Stock();
                    $stock->producto_id = $request->input('producto_id'.$i);
                    $stock->sucursal_id = $sucursal_id;
                }
                $producto = Producto::find( $request->input('producto_id'.$i) );
                if($producto->recargable == 1){
                    $stock->cantidad += ($cantidad + $cantidadenvase);
                    $stock->envases_total += $cantidadenvase;
                    $stock->envases_llenos += ($cantidad + $cantidadenvase);
                    $stock->envases_vacios -= $cantidad;
                }else{
                    $stock->cantidad += $cantidad;
                }
                $stock->save();
            }
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
        $error = DB::transaction(function() use($request, $id){
            $compra = Movimiento::find($id);
            $compra->estado = 0;
            $compra->comentario_anulado  = strtoupper($request->input('motivo'));  
            $compra->save();

            $pagos = Detallepagos::where('pedido_id', $id)
                                ->leftjoin('movimiento', 'detalle_pagos.pago_id', '=', 'movimiento.id')                       
                                ->where('movimiento.estado', 1)
                                ->get();

            //echo "cantidad de pagos : " . count($pagos); die;

            if( count($pagos) == 1 ) {

                foreach ($pagos as $key => $value) {
                    $pago = Movimiento::find($value->pago_id);
                    $pago->estado = 0;
                    $pago->comentario_anulado  = strtoupper($request->input('motivo'));  
                    $pago->save();
                }

            }else if( count($pagos) == 0 ){

                $movcaja = Movimiento::where('compra_id', $id)->first();
                $movcaja->estado = 0;
                $movcaja->comentario_anulado  = strtoupper($request->input('motivo'));  
                $movcaja->save();
            }


            $kardexs = Kardex::join('detalle_mov_almacen', 'kardex.detalle_mov_almacen_id', '=', 'detalle_mov_almacen.id')
                            ->where('detalle_mov_almacen.movimiento_id', $id)
                            ->get();

            foreach ($kardexs as $key => $value) {
                $stock = Stock::where('producto_id', $value->producto_id )->where('sucursal_id', $compra->sucursal_id)->first();

                if( $value->cantidad_envase == 0 || $value->cantidad_envase == null ){
                    $cantidad_envase = 0;
                }else{
                    $cantidad_envase = $value->cantidad_envase;
                }

                //actualizar stock
                if( $value->tipo == "I"){
                    $stock->cantidad -= ($value->cantidad + $cantidad_envase);
                }else{
                    $stock->cantidad += ($value->cantidad + $cantidad_envase);
                }

                //actualizar cantidad de balones
                $producto = Producto::find($value->producto_id);
                if($producto->recargable == 1){
                    if( $value->tipo == "I"){
                        $stock->envases_total -= $cantidad_envase;
                        $stock->envases_llenos -= ($value->cantidad + $cantidad_envase);
                        $stock->envases_vacios += $value->cantidad;
                    }else{
                        $stock->envases_total += $cantidad_envase;
                        $stock->envases_llenos += ($value->cantidad + $cantidad_envase);
                        $stock->envases_vacios -=  $value->cantidad;
                    }
                }

                $stock->save();
                
                $kardexs_producto = Kardex::join('detalle_mov_almacen', 'kardex.detalle_mov_almacen_id', '=', 'detalle_mov_almacen.id')
                                            ->where('detalle_mov_almacen.producto_id', '=', $value->producto_id)
                                            ->where('detalle_mov_almacen.id','>', $value->id)
                                            ->get();
    
                foreach ($kardexs_producto as $key => $value2) {

                $kardex_edit = Kardex::where('detalle_mov_almacen_id', $value2->id )->first();

                //actualizar stocks en kardex
                if( $value->tipo == "I"){
                    $kardex_edit->stock_anterior -= ($value->cantidad + $cantidad_envase);
                    $kardex_edit->stock_actual -= ($value->cantidad + $cantidad_envase);
                }else{
                    $kardex_edit->stock_anterior += ($value->cantidad + $cantidad_envase);
                    $kardex_edit->stock_actual += ($value->cantidad + $cantidad_envase);
                }

                $kardex_edit->save();

                }
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
        $entidad  = 'Compras';
        $formData = array('route' => array('compras.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Eliminar';
        return view('app.caja.confirmarAnular')->with(compact('modelo', 'formData', 'entidad', 'boton', 'listar'));
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
        $entidad  = 'Turnorepartidor';
        $formData = array('compras.store', $id);
        $formData = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Modificar';
        $detallespago = Detallepagos::where('pedido_id', '=', $id)->where('credito',0)->get();  
        $detallespago_credito = Detallepagos::where('pedido_id', '=', $id)->where('credito',1)->get();  
        return view($this->folderview.'.detalle')->with(compact('compra', 'detallespago', 'detallespago_credito','detalles','formData', 'entidad', 'boton', 'listar'));
    }

    /*
        SELECT kardex.stock_actual
        FROM kardex 
        INNER JOIN detalle_mov_almacen 
        ON detalle_mov_almacen.ID = kardex.detalle_mov_almacen_id 
        where detalle_mov_almacen.producto_id = 12
        and kardex.almacen_id = 1
        order by kardex.id desc
    */

    public function buscandoproducto(Request $request)
    {
        $nombre = $request->input("nombre");
        $sucursal_id = $request->input('sucursal');
        $productos  = Producto::where('descripcion', 'LIKE', '%'.strtoupper($nombre).'%')->orderBy('descripcion', 'ASC')->get();

        if(count($productos)>0){
            $c=0;
            foreach ($productos as $key => $value){
                /*$currentstock = Kardex::leftjoin('detallemovimiento', 'kardex.detallemovimiento_id', '=', 'detallemovimiento.id')->leftjoin('movimiento', 'detallemovimiento.movimiento_id', '=', 'movimiento.id')->where('producto_id', '=', $value->id)->where('movimiento.almacen_id', '=',1)->orderBy('kardex.id', 'DESC')->first();*/
                $currentstock = Kardex::join('detalle_mov_almacen', 'kardex.detalle_mov_almacen_id', '=', 'detalle_mov_almacen.id')
                                        ->join('movimiento', 'detalle_mov_almacen.movimiento_id', '=', 'movimiento.id')
                                        ->where('detalle_mov_almacen.producto_id', '=', $value->id)
                                        ->where('kardex.sucursal_id', '=', $sucursal_id)
                                        ->where('movimiento.estado', '=', 1)
                                        ->orderBy('kardex.id', 'DESC')
                                        ->first();

                $stock = Stock::where('producto_id', $value->id )->where('sucursal_id', $sucursal_id)->first();

                $recargable = "";
                $envases_vacios = 0;

                if( $value->recargable == 1){
                    $recargable = "SI";
                }else{
                    $recargable = "NO";
                }

                if( $stock == null){
                    $data[$c] = array(
                        'nombre' => $value->descripcion,
                        'stock' => number_format(0, 0, '.', ''),
                        'envases_vacios' => $envases_vacios,
                        'recargable' => $recargable,
                        'precio_venta' => number_format($value->precio_venta,2,'.',''),
                        'precio_compra' => number_format($value->precio_compra,2,'.',''),
                        'idproducto' => $value->id,
                    );
                }else{
                    if( $stock->envases_vacios == null){
                        $envases_vacios = number_format(0, 0, '.', '');
                    }else{
                        $envases_vacios = number_format($stock->envases_vacios, 0, '.', '');
                    }
                    $data[$c] = array(
                        'nombre' => $value->descripcion,
                        'stock' => number_format($stock->cantidad, 0, '.', ''),
                        'envases_vacios' => $envases_vacios,
                        'recargable' => $recargable,
                        'precio_venta' => number_format($value->precio_venta,2,'.',''),
                        'precio_compra' => number_format($value->precio_compra,2,'.',''),
                        'idproducto' => $value->id,
                    );
                }
                
                $c++;
            }            
        }else{
            $data = array();
        }
        return json_encode($data);
    }

    public function consultaproducto(Request $request)
    {
        $sucursal_id = $request->input('sucursal_id');
        $producto_id = $request->input('idproducto');

        $producto = Producto::find($request->input("idproducto"));
        
        $currentstock = Kardex::join('detalle_mov_almacen', 'kardex.detalle_mov_almacen_id', '=', 'detalle_mov_almacen.id')
                                ->join('movimiento', 'detalle_mov_almacen.movimiento_id', '=', 'movimiento.id')
                                ->where('detalle_mov_almacen.producto_id', '=', $producto->id)
                                ->where('kardex.sucursal_id', '=',$sucursal_id)
                                ->where('movimiento.estado', '=', 1)
                                ->orderBy('kardex.id', 'DESC')->first();
        
        $stock = Stock::where('producto_id', $producto_id )->where('sucursal_id', $sucursal_id)->first();
        $sucursal = Sucursal::find($sucursal_id);

        
        $envases_sucursal = "";
        $total_envases = "";
        if( $producto->recargable != 1){
            $envases_vacios = 0;
        }else{
            if($stock != null){
                if( $producto_id == 4){
                    $envases_sucursal = $sucursal->cant_balon_premium;
                    $total_envases = $stock->envases_total;
                }else if( $producto_id == 5){ //normal
                    $envases_sucursal = $sucursal->cant_balon_normal;
                    $total_envases = $stock->envases_total;
                }
                $total_envases = $stock->envases_total;
                $envases_vacios = $stock->envases_vacios;
            }else{
                if( $producto_id == 4){
                    $envases_sucursal = $sucursal->cant_balon_premium;
                }else if( $producto_id == 5){
                    $envases_sucursal = $sucursal->cant_balon_normal;
                }
                $total_envases = 0;
                $envases_vacios = 0;
            }
        }

        if ($stock == null) {
            $stock = 0;
            $envases_vacios = 0;
        }else{
            
            $stock = $stock->cantidad;
        }

        return $producto->id.'@'.$producto->precio_compra.'@'.$producto->precio_venta.'@'.$stock.'@'.$producto->recargable.'@'.$producto->precio_compra_envase.'@'.$producto->precio_venta_envase.'@'.$envases_vacios.'@'.$envases_sucursal.'@'.$total_envases;
    }

    public function agregarcarritocompra(Request $request)
    {
        $cadena = '';
        $cantidad         = Libreria::getParam(str_replace(",", "", $request->input('cantidad'))); 
        $cantidad_envase        = Libreria::getParam(str_replace(",", "", $request->input('cantidad_envase'))); 
        //$cantidades2      = explode("F", $cantidades1);
        $producto_id      = Libreria::getParam($request->input('producto_id'));
        $precio_compra           = Libreria::getParam(str_replace(",", "", $request->input('precio_compra')));
        $precio_compra_envase           = Libreria::getParam(str_replace(",", "", $request->input('precio_compra_envase')));
        $precio_venta           = Libreria::getParam(str_replace(",", "", $request->input('precio_venta')));
        $precio_venta_envase           = Libreria::getParam(str_replace(",", "", $request->input('precio_venta_envase')));
        //$distribuidora_id = Libreria::getParam($request->input('person_id'));
        $producto         = Producto::find($producto_id);

        if( $producto->recargable == 1){
            $subtotal = round( ($cantidad * $precio_compra) + ( $cantidad_envase * $precio_compra_envase) , 2);
        }else{
            $subtotal = round($cantidad*$precio_compra, 2);
        }

        if(  $request->input('cantidad') == 0){
            $cantidad = 0;
        }
        if(  $request->input('cantidad_envase') == 0){
            $cantidad_envase = 0;
        }

        $cadena .= '<td class="text-center numeration"></td>
                    <td class="infoProducto">
                        <span style="display: block; font-size:.9em">'.$producto->descripcion.'</span>
                        <input type ="hidden" class="producto_id"  value="'.$producto_id.'">
                        <input type ="hidden" class="productonombre"  value="'.$producto->nombre.'">
                        <input type ="hidden" class="cantidad" value="'.$cantidad.'">
                        <input type ="hidden" class="cantidadenvase" value="'.$cantidad_envase.'">
                        <input type ="hidden" class="preciocompraenvase" value="'.$precio_compra_envase.'">
                        <input type ="hidden" class="preciocompra" value="'.$precio_compra.'">
                        <input type ="hidden" class="precioventaenvase" value="'.$precio_venta_envase.'">
                        <input type ="hidden" class="precioventa" value="'.$precio_venta.'">
                        <input type ="hidden" class="subtotal" value="'.$subtotal.'">
                    </td>';

        if($cantidad != 0){
        $cadena .= '<td class="text-center">
                    <span style="display: block; font-size:.9em">'.$cantidad.' UNIDADES</span>                    
                </td>';
        $cadena .= '<td class="text-center">
                    <span style="display: block; font-size:.9em">'.number_format($precio_compra, 2, '.','').'</span>                    
                </td>';
        }else{
            $cadena .= '<td class="text-center">
                <span style="display: block; font-size:.9em"> - </span>                    
                </td><td class="text-center">
                <span style="display: block; font-size:.9em"> - </span>                    
            </td>';
        }

        if( $producto->recargable == 1){

            if( $cantidad_envase == ""){
                $cadena .= '<td class="text-center">
                <span style="display: block; font-size:.9em"> - </span>                    
                </td><td class="text-center">
                <span style="display: block; font-size:.9em"> - </span>                    
            </td>';
            }else{
                $cadena .= '<td class="text-center">
                <span style="display: block; font-size:.9em">'.$cantidad_envase.' UNIDADES</span>                    
            </td><td class="text-center">
                <span style="display: block; font-size:.9em">'.number_format($precio_compra_envase, 2, '.','').'</span>                    
            </td>';
            }
        }else{
            $cadena .= '<td class="text-center">
                <span style="display: block; font-size:.9em"> - </span>                    
                </td><td class="text-center">
                <span style="display: block; font-size:.9em"> - </span>                    
            </td>';
        }

        $cadena .= '<td class="text-center">
                    <span style="display: block; font-size:.9em">'.number_format($subtotal, 2, '.','').'</span>                    
                </td>';
        $cadena .= '<td class="text-center">
                    <span style="display: block; font-size:.9em">
                    <a class="btn btn-xs btn-danger glyphicon glyphicon-remove quitarFila"></a></span>
                </td>';

        return $cadena; 
    }

    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function proveedor(Request $request)
    {
        $listar         = Libreria::getParam($request->input('listar'), 'NO');
        $entidad        = 'Proveedor'; 
        $proveedor     = null;
        $formData       = array('compras.guardarproveedor');
        $formData       = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton          = 'Guardar'; 
        $accion = 0;
        return view($this->folderview.'.proveedor')->with(compact('accion' ,'proveedor', 'formData', 'entidad', 'boton', 'listar'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function guardarproveedor(Request $request)
    {
        $listar     = Libreria::getParam($request->input('listar'), 'NO');
        $documento = $request->input('dni');
        $reglas = array(
            'ruc'       => 'required|max:11|unique:person,ruc,NULL,id,deleted_at,NULL',
            'razon_social'    => 'required|max:100',
            'direccion'    => 'required|max:400',
            'celular'       => 'required|numeric|digits:9',
            );
        $validacion = Validator::make($request->all(),$reglas);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $error = DB::transaction(function() use($request){
            $proveedor                = new Person();
            $proveedor->ruc           = $request->input('ruc');
            $proveedor->razon_social       = strtoupper($request->input('razon_social'));
            $proveedor->tipo_persona  = "P";
            $proveedor->direccion  = $request->input('direccion');
            $proveedor->celular  = $request->input('celular');
            $proveedor->save();
            
        });
        return is_null($error) ? "OK" : $error;
    }
}

