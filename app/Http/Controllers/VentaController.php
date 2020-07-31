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
use App\Sucursal;
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
    protected $rutas           = array('create' => 'trabajador.create', 
            'cliente'   => 'cliente.create',
            'guardarventa'   => 'venta.guardarventa',
            'guardardetalle' => 'venta.guardardetalle',
            'serieventa'     => 'venta.serieventa',
            'permisoRegistrar' => 'venta.permisoRegistrar'
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
    public function index()
    {
        $entidad          = 'Venta';
        $title            = $this->tituloAdmin;
        $titulo_cliente   = $this->tituloCliente;
        $ruta             = $this->rutas;
        $turnos_iniciados = Turnorepartidor::where('estado','I')->get();
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

    public function clienteautocompletar($searching)
    {
        $type = 'C';
        $user = Auth::user();
        $empresa_id = $user->empresa_id;
        $resultado = DB::table('personamaestro')
            ->where(function($subquery) use($searching)
            {
                $subquery->where(DB::raw('CONCAT(apellidos," ",nombres)'), 'LIKE','%'.strtoupper($searching).'%')->orWhere('razonsocial','LIKE','%'.strtoupper($searching).'%');
            })
            ->where(function($subquery) use($type)
            {
                if (!is_null($type)) {
                   
                    $subquery->where('type', '=', $type)->orwhere('secondtype','=', $type)->orwhere('type','=', 'T');
                   
                }		            		
            })
            ->leftJoin('persona', 'personamaestro.id', '=', 'persona.personamaestro_id')
            ->where('persona.empresa_id', '=', $empresa_id)
            ->where('persona.personamaestro_id', '!=', 2)
            ->whereNull('personamaestro.deleted_at')
            ->orderBy('apellidos', 'ASC')->orderBy('nombres', 'ASC')->orderBy('razonsocial', 'ASC')
            ->take(5);
        $list      = $resultado->get();
        $data = array();
        foreach ($list as $key => $value) {
            $name = '';
            if ($value->razonsocial != null) {
                $name = $value->razonsocial;
            }else{
                $name = $value->apellidos." ".$value->nombres;
            }
            $data[] = array(
                            'label' => trim($name),
                            'id'    => $value->id,
                            'value' => trim($name),
                        );
        }
        return json_encode($data);
    }

    public function guardarventa(Request $request){
        $reglas     = array('empleado_id' => 'required',
                            'serieventa' => 'required',
                            'cliente_id' => 'required',
                            'montoefectivo' => 'required',
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

            $vale_balon_lleno                = $request->input('vale_balon_lleno');
            if($vale_balon_lleno == true){
                $movimiento->vale_balon_lleno    = 1;
            }else{
                $movimiento->vale_balon_lleno    = 0;
            }

            $vale_balon_monto                = $request->input('vale_balon_monto');
            if($vale_balon_monto == true){
                $movimiento->vale_balon_monto    = 1;
                $movimiento->monto_vale_balon        = $request->input('monto_vale_balon');
            }else{
                $movimiento->vale_balon_monto    = 0;
            }

            $vale_balon_sisfoh                = $request->input('vale_balon_sisfoh');
            if($vale_balon_sisfoh == true){
                $movimiento->vale_balon_sisfoh    = 1;
                $movimiento->monto_vale_sisfoh        = $request->input('monto_vale_sisfoh');
            }else{
                $movimiento->vale_balon_sisfoh    = 0;
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

            $trabajador =$request->input('empleado_id');

            $max_turno = Turnorepartidor::where('trabajador_id', $trabajador)
                                ->max('id');

            $turno_maximo = Turnorepartidor::find($max_turno);

            $detalle_turno_pedido =  new Detalleturnopedido();
            $detalle_turno_pedido->pedido_id = $movimiento->id;
            $detalle_turno_pedido->turno_id = $turno_maximo->id;
            $detalle_turno_pedido->save();

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
            $error = DB::transaction(function() use($venta_id, $detalle,$cantidad_servicios){
                $detalleventa            = new Detalleventa();
                $cantidad                = $detalle->{"cantidad"};
                $detalleventa->cantidad  = $cantidad;
                $detalleventa->producto_id  = $detalle->{"id"};
                $detalleventa->venta_id  = $venta_id;
                $detalleventa->precio  = $detalle->{"precio"};
                $detalleventa->save();
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

}
