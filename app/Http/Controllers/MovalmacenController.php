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

class MovalmacenController extends Controller
{

    protected $folderview      = 'app.movalmacen';
    protected $tituloAdmin     = 'Movimientos de Almacén';
    protected $tituloDetalle   = 'Detalle de movimiento de almacén';
    protected $tituloRegistrar = 'Registrar movimiento de almacén';
    protected $tituloModificar = 'Modificar compra';
    protected $tituloEliminar  = 'Anular compra';
    protected $rutas           = array('create' => 'movalmacen.create', 
            'edit'     => 'movalmacen.edit', 
            'delete'   => 'movalmacen.eliminar',
            'search'   => 'movalmacen.buscar',
            'index'    => 'movalmacen.index',
            'detalle'     => 'movalmacen.detalle',
        );

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function buscar(Request $request)
    {
        $pagina           = $request->input('page');
        $filas            = $request->input('filas');
        $entidad          = 'MovAlmacen';
        $sucursal_id      = Libreria::getParam($request->input('sucursal_id'));
        $proveedor_id     = Libreria::getParam($request->input('proveedor_idb'));
        $fechainicio      = Libreria::getParam($request->input('fechai'));
        $fechafin         = Libreria::getParam($request->input('fechaf'));
        $resultado        = Movimiento::listarmovalmacen($fechainicio,$fechafin, $sucursal_id ,$proveedor_id);
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => 'VER', 'numero' => '1');
        $cabecera[]       = array('valor' => 'ANUL', 'numero' => '1');
        $cabecera[]       = array('valor' => 'FECHA', 'numero' => '1');
        $cabecera[]       = array('valor' => 'CONCEPTO', 'numero' => '1');
        $cabecera[]       = array('valor' => 'NRO DOC', 'numero' => '1');
        $cabecera[]       = array('valor' => 'COMENTARIO', 'numero' => '1');
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
        $entidad          = 'MovAlmacen';
        $title            = $this->tituloAdmin;
        $titulo_registrar = $this->tituloRegistrar;
        $ruta             = $this->rutas;
        $cboSucursal      = Sucursal::pluck('nombre', 'id')->all();
        return view($this->folderview.'.admin')->with(compact('entidad', 'cboSucursal', 'title', 'titulo_registrar', 'ruta'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $listar       = Libreria::getParam($request->input('listar'), 'NO');
        $entidad      = 'MovAlmacen';
        $compra  = null;
        $cboSucursal      = Sucursal::pluck('nombre', 'id')->all();
        $cboDocumento = array();
        $listdocument = Tipodocumento::where('tipomovimiento_id','=','3')->get();
        foreach ($listdocument as $key => $value) {
            $cboDocumento = $cboDocumento + array( $value->id => $value->abreviatura . " - " .$value->descripcion);
        }
        $formData     = array('movalmacen.store');
        $formData     = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton        = 'Guardar'; 
        $cboTipo = array(
            'I' => 'INGRESO',
            'E' => 'SALIDA',
        );
        return view($this->folderview.'.mant')->with(compact('compra', 'cboTipo','cboSucursal','cboDocumento', 'formData', 'entidad', 'boton', 'listar'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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
            $tipo =  $request->input('tipo');
            $movalmacen                 = new Movimiento();
            $movalmacen->sucursal_id    = $sucursal_id; 
            $movalmacen->tipodocumento_id = $request->input('tipodocumento_id');
            $movalmacen->tipomovimiento_id = 4;
            $movalmacen->num_compra = str_replace("_","", $request->input('serie')) . '-' . str_replace("_","", $request->input('numerodocumento'));
            $movalmacen->estado = 1;
            $movalmacen->total = $total;
            $movalmacen->concepto_id = $tipo == "I" ? 11 : 18 ;
            $movalmacen->comentario = strtoupper ( $request->input('comentario') );
            $user = Auth::user();
            $movalmacen->usuario_id = $user->id;
            $movalmacen->trabajador_id = $user->person_id;
            $movalmacen->save();
            
            $lista = (int) $request->input('cantproductos');

            for ($i=1; $i <= $lista; $i++) {
                $cantidad  = $request->input('cantidad'.$i);
                $cantidadenvase  = $request->input('cantidadenvase'.$i);
                $preciocompra    = $request->input('preciocompra'.$i);
                $preciocompraenvase    = $request->input('preciocompraenvase'.$i);
                $precioventa    = $request->input('precioventa'.$i);
                $precioventaenvase    = $request->input('precioventaenvase'.$i);
                if( $cantidadenvase == ""){
                    $cantidadenvase = 0;
                    $preciocompraenvase = 0;
                    $precioventaenvase = 0;
                }

                if($tipo == "I"){
                    $subtotal  = round(( ($cantidad * $preciocompra) + ( $cantidadenvase * $preciocompraenvase )), 2);
                }else{
                    $subtotal  = round(( ($cantidad * $precioventa) + ( $cantidadenvase * $precioventaenvase )), 2);
                }

                $detalleMovalmacen = new Detallemovalmacen();
                $detalleMovalmacen->cantidad = $cantidad;
                $detalleMovalmacen->cantidad_envase = $cantidadenvase;
                if($tipo == "I"){
                    $detalleMovalmacen->precio = $preciocompra;
                    $detalleMovalmacen->precio_envase = $preciocompraenvase;
                }else{
                    $detalleMovalmacen->precio = $precioventa;
                    $detalleMovalmacen->precio_envase = $precioventaenvase;
                }
                $detalleMovalmacen->subtotal = $subtotal;
                $detalleMovalmacen->movimiento_id = $movalmacen->id;
                $detalleMovalmacen->producto_id = $request->input('producto_id'.$i);
                $detalleMovalmacen->save();

                //Editamos valores del producto
                $producto = Producto::find($request->input('producto_id'.$i));
                if($tipo == 'I'){
                    $producto->precio_compra = $request->input('preciocompra'.$i);
                    $producto->precio_compra_envase = $request->input('preciocompraenvase'.$i);
                }else{
                    $producto->precio_venta = $request->input('precioventa'.$i);
                    $producto->precio_venta_envase = $request->input('precioventaenvase'.$i);
                }
                $producto->save();

                $stockanterior = 0;
                $stockactual = 0;
                $ultimokardex = Kardex::join('detalle_mov_almacen', 'kardex.detalle_mov_almacen_id', '=', 'detalle_mov_almacen.id')
                                        ->join('movimiento', 'detalle_mov_almacen.movimiento_id', '=', 'movimiento.id')
                                        ->where('detalle_mov_almacen.producto_id', '=',$request->input('producto_id'.$i))
                                        ->where('kardex.sucursal_id', '=',$sucursal_id)
                                        ->where('movimiento.estado','=',1)
                                        ->orderBy('kardex.id', 'DESC')
                                        ->first();

                if($tipo == "I"){
                    // ingresamos nuevo kardex
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
                    $kardex->precio_compra = $preciocompra;
                    $kardex->precio_compra_envase = $preciocompraenvase;
                    $kardex->sucursal_id = $sucursal_id;
                    $kardex->detalle_mov_almacen_id = $detalleMovalmacen->id;
                    $kardex->save();

                    //Aumentar Stock
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

                }else{
                     // ingresamos nuevo kardex
                     if ($ultimokardex ) {
                        $stockanterior = $ultimokardex->stock_actual;
                        $stockactual = $ultimokardex->stock_actual - $cantidad - $cantidadenvase;
                        $kardex = new Kardex();
                        $kardex->tipo = 'E';
                        $kardex->stock_anterior = $stockanterior;
                        $kardex->stock_actual = $stockactual;
                        $kardex->cantidad = $cantidad;
                        $kardex->cantidad_envase = $cantidadenvase;
                        $kardex->precio_venta = $precioventa;
                        $kardex->precio_venta_envase = $precioventaenvase;
                        $kardex->sucursal_id = $sucursal_id;
                        $kardex->detalle_mov_almacen_id = $detalleMovalmacen->id;
                        $kardex->save();    
                    }

                    //Reducir Stock
                    $stock = Stock::where('producto_id', $request->input('producto_id'.$i))->where('sucursal_id', $sucursal_id)->first();
                    if (count($stock) == 0) {
                        $stock = new Stock();
                        $stock->producto_id = $request->input('producto_id'.$i);
                        $stock->sucursal_id = $sucursal_id;
                    }
                    $producto = Producto::find( $request->input('producto_id'.$i) );
                    if($producto->recargable == 1){
                        $stock->cantidad -= ($cantidad + $cantidadenvase);
                        $stock->envases_total -= $cantidadenvase;
                        $stock->envases_llenos -= ($cantidad + $cantidadenvase);
                        $stock->envases_vacios += $cantidad;
                    }else{
                        $stock->cantidad -= $cantidad;
                    }
                    $stock->save();
                }
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
            $movimiento = Movimiento::find($id);
            $movimiento->estado = 0;
            $movimiento->comentario_anulado  = strtoupper($request->input('motivo'));  
            $movimiento->save();

            $kardexs = Kardex::join('detalle_mov_almacen', 'kardex.detalle_mov_almacen_id', '=', 'detalle_mov_almacen.id')
                            ->where('detalle_mov_almacen.movimiento_id', $id)
                            ->get();

            foreach ($kardexs as $key => $value) {
                $stock = Stock::where('producto_id', $value->producto_id )->where('sucursal_id', $movimiento->sucursal_id)->first();

                if( $value->cantidad_envase == 0 || $value->cantidad_envase == null ){
                    $cantidad_envase = 0;
                }else{
                    $cantidad_envase = $value->cantidad_envase;
                }

                //echo $cantidad_envase; die;
                
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

    /**
     * Función para confirmar la eliminación de un registrlo
     * @param  integer $id          id del registro a intentar eliminar
     * @param  string $listarLuego consultar si luego de eliminar se listará
     * @return html              se retorna html, con la ventana de confirmar eliminar
     */
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
        $entidad  = 'MovAlmacen';
        $formData = array('route' => array('movalmacen.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
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
        return view($this->folderview.'.detalle')->with(compact('compra','detalles','formData', 'entidad', 'boton', 'listar'));
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
                                ->where('detalle_mov_almacen.producto_id', '=', $producto_id)
                                ->where('kardex.sucursal_id', '=', $sucursal_id)
                                ->where('movimiento.estado', '=', 1)
                                ->orderBy('kardex.id', 'DESC')
                                ->first();

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
        $tipo      = Libreria::getParam($request->input('tipo'));
        $precio_compra           = Libreria::getParam(str_replace(",", "", $request->input('precio_compra')));
        $precio_compra_envase           = Libreria::getParam(str_replace(",", "", $request->input('precio_compra_envase')));
        $precio_venta           = Libreria::getParam(str_replace(",", "", $request->input('precio_venta')));
        $precio_venta_envase           = Libreria::getParam(str_replace(",", "", $request->input('precio_venta_envase')));
        //$distribuidora_id = Libreria::getParam($request->input('person_id'));
        $producto         = Producto::find($producto_id);

        $precio = $tipo == 'I' ? $precio_compra : $precio_venta; 
        $precio_envase = $tipo == 'I' ? $precio_compra_envase : $precio_venta_envase; 

        if( $producto->recargable == 1){
            $subtotal = round( ($cantidad * $precio) + ( $cantidad_envase * $precio_envase) , 2);
        }else{
            $subtotal = round($cantidad*$precio, 2);
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
                        <input type ="hidden" class="preciocompraenvase" value="'.$precio_envase.'">
                        <input type ="hidden" class="preciocompra" value="'.$precio.'">
                        <input type ="hidden" class="precioventaenvase" value="'.$precio_envase.'">
                        <input type ="hidden" class="precioventa" value="'.$precio.'">
                        <input type ="hidden" class="subtotal" value="'.$subtotal.'">
                    </td>';

        if($cantidad != 0){
        $cadena .= '<td class="text-center">
                    <span style="display: block; font-size:.9em">'.$cantidad.' UNIDADES</span>                    
                </td>';
        $cadena .= '<td class="text-center">
                    <span style="display: block; font-size:.9em">'.number_format($precio, 2, '.','').'</span>                    
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
                <span style="display: block; font-size:.9em">'.number_format($precio_envase, 2, '.','').'</span>                    
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

    public function generartipodocumento(Request $request){
        $tipo = $request->input('tipo');
        $tipos_doc = null;
        if( $tipo == "I"){
            $tipos_doc = Tipodocumento::where('tipomovimiento_id',3)->get();
        }else{
            $tipos_doc = Tipodocumento::where('tipomovimiento_id',2)->get();
        }
        $cboDocumento = array();
        foreach ($tipos_doc as $key => $value) {
            $cboDocumento = $cboDocumento + array( $value->id => $value->abreviatura . " - " .$value->descripcion);
        }
        return $tipos_doc;
    }
}
