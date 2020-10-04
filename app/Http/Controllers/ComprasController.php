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
        $proveedor_id     = Libreria::getParam($request->input('proveedor_id'));
        $fechainicio      = Libreria::getParam($request->input('fechai'));
        $fechafin         = Libreria::getParam($request->input('fechaf'));
        $resultado        = Movimiento::listarcompras($fechainicio,$fechafin, $sucursal_id ,$proveedor_id);
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => 'VER', 'numero' => '1');
        $cabecera[]       = array('valor' => 'ANUL', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Fecha', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Proveedor', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Nro', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Tipo Doc', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Responsable', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Situación', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Total', 'numero' => '1');
        
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
        $almacenes = Almacen::where('sucursal_id', 1)->get();
        $cboAlmacenes = array();
        foreach ($almacenes as $key => $value) {
            $cboAlmacenes = $cboAlmacenes + array( $value->id => $value->nombre);
        }
        return view($this->folderview.'.admin')->with(compact('entidad', 'cboSucursal', 'cboAlmacenes', 'title', 'titulo_registrar', 'ruta'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
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
        $almacenes = Almacen::where('sucursal_id', 1)->get();
        $cboAlmacenes = array();
        foreach ($almacenes as $key => $value) {
            $cboAlmacenes = $cboAlmacenes + array( $value->id => $value->nombre);
        }
        $formData     = array('compras.store');
        $formData     = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton        = 'Registrar'; 
        return view($this->folderview.'.mant')->with(compact('compra', 'cboAlmacenes', 'cboSucursal','cboDocumento', 'formData', 'entidad', 'boton', 'listar'));
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
            
            $almacen_id = $request->input('almacen');
            
            $compra                 = new Movimiento();
            $compra->sucursal_id    = $sucursal_id; 
            $compra->tipodocumento_id = $request->input('tipodocumento_id');
            $compra->tipomovimiento_id = 3;
            $compra->persona_id =  $request->input('proveedor_id');
            $compra->concepto_id = 4;
            $compra->num_compra = $request->input('serie') . '-' . $request->input('numerodocumento');
            $compra->fecha  = $request->input('fecha');
            $compra->estado = 1;
            $compra->total = $total;
            //$compra->igv = $igv;
            //$compra->subtotal = $total - $igv;
            //$compra->estadopago = 'P';
            
            $user = Auth::user();
            $compra->usuario_id = $user->id;
            $compra->trabajador_id = $user->person_id;
            $compra->almacen_id = $almacen_id;
            $compra->save();

            $compra_id = $compra->id;
            
            $lista = (int) $request->input('cantproductos');

            for ($i=1; $i <= $lista; $i++) {
                $cantidad  = $request->input('cantidad'.$i);
                $precio    = $request->input('preciocompra'.$i);
                $subtotal  = round(($cantidad*$precio), 2);

                $detalleCompra = new Detallemovalmacen();
                $detalleCompra->cantidad = $cantidad;
                $detalleCompra->precio = $precio;
                $detalleCompra->subtotal = $subtotal;
                $detalleCompra->movimiento_id = $compra_id;
                $detalleCompra->producto_id = $request->input('producto_id'.$i);
                $detalleCompra->save();
                //Editamos valores del producto
                $producto = Producto::find($request->input('producto_id'.$i));
                $producto->precio_compra = $request->input('preciocompra'.$i);
                $producto->precio_venta = $request->input('precioventa'.$i);
                $producto->save();

                $lote = null;
              
                if( $request->input('lote'.$i) != ""){
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
                }

                $stockanterior = 0;
                $stockactual = 0;

                $ultimokardex = Kardex::join('detalle_mov_almacen', 'kardex.detalle_mov_almacen_id', '=', 'detalle_mov_almacen.id')
                                        //->join('movimiento', 'detalle_mov_almacen.movimiento_id', '=', 'movimiento.id')
                                        ->where('detalle_mov_almacen.producto_id', '=',$request->input('producto_id'.$i))
                                        ->where('kardex.almacen_id', '=',$almacen_id)
                                        ->orderBy('kardex.id', 'DESC')
                                        ->first();

                //$ultimokardex = Kardex::join('detallemovimiento', 'kardex.detallemovimiento_id', '=', 'detallemovimiento.id')->where('promarlab_id', '=', $lista[$i]['promarlab_id'])->where('kardex.almacen_id', '=',1)->orderBy('kardex.id', 'DESC')->first();

                // ingresamos nuevo kardex
                if ($ultimokardex === NULL) {
                    $stockactual = $cantidad;
                    $kardex = new Kardex();
                    $kardex->tipo = 'I';
                    $kardex->fecha =  $request->input('fecha');
                    $kardex->stock_anterior = $stockanterior;
                    $kardex->stock_actual = $stockactual;
                    $kardex->cantidad = $cantidad;
                    $kardex->precio_compra = $precio;
                    $kardex->almacen_id = $almacen_id;
                    $kardex->detalle_mov_almacen_id = $detalleCompra->id;
                    if( $lote != null){
                        $kardex->lote_id = $lote->id;
                    }
                    $kardex->save();
                    
                }else{
                    $stockanterior = $ultimokardex->stock_actual;
                    $stockactual = $ultimokardex->stock_actual + $cantidad;
                    $kardex = new Kardex();
                    $kardex->tipo = 'I';
                    $kardex->fecha =  $request->input('fecha');
                    $kardex->stock_anterior = $stockanterior;
                    $kardex->stock_actual = $stockactual;
                    $kardex->cantidad = $cantidad;
                    $kardex->precio_compra = $precio;
                    $kardex->almacen_id = $almacen_id;
                    $kardex->detalle_mov_almacen_id = $detalleCompra->id;
                    if( $lote != null){
                        $kardex->lote_id = $lote->id;
                    }
                    $kardex->save();    

                }

                //Reducir Stock

                $stock = Stock::where('producto_id', $request->input('producto_id'.$i))->where('almacen_id', $almacen_id)->first();
                if (count($stock) == 0) {
                    $stock = new Stock();
                    $stock->producto_id = $request->input('producto_id'.$i);
                    $stock->almacen_id = $almacen_id;
                }
                $stock->cantidad += $cantidad;
                $stock->save();

            }

            $dat[0]=array("respuesta"=>"OK","compra_id"=>$compra->id, "ind" => 0, "second_id" => 0);
        });
        return is_null($error) ? json_encode($dat) : $error;

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
        $formData = array('turno.store', $id);
        $formData = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Modificar';
        return view($this->folderview.'.detalle')->with(compact('compra', 'detalles','formData', 'entidad', 'boton', 'listar'));
    }

    public function consultarAlmacenes(Request $request)
    {

        $sucursal_id = $request->input('sucursal_id');

        $almacenes = Almacen::where('sucursal_id',$sucursal_id)->get();

        $html = "";
        foreach($almacenes as $key => $value){
            $html = $html . '<option value="'. $value->id .'">'. $value->nombre .'</option>';
        }
        return $html;
        
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
        $almacen_id = $request->input('almacen');
        $productos  = Producto::where('descripcion', 'LIKE', '%'.strtoupper($nombre).'%')->orderBy('descripcion', 'ASC')->get();

        if(count($productos)>0){
            $c=0;
            foreach ($productos as $key => $value){
                /*$currentstock = Kardex::leftjoin('detallemovimiento', 'kardex.detallemovimiento_id', '=', 'detallemovimiento.id')->leftjoin('movimiento', 'detallemovimiento.movimiento_id', '=', 'movimiento.id')->where('producto_id', '=', $value->id)->where('movimiento.almacen_id', '=',1)->orderBy('kardex.id', 'DESC')->first();*/
                $currentstock = Kardex::join('detalle_mov_almacen', 'kardex.detalle_mov_almacen_id', '=', 'detalle_mov_almacen.id')
                                        ->where('detalle_mov_almacen.producto_id', '=', $value->id)
                                        ->where('kardex.almacen_id', '=', $almacen_id)
                                        ->orderBy('kardex.id', 'DESC')
                                        ->first();
                $stock = 0;
                if ($currentstock != null) {
                    $stock = $currentstock->stock_actual;
                }

                $data[$c] = array(
                    'nombre' => $value->descripcion,
                    'stock' => number_format($stock, 0, '.', ''),
                    'stock_seguridad' => number_format($value->stock_seguridad, 0, '.', ''),
                    'precio_venta' => number_format($value->precio_venta,2,'.',''),
                    'precio_compra' => number_format($value->precio_compra,2,'.',''),
                    'idproducto' => $value->id,
                    'lote' => $value->lote,
                );
                $c++;
            }            
        }else{
            $data = array();
        }
        return json_encode($data);
    }

    public function consultaproducto(Request $request)
    {
        $almacen_id = $request->input('almacen_id');

        $producto = Producto::find($request->input("idproducto"));
        $currentstock = Kardex::join('detalle_mov_almacen', 'kardex.detalle_mov_almacen_id', '=', 'detalle_mov_almacen.id')->join('movimiento', 'detalle_mov_almacen.movimiento_id', '=', 'movimiento.id')->where('producto_id', '=', $producto->id)->where('movimiento.almacen_id', '=',$almacen_id)->orderBy('kardex.id', 'DESC')->first();
        $stock = 0;
        if ($currentstock !== null) {
            $stock=$currentstock->stock_actual;
        }

        return $producto->id.'@'.$producto->precio_compra.'@'.$producto->precio_venta.'@'.$stock.'@'.$producto->stock_seguridad.'@'.$producto->lote;
    }

    public function agregarcarritocompra(Request $request)
    {
        $cadena = '';
        $cantidad         = Libreria::getParam(str_replace(",", "", $request->input('cantidad'))); 
        //$cantidades2      = explode("F", $cantidades1);
        $producto_id      = Libreria::getParam($request->input('producto_id'));
        $precio           = Libreria::getParam(str_replace(",", "", $request->input('precio')));
        $precioventa           = Libreria::getParam(str_replace(",", "", $request->input('precioventa')));
        $lote             = Libreria::getParam($request->input('lote'));
        //$distribuidora_id = Libreria::getParam($request->input('person_id'));
        $producto         = Producto::find($producto_id);
        
        $subtotal = round($cantidad*$precio, 2);

        $cadena .= '<td class="text-center numeration"></td>
                    <td class="infoProducto">
                        <span style="display: block; font-size:.9em">'.$producto->descripcion.'</span>
                        <input type ="hidden" class="producto_id"  value="'.$producto_id.'">
                        <input type ="hidden" class="productonombre"  value="'.$producto->nombre.'">
                        <input type ="hidden" class="cantidad" value="'.$cantidad.'">
                        <input type ="hidden" class="lote" value="'.$lote.'">
                        <input type ="hidden" class="precioventa" value="'.$precioventa.'">
                        <input type ="hidden" class="preciocompra" value="'.$precio.'">
                        <input type ="hidden" class="subtotal" value="'.$subtotal.'">
                    </td>';
        if($lote == '') {
            $lote = '-';
        }
        $cadena .= '<td class="text-center">
                    <span style="display: block; font-size:.9em">'.$lote.'</span>                    
                </td>';
        $cadena .= '<td class="text-center">
                    <span style="display: block; font-size:.9em">'.$cantidad.' UNIDADES</span>                    
                </td>';
        $cadena .= '<td class="text-center">
                    <span style="display: block; font-size:.9em">'.number_format($precio, 2, '.','').'</span>                    
                </td>';
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
        $boton          = 'Registrar'; 
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
