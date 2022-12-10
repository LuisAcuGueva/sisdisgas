<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Hash;
use Validator;
use App\Http\Requests;
use App\User;
use App\Person;
use App\Sucursal;
use App\Usertype;
use App\Movimiento;
use App\Producto;
use App\Turnorepartidor;
use App\Detalleturnopedido;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class InicioController extends Controller
{
    protected $folderview      = 'app.inicio';
    protected $tituloAdmin     = 'Caja actual';
    protected $tituloRegistrar = 'Registrar usuario';
    protected $tituloModificar = 'Modificar usuario';
    protected $tituloEliminar  = 'Eliminar usuario';
    protected $rutas           = array('index'  => 'inicio.index',
            'save'  => 'inicio.save',
            'search_stock'  => 'inicio.buscarinventario',
            'search_turnos'  => 'inicio.buscarturnos',
            'search_vendidos'  => 'inicio.buscarproductosvendidos',
            'search_caja'  => 'inicio.buscarcaja',
            'search_credito'  => 'inicio.buscarcredito',
            'update'  => 'inicio.update',
        );

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function buscarcaja(Request $request)
    {
        $sucursal_id      = Libreria::getParam($request->input('sucursal_id_caja'));
        $sucursal = Sucursal::find($sucursal_id);
        //$empresa_id = $sucursal->empresa_id;

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

        //cantidad de aperturas
        $aperturas_principal = Movimiento::where('concepto_id', 1)
                                ->where('sucursal_id', "=", 1)
                                ->where('estado', "=", 1)
                                ->count();
        //cantidad de cierres
        $cierres_principal = Movimiento::where('concepto_id', 2)
                            ->where('sucursal_id', "=", 1)
                            ->where('estado', "=", 1)
                            ->count();

        $caja_principal = null;

        if($aperturas_principal == $cierres_principal){ // habilitar apertura de caja = CAJA CERRADA
            $caja_principal = 0;
        }else if($aperturas_principal != $cierres_principal){ //habilitar cierre de caja = CAJA ABIERTA
            $caja_principal = 1;
        }

        $lstsucursal = Sucursal::all();

        $sucursal_principal = Sucursal::find(1);

        foreach ($lstsucursal as $key => $value) {
            if($value == $sucursal_principal){
               unset($lstsucursal[$key]);
            }
        }

        $cant_cajas_sucursales_abiertas = 0;

        foreach ($lstsucursal as $key => $value) {

            $aperturas_sucursal = Movimiento::where('concepto_id', 1)
                                ->where('sucursal_id', "=", $value->id)
                                ->where('estado', "=", 1)
                                ->count();
            //cantidad de cierres
            $cierres_sucursal = Movimiento::where('concepto_id', 2)
                                ->where('sucursal_id', "=", $value->id)
                                ->where('estado', "=", 1)
                                ->count();

            $aperturaycierre_sucursal = null;

            if($aperturas_sucursal == $cierres_sucursal){ // habilitar apertura de caja = caja cerrada
                //$aperturaycierre_sucursal = 0;
            }else if($aperturas_sucursal != $cierres_sucursal){ //habilitar cierre de caja = caja abierta
                $cant_cajas_sucursales_abiertas++;
            }

        }


        //max apertura
        $maxapertura = Movimiento::where('concepto_id', 1)
                ->where('sucursal_id', "=", $sucursal_id)
                ->where('estado', "=", 1)
                ->max('num_caja');
        //max cierre
        $maxcierre = Movimiento::where('concepto_id', 2)
                ->where('sucursal_id', "=", $sucursal_id)
                ->where('estado', "=", 1)
                ->max('num_caja');

        $ultimo_cierre = Movimiento::where('concepto_id', 2)
                                ->where('sucursal_id', "=", $sucursal_id)
                                ->where('estado', "=", 1)
                                ->where('num_caja', "=", $maxcierre)
                                ->first();

        $montoapertura = 0.00;
        $monto_vuelto = 0.00;
        $ingresos_efectivo = 0.00;
        $ingresos_visa = 0.00;
        $ingresos_master = 0.00;
        $ingresos_total = 0.00;
        $egresos = 0.00;
        $saldo = 0.00;
        $monto_caja = 0.00;

        if (!is_null($maxapertura) && !is_null($maxcierre)) { // Ya existe una apertura y un cierre
            $apertura = Movimiento::where('concepto_id', 1)
                ->where('sucursal_id', "=", $sucursal_id)
                ->where('estado', "=", 1)
                ->where('num_caja',$maxapertura)->first();
            $montoapertura = $apertura->total;
            if($aperturaycierre == 0){ //apertura y cierre iguales ---- mostrar desde apertura a cierre
                /*

                SELECT SUM(montoefectivo)
                FROM movimiento as mov
                INNER JOIN concepto as con 
                ON mov.concepto_id = con.id
                WHERE mov.num_caja >= 5 
                and mov.sucursal_id = 1
                and con.tipo = 0 // INGRESO

                */
                
                //ingresos efectivo
                $ingresos_efectivo = Movimiento::where('num_caja','>', $maxapertura)
                                            ->where('num_caja','<', $maxcierre)
                                            ->where('tipomovimiento_id',1)
                                            ->where('estado', "=", 1)
                                            ->where('sucursal_id', "=", $sucursal_id)
                                            ->where('concepto_id', "!=", 12)
                                            ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                            ->where('concepto.tipo', "=", 0) //ingreso
                                            ->sum('montoefectivo');

                round($ingresos_efectivo,2);



                    //montos vuelto
                    $monto_vuelto = Movimiento::where('num_caja','>', $maxapertura)
                    ->where('num_caja','<', $maxcierre)
                    ->where('tipomovimiento_id',1)
                    ->where('estado', "=", 1)
                    ->where('sucursal_id', "=", $sucursal_id)
                    ->where(function($subquery)
                    {
                        $subquery->where('concepto_id', '=', 12)->orwhere('concepto_id', "=", 15);
                    })
                    ->sum('total');

                round($monto_vuelto,2);

                /*


                SELECT SUM(montovisa)
                FROM movimiento as mov
                INNER JOIN concepto as con 
                ON mov.concepto_id = con.id
                WHERE mov.num_caja >= 5 
                and mov.sucursal_id = 1
                and con.tipo = 0 // INGRESO

                */
                //ingresos tarjeta visa

                $ingresos_visa = Movimiento::where('num_caja','>', $maxapertura)
                                            ->where('num_caja','<', $maxcierre)
                                            ->where('tipomovimiento_id',1)
                                            ->where('estado', "=", 1)
                                            ->where('sucursal_id', "=", $sucursal_id)
                                            ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                            ->where('concepto.tipo', "=", 0) //ingreso
                                            ->sum('montovisa');

                round($ingresos_visa,2);

                /*

                SELECT SUM(montomaster)
                FROM movimiento as mov
                INNER JOIN concepto as con 
                ON mov.concepto_id = con.id
                WHERE mov.num_caja >= 5 
                and mov.sucursal_id = 1
                and con.tipo = 0 // INGRESO

                */
                //ingresos tarjeta mastercard

                $ingresos_master = Movimiento::where('num_caja','>', $maxapertura)
                                            ->where('num_caja','<', $maxcierre)
                                            ->where('tipomovimiento_id',1)
                                            ->where('estado', "=", 1)
                                            ->where('sucursal_id', "=", $sucursal_id)
                                            ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                            ->where('concepto.tipo', "=", 0) //ingreso
                                            ->sum('montomaster');

                round($ingresos_master,2);

                //ingresos total

                $ingresos_total = Movimiento::where('num_caja','>', $maxapertura)
                                            ->where('num_caja','<', $maxcierre)
                                            ->where('tipomovimiento_id',1)
                                            ->where('estado', "=", 1)
                                            ->where('sucursal_id', "=", $sucursal_id)
                                            ->where('concepto_id', "!=", 12)
                                            ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                            ->where('concepto.tipo', "=", 0) //ingreso
                                            ->sum('total');
                round($ingresos_total,2);

                /*

                SELECT SUM(total)
                FROM movimiento as mov
                INNER JOIN concepto as con 
                ON mov.concepto_id = con.id
                WHERE mov.num_caja >= 5 
                and mov.sucursal_id = 1
                and con.tipo = 1 // EGRESO

                */
                //egresos
                $egresos = Movimiento::where('num_caja','>', $maxapertura)
                                            ->where('num_caja','<', $maxcierre)
                                            ->where('tipomovimiento_id',1)
                                            ->where('estado', "=", 1)
                                            ->where('sucursal_id', "=", $sucursal_id)
                                            ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                            ->where('concepto.tipo', "=", 1) //egreso
                                            ->where(function($subquery)
                                            {
                                                $subquery->where('concepto_id', '!=', 12)->where('concepto_id', "!=", 15);
                                            })
                                            ->sum('total');
                round($egresos,2);

                //saldo
                $saldo = round($ingresos_total - $egresos, 2);

                $monto_caja = round($montoapertura + $ingresos_total - $egresos - $monto_vuelto, 2);

            }else if($aperturaycierre == 1){ //apertura y cierre diferentes ------- mostrar desde apertura hasta ultimo movimiento
                /*

                SELECT SUM(montoefectivo)
                FROM movimiento as mov
                INNER JOIN concepto as con 
                ON mov.concepto_id = con.id
                WHERE mov.num_caja >= 5 
                and mov.sucursal_id = 1
                and con.tipo = 0 // INGRESO

                */
                
                //ingresos efectivo
                $ingresos_efectivo = Movimiento::where('num_caja','>', $maxapertura)
                                            ->where('tipomovimiento_id',1)
                                            ->where('estado', "=", 1)
                                            ->where('sucursal_id', "=", $sucursal_id)
                                            ->where('concepto_id', "!=", 12)
                                            ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                            ->where('concepto.tipo', "=", 0) //ingreso
                                            ->sum('montoefectivo');
                round($ingresos_efectivo,2);


                //montos vuelto
                $monto_vuelto = Movimiento::where('num_caja','>', $maxapertura)
                ->where('tipomovimiento_id',1)
                ->where('estado', "=", 1)
                ->where('sucursal_id', "=", $sucursal_id)
                ->where(function($subquery)
                    {
                        $subquery->where('concepto_id', '=', 12)->orwhere('concepto_id', "=", 15);
                    })
                ->sum('total');

                round($monto_vuelto,2);


                /*

                SELECT SUM(montovisa)
                FROM movimiento as mov
                INNER JOIN concepto as con 
                ON mov.concepto_id = con.id
                WHERE mov.num_caja >= 5 
                and mov.sucursal_id = 1
                and con.tipo = 0 // INGRESO

                */
                //ingresos tarjeta visa


                $ingresos_visa = Movimiento::where('num_caja','>', $maxapertura)
                                            ->where('tipomovimiento_id',1)
                                            ->where('estado', "=", 1)
                                            ->where('sucursal_id', "=", $sucursal_id)
                                            ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                            ->where('concepto.tipo', "=", 0) //ingreso
                                            ->sum('montovisa');
                round($ingresos_visa,2);

                /*

                SELECT SUM(montomaster)
                FROM movimiento as mov
                INNER JOIN concepto as con 
                ON mov.concepto_id = con.id
                WHERE mov.num_caja >= 5 
                and mov.sucursal_id = 1
                and con.tipo = 0 // INGRESO

                */
                //ingresos tarjeta mastercard

                $ingresos_master = Movimiento::where('num_caja','>', $maxapertura)
                                            ->where('tipomovimiento_id',1)  
                                            ->where('estado', "=", 1)
                                            ->where('sucursal_id', "=", $sucursal_id)
                                            ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                            ->where('concepto.tipo', "=", 0) //ingreso
                                            ->sum('montomaster');
                round($ingresos_master,2);

                //ingresos total

                $ingresos_total = Movimiento::where('num_caja','>', $maxapertura)
                                            ->where('tipomovimiento_id',1)
                                            ->where('estado', "=", 1)
                                            ->where('sucursal_id', "=", $sucursal_id)
                                            ->where('concepto_id', "!=", 12)
                                            ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                            ->where('concepto.tipo', "=", 0) //ingreso
                                            ->sum('total');
                round($ingresos_total,2);

                /*

                SELECT SUM(total)
                FROM movimiento as mov
                INNER JOIN concepto as con 
                ON mov.concepto_id = con.id
                WHERE mov.num_caja >= 5 
                and mov.sucursal_id = 1
                and con.tipo = 1 // EGRESO

                */
                //egresos
                $egresos = Movimiento::where('num_caja','>', $maxapertura)
                                            ->where('tipomovimiento_id',1)
                                            ->where('estado', "=", 1)
                                            ->where('sucursal_id', "=", $sucursal_id)
                                            ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                            ->where('concepto.tipo', "=", 1) //egreso
                                            ->where(function($subquery)
                                            {
                                                $subquery->where('concepto_id', '!=', 12)->where('concepto_id', "!=", 15);
                                            })
                                            ->sum('total');
                round($egresos,2);
                //saldo
                $saldo = round($ingresos_total - $egresos, 2);

                $monto_caja = round($montoapertura + $ingresos_total - $egresos - $monto_vuelto, 2);
            }
            $saldo += $montoapertura;
        }else if(!is_null($maxapertura) && is_null($maxcierre)) { //existe apertura pero no existe cierre
            $apertura = Movimiento::where('concepto_id', 1)
                ->where('sucursal_id', "=", $sucursal_id)
                ->where('estado', "=", 1)
                ->where('num_caja',$maxapertura)->first();
            $montoapertura = $apertura->total;
            if($aperturaycierre == 1){ //apertura y cierre diferentes ------- mostrar desde apertura hasta ultimo movimiento
                /*

                SELECT SUM(montoefectivo)
                FROM movimiento as mov
                INNER JOIN concepto as con 
                ON mov.concepto_id = con.id
                WHERE mov.num_caja >= 5 
                and mov.sucursal_id = 1
                and con.tipo = 0 // INGRESO

                */
                
                //ingresos efectivo
                $ingresos_efectivo = Movimiento::where('num_caja','>', $maxapertura)
                                            ->where('tipomovimiento_id',1)
                                            ->where('estado', "=", 1)
                                            ->where('sucursal_id', "=", $sucursal_id)
                                            ->where('concepto_id', "!=", 12)
                                            ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                            ->where('concepto.tipo', "=", 0) //ingreso
                                            ->sum('montoefectivo');
                round($ingresos_efectivo,2);


                //montos vuelto
                $monto_vuelto = Movimiento::where('num_caja','>', $maxapertura)
                ->where('tipomovimiento_id',1)
                ->where('estado', "=", 1)
                ->where('sucursal_id', "=", $sucursal_id)
                /*->where('concepto_id', "=", 12) // montovuelto
                ->orwhere('concepto_id', "=", 15)*/
                ->where(function($subquery)
                    {
                        $subquery->where('concepto_id', '=', 12)->orwhere('concepto_id', "=", 15);
                    })
                ->sum('total');

                round($monto_vuelto,2);
                /*

                SELECT SUM(montovisa)
                FROM movimiento as mov
                INNER JOIN concepto as con 
                ON mov.concepto_id = con.id
                WHERE mov.num_caja >= 5 
                and mov.sucursal_id = 1
                and con.tipo = 0 // INGRESO

                */
                //ingresos tarjeta visa

                $ingresos_visa = Movimiento::where('num_caja','>', $maxapertura)
                                            ->where('tipomovimiento_id',1)
                                            ->where('estado', "=", 1)
                                            ->where('sucursal_id', "=", $sucursal_id)
                                            ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                            ->where('concepto.tipo', "=", 0) //ingreso
                                            ->sum('montovisa');
                round($ingresos_visa,2);

                /*

                SELECT SUM(montomaster)
                FROM movimiento as mov
                INNER JOIN concepto as con 
                ON mov.concepto_id = con.id
                WHERE mov.num_caja >= 5 
                and mov.sucursal_id = 1
                and con.tipo = 0 // INGRESO

                */
                //ingresos tarjeta mastercard

                $ingresos_master = Movimiento::where('num_caja','>', $maxapertura)
                                            ->where('tipomovimiento_id',1)
                                            ->where('estado', "=", 1)
                                            ->where('sucursal_id', "=", $sucursal_id)
                                            ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                            ->where('concepto.tipo', "=", 0) //ingreso
                                            ->sum('montomaster');
                round($ingresos_master,2);

                //ingresos total

                $ingresos_total = Movimiento::where('num_caja','>', $maxapertura)
                                            ->where('tipomovimiento_id',1)
                                            ->where('estado', "=", 1)
                                            ->where('sucursal_id', "=", $sucursal_id)
                                            ->where('concepto_id', "!=", 12)
                                            ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                            ->where('concepto.tipo', "=", 0) //ingreso
                                            ->sum('total');
                round($ingresos_total,2);

                /*

                SELECT SUM(total)
                FROM movimiento as mov
                INNER JOIN concepto as con 
                ON mov.num_caja = con.id
                WHERE mov.serie_numero >= 5 
                and mov.sucursal_id = 1
                and con.tipo = 1 // EGRESO

                */
                //egresos
                $egresos = Movimiento::where('num_caja','>', $maxapertura)
                                            ->where('tipomovimiento_id',1)
                                            ->where('estado', "=", 1)
                                            ->where('sucursal_id', "=", $sucursal_id)
                                            ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                            ->where('concepto.tipo', "=", 1) //egreso
                                            ->where(function($subquery)
                                            {
                                                $subquery->where('concepto_id', '!=', 12)->where('concepto_id', "!=", 15);
                                            })
                                            ->sum('total');
                round($egresos,2);
                //saldo
                $saldo = round($ingresos_total - $egresos, 2);

                $monto_caja = round($montoapertura + $ingresos_total - $egresos - $monto_vuelto, 2);
            }
            $saldo += $montoapertura; // + $monto_vuelto;
        }

        $pagina           = $request->input('page');
        $filas            = $request->input('filas');
        $entidad          = 'Caja';
        $num_caja         = Libreria::getParam($request->input('num_caja'));
        $fechainicio      = Libreria::getParam($request->input('fechainicio'));
        $fechafin         = Libreria::getParam($request->input('fechafin'));
        $resultado        = Movimiento::listar($fechainicio,$fechafin,$num_caja, $sucursal_id, $aperturaycierre, $maxapertura, $maxcierre, 1);
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => 'ANUL', 'numero' => '1');
        $cabecera[]       = array('valor' => 'NRO', 'numero' => '1');
        $cabecera[]       = array('valor' => 'FECHA Y HORA', 'numero' => '1');
        $cabecera[]       = array('valor' => 'CONCEPTO', 'numero' => '1');
        $cabecera[]       = array('valor' => 'PERSONA', 'numero' => '1');
        $cabecera[]       = array('valor' => 'TRABAJADOR', 'numero' => '1');
        $cabecera[]       = array('valor' => 'COMENTARIO', 'numero' => '1');
        $cabecera[]       = array('valor' => 'INGRESOS', 'numero' => '1');
        $cabecera[]       = array('valor' => 'EGRESOS', 'numero' => '1');
        //$cabecera[]       = array('valor' => 'USUARIO', 'numero' => '1');
        
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
            return view($this->folderview.'.list_caja')->with(compact('maxapertura','ultima_apertura', 'ultimo_cierre','sucursal_id', 'tituloIngresarCierres','cant_cajas_sucursales_abiertas', 'caja_principal','montoapertura','monto_vuelto', 'lista', 'ingresos_efectivo', 'monto_caja', 'ingresos_visa', 'ingresos_master' , 'ingresos_total', 'egresos' , 'saldo',  'aperturas' , 'cierres' , 'ruta', 'paginacion', 'inicio', 'fin', 'entidad', 'cabecera', 'aperturaycierre', 'tituloTurnoRepartidor', 'titulo_eliminar', 'titulo_registrar', 'titulo_apertura', 'titulo_cierre', 'ruta'));
        }
        return view($this->folderview.'.list_caja')->with(compact('maxapertura','ultimo_cierre','tituloIngresarCierres','sucursal_id','caja_principal','montoapertura', 'monto_vuelto', 'lista', 'ingresos_efectivo', 'monto_caja', 'ingresos_visa', 'ingresos_master' , 'ingresos_total', 'egresos' , 'saldo', 'aperturas' , 'cierres' , 'ruta', 'aperturaycierre', 'titulo_registrar', 'tituloTurnoRepartidor', 'titulo_apertura', 'titulo_cierre', 'entidad'));
    }

    public function buscarproductosvendidos(Request $request)
    {
        $pagina           = $request->input('page');
        $filas            = $request->input('filas');
        $entidad          = 'Pedidos';
        $sucursal_id         = Libreria::getParam($request->input('sucursal_id_productos'));
        $lista            = array();
        
        $ingresos_repartidor = 0.00;
        $ingresos_credito = 0.00;
        $vueltos_repartidor = 0.00;
        $total_ingresos = 0.00;
        $egresos_repartidor = 0.00;
        $saldo_repartidor = 0.00;

        //max apertura
        $maxapertura = Movimiento::where('concepto_id', 1)
                ->where('sucursal_id', "=", $sucursal_id)
                ->where('estado', "=", 1)
                ->max('num_caja');

        //max cierre
        $maxcierre = Movimiento::where('concepto_id', 2)
                ->where('sucursal_id', "=", $sucursal_id)
                ->where('estado', "=", 1)
                ->max('num_caja');

        $maxima_apertura = Movimiento::where('num_caja',$maxapertura)
                                    ->where('sucursal_id', "=", $sucursal_id)
                                    ->first();

        $maximo_cierre = Movimiento::where('num_caja',$maxcierre)
                                    ->where('sucursal_id', "=", $sucursal_id)
                                    ->first();

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
    
        if (is_null($maxapertura) && is_null($maxcierre)) {
            $lista = null;
        }else{
            $maxima_apertura_id = null;
            $maximo_cierre_id = null;
            if($maxima_apertura != null) $maxima_apertura_id = $maxima_apertura->id;
            if($maximo_cierre != null) $maximo_cierre_id = $maximo_cierre->id;
            $resultado = Movimiento::listardetallespedidosactual($sucursal_id, $aperturaycierre, $maxima_apertura_id, $maximo_cierre_id);
            $lista            = $resultado->get();
        }

        $cabecera         = array();
        $cabecera[]       = array('valor' => 'PRODUCTO', 'numero' => '1');
        $cabecera[]       = array('valor' => 'VENDIDOS', 'numero' => '1');
        $cabecera[]       = array('valor' => 'PRODUCTO SIN ENVASE', 'numero' => '1');
        $cabecera[]       = array('valor' => 'PRODUCTO CON ENVASE', 'numero' => '1');

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
            return view($this->folderview.'.list_productos')->with(compact('lista', 'paginacion', 'inicio', 'sucursal_id', 'maximo_cierre_id','maxima_apertura_id','aperturaycierre', 'ingresos_credito', 'ingresos_repartidor', 'total_ingresos', 'egresos_repartidor', 'vueltos_repartidor','saldo_repartidor','fin', 'entidad', 'cabecera', 'tituloAnulacion', 'tituloDetalle', 'ruta'));
        }
        return view($this->folderview.'.list_productos')->with(compact('lista', 'entidad'));
    }

    public function buscarinventario(Request $request)
    {
        $pagina           = $request->input('page');
        $filas            = $request->input('filas');
        $entidad          = 'Inventario';
        $descripcion      = Libreria::getParam($request->input('name'));
        $sucursal_id      = Libreria::getParam($request->input('sucursal_id_stock'));
        $resultado        = Producto::inventario($descripcion, $sucursal_id);
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => 'NRO', 'numero' => '1');
        $cabecera[]       = array('valor' => 'DESCRIPCIÓN', 'numero' => '1');
        $cabecera[]       = array('valor' => 'STOCK', 'numero' => '1');
        $cabecera[]       = array('valor' => 'TOTAL ENVASES', 'numero' => '1');
        $cabecera[]       = array('valor' => 'ENVASES LLENOS', 'numero' => '1');
        $cabecera[]       = array('valor' => 'ENVASES VACIOS', 'numero' => '1');
        $cabecera[]       = array('valor' => 'ENVASES PRESTADOS', 'numero' => '1');
        
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
            return view($this->folderview.'.list_inventario')->with(compact('lista', 'paginacion', 'inicio', 'fin', 'entidad', 'cabecera', 'ruta'));
        }
        return view($this->folderview.'.list_inventario')->with(compact('lista', 'entidad'));
    }

    public function buscarturnos(Request $request)
    {
        $trabajador = Person::find($request->input('trabajador_id'));
        $cboSucursal = Sucursal::pluck('nombre', 'id')->all();
        return view($this->folderview.'.list_turno')->with(compact('trabajador', 'cboSucursal'));
    }

    public function guardarSucursalRepartidor(Request $request)
    {
        $trabajador = Person::find($request->input('trabajador_id'));
        $trabajador->sucursal_id = $request->input('repartidor_sucursal_id');
        $trabajador->save();
        return $request->input('repartidor_sucursal_id');
    }

    public function buscarcredito(Request $request)
    {
        $pagina           = $request->input('page');
        $filas            = $request->input('filas');
        $entidad          = 'Credito';
        $sucursal_id      = Libreria::getParam($request->input('sucursal_id_credito'));
        $resultado        = Movimiento::iniciocredito( $sucursal_id);
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => 'VER', 'numero' => '1');
        $cabecera[]       = array('valor' => 'FECHA Y HORA', 'numero' => '1');
        $cabecera[]       = array('valor' => 'CLIENTE', 'numero' => '1');
        $cabecera[]       = array('valor' => 'DIRECCIÓN', 'numero' => '1');
        $cabecera[]       = array('valor' => 'SUCURSAL', 'numero' => '1');
        $cabecera[]       = array('valor' => 'TOTAL', 'numero' => '1');
        $cabecera[]       = array('valor' => 'DEBE', 'numero' => '1');
        $cabecera[]       = array('valor' => 'PAGÓ', 'numero' => '1');
        $cabecera[]       = array('valor' => 'DETALLE DE PAGOS', 'numero' => '1');
        $cabecera[]       = array('valor' => 'PAGAR', 'numero' => '1');
        
        $ruta             = $this->rutas;
        if (count($lista) > 0) {
            $clsLibreria     = new Libreria();
            $paramPaginacion = $clsLibreria->generarPaginacion($lista, $pagina, $filas, $entidad);
            $paginacion      = $paramPaginacion['cadenapaginacion'];
            $inicio          = $paramPaginacion['inicio'];
            $fin             = $paramPaginacion['fin'];
            $paginaactual    = $paramPaginacion['nuevapagina'];
            //$lista           = $resultado->paginate($filas);
            $request->replace(array('page' => $paginaactual));
            return view($this->folderview.'.list_credito')->with(compact('lista', 'tituloPagos', 'tituloPagar', 'tituloDetalle','paginacion', 'inicio', 'fin', 'entidad', 'cabecera', 'ruta'));
        }
        return view($this->folderview.'.list_credito')->with(compact('lista', 'entidad'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $entidad_inventario       = 'Inventario';
        $entidad_caja             = 'Caja';
        $entidad_credito          = 'Credito';
        $entidad_productos        = 'Productos';
        $entidad_turnos           = 'Turno';
        $entidad                  = 'Inicio';
        $title                    = $this->tituloAdmin;
        $titulo_registrar         = $this->tituloRegistrar;
        $ruta                     = $this->rutas;
        $user                     = Auth::user();
        $person                   = Person::where('id','=',$user->person_id)->first();
        $cboSucursal              = Sucursal::pluck('nombre', 'id')->all();
        $listar                   = Libreria::getParam($request->input('listar'), 'SI');
        $turnos_iniciados = Turnorepartidor::join('person', 'person.id', '=', 'turno_repartidor.trabajador_id')
                                            ->where('turno_repartidor.estado','I')
                                            ->get();
        //* TRABAJADORES EN TURNO
        $trabajadores_iniciados = array();
        foreach ($turnos_iniciados as $key => $value) {
            $trabajador = Person::find($value->trabajador_id);
            array_push($trabajadores_iniciados, $trabajador);
        }
        //* TODOS TRABAJADORES
        $todos = Person::where('tipo_persona','T')
                        ->get();
        $todos_trabajadores = array();
        foreach ($todos as $key => $value) {
            $trabajador = Person::find($value->id);
            array_push($todos_trabajadores, $trabajador);
        }
        //* TRABAJADORES POR INICIAR TURNO
        $empleados = $todos_trabajadores;
        $formData                 = array('profile.update', $user->id);
        $formData                 = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal' , 'id' => 'formMantenimientoPassword', 'autocomplete' => 'off');
        return view($this->folderview.'.admin')->with(compact('entidad', 'entidad_credito', 'empleados', 'entidad_turnos', 'turnos_iniciados','entidad_caja', 'entidad_productos' , 'entidad_inventario','cboSucursal','formData', 'user','listar', 'person', 'title', 'titulo_registrar', 'ruta'));
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

    public function save(Request $request)
    {
        $id             = Auth::user()->person_id;
        $existe = Libreria::verificarExistencia($id, 'person');
        if ($existe !== true) {
            return $existe;
        }
        $reglas = array(
            'dni'       => 'required|max:8',
            'registro'       => 'required|max:4',
            'nombres'    => 'required|max:100',
            'apellido_pat'    => 'required|max:100',
            'apellido_mat'    => 'required|max:100',
            );
        $validacion = Validator::make($request->all(),$reglas);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $error = DB::transaction(function() use($request, $id){
            $personal                = Person::find($id);
            $personal->dni           = $request->input('dni');
            $personal->registro      = $request->input('registro');
            $personal->nombres       = strtoupper($request->input('nombres'));
            $personal->apellido_pat  = strtoupper($request->input('apellido_pat'));
            $personal->apellido_mat  = strtoupper($request->input('apellido_mat'));
            $personal->save();
            
        });
        return is_null($error) ? "OK" : $error;
    }

    public function update(Request $request, $id){
        $error = null;
        $success = "";
        $rules = [
            'mypassword' => 'required',
            'password' => 'required|confirmed|min:6|max:18',
        ];
        
        $messages = [
            'mypassword.required' => 'El campo contraseña actual es requerido.',
            'password.required' => 'El campo nueva contraseña es requerido.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.min' => 'El mínimo permitido son 6 caracteres.',
            'password.max' => 'El máximo permitido son 18 caracteres.',
        ];
        
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()){
            return $validator->messages()->toJson();
        }
        else{
            if (Hash::check($request->mypassword, Auth::user()->password) && !Hash::check($request->password, Auth::user()->password) ){
                $error = DB::transaction(function() use($request, $id){
                    $usuario           = User::find($id);
                    $usuario->password = bcrypt($request->get('password'));
                    $usuario->save();
                });
                return is_null($error) ? "OK" : $error;
            }
            else if(Hash::check($request->password, Auth::user()->password))
            {
                $error =  'IGUAL';
                return $error;
            }
            else
            {
                $error =  'ERROR';
                return $error;
            }
        }
    }
}