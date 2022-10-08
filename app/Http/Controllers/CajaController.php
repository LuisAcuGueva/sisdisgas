<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Movimiento;
use App\Sucursal;
use App\Turnorepartidor;
use App\Concepto;
use App\Person;
use App\Detalleturnopedido;
use App\Kardex;
use App\Stock;
use App\Producto;
use App\Detallepagos;
use App\Tipodocumento;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Elibyy\TCPDF\Facades\TCPDF;

class CajaController extends Controller
{

    protected $folderview      = 'app.caja';
    protected $tituloAdmin     = 'Caja';
    protected $tituloRegistrar = 'Registrar Movimiento de Caja';
    protected $tituloEliminar  = 'Anular Movimiento de Caja';
    protected $tituloApertura  = 'Apertura de Caja';
    protected $tituloTurnoRepartidor  = 'Iniciar Turno de Repartidor';
    protected $tituloCierre    = 'Cierre de Caja';
    protected $tituloIngresarCierres    = 'Ingresar Cajas de otras sucursales';
    protected $tituloPersona   = 'Registrar Nueva Persona';
    protected $rutas           = array('create' => 'caja.create', 
            'persona'  => 'caja.persona',
            'guardarpersona'  => 'caja.guardarpersona',
            'delete'   => 'caja.eliminar',
            'search'   => 'caja.buscar',
            'index'    => 'caja.index',
            'apertura' => 'caja.apertura',
            'turnoRepartidor' => 'caja.turnoRepartidor',
            'cierre'   => 'caja.cierre',
            'ingresarcierres'   => 'caja.ingresarcierres',
            'repetido' => 'caja.repetido',
            'aperturaycierre' => 'caja.aperturaycierre',
        );

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function buscar(Request $request)
    {
        $sucursal_id      = Libreria::getParam($request->input('sucursal_id'));
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
        
        $tituloTurnoRepartidor = $this->tituloTurnoRepartidor;
        $titulo_eliminar  = $this->tituloEliminar;
        $titulo_registrar = $this->tituloRegistrar;
        $titulo_apertura  = $this->tituloApertura;
        $titulo_cierre    = $this->tituloCierre;
        $tituloIngresarCierres  = $this->tituloIngresarCierres;
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
            return view($this->folderview.'.list')->with(compact('maxapertura','ultima_apertura', 'ultimo_cierre','sucursal_id', 'tituloIngresarCierres','cant_cajas_sucursales_abiertas', 'caja_principal','montoapertura','monto_vuelto', 'lista', 'ingresos_efectivo', 'monto_caja', 'ingresos_visa', 'ingresos_master' , 'ingresos_total', 'egresos' , 'saldo',  'aperturas' , 'cierres' , 'ruta', 'paginacion', 'inicio', 'fin', 'entidad', 'cabecera', 'aperturaycierre', 'tituloTurnoRepartidor', 'titulo_eliminar', 'titulo_registrar', 'titulo_apertura', 'titulo_cierre', 'ruta'));
        }
        return view($this->folderview.'.list')->with(compact('maxapertura','ultimo_cierre','tituloIngresarCierres','sucursal_id','caja_principal','montoapertura', 'monto_vuelto', 'lista', 'ingresos_efectivo', 'monto_caja', 'ingresos_visa', 'ingresos_master' , 'ingresos_total', 'egresos' , 'saldo', 'aperturas' , 'cierres' , 'ruta', 'aperturaycierre', 'titulo_registrar', 'tituloTurnoRepartidor', 'titulo_apertura', 'titulo_cierre', 'entidad'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $entidad          = 'Caja';
        $title            = $this->tituloAdmin;
        $titulo_registrar = $this->tituloRegistrar;
        $titulo_apertura  = $this->tituloApertura;
        $titulo_cierre    = $this->tituloCierre;
        $ruta             = $this->rutas;
        $cboSucursal      = Sucursal::pluck('nombre', 'id')->all();
        return view($this->folderview.'.admin')->with(compact('entidad', 'cboSucursal' , 'title', 'titulo_registrar', 'titulo_apertura' , 'titulo_cierre' , 'ruta'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $titulo_persona   = $this->tituloPersona;
        $ruta             = $this->rutas;
        $listar       = Libreria::getParam($request->input('listar'), 'NO');
        $entidad      = 'Caja';
        $movimiento   = null;
        $formData     = array('caja.store');
        $formData     = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $sucursal_id  = $request->input('sucursal_id');
        $user = Auth::user();
        $empresa_id = $user->empresa_id;
        $num_caja   = Movimiento::where('sucursal_id', '=' , $sucursal_id)->max('num_caja') + 1;
        $anonimo = Person::find(1);
        $boton        = 'Guardar'; 
        return view($this->folderview.'.mant')->with(compact('anonimo','titulo_persona','ruta','num_caja' , 'movimiento', 'formData', 'entidad', 'boton', 'listar'));
    }

    public function apertura(Request $request)
    {
        $listar       = Libreria::getParam($request->input('listar'), 'NO');
        $entidad      = 'Caja';
        $movimiento   = null;
        $formData     = array('caja.store');
        $formData     = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        
        $sucursal_id  = $request->input('sucursal_id');
        $user = Auth::user();
        $persona_id = $user->person_id;
        $num_caja   = Movimiento::where('sucursal_id', '=' , $sucursal_id)->max('num_caja') + 1;

        $maxcierre = Movimiento::where('concepto_id', 2)
                ->where('sucursal_id', "=", $sucursal_id)
                ->where('estado', "=", 1)
                ->max('num_caja');
        $cierre_ultimo = Movimiento::where('concepto_id', 2)
                    ->where('sucursal_id', "=", $sucursal_id)
                    ->where('estado', "=", 1)
                    ->where('num_caja',"=", $maxcierre)
                    ->first();
                    
        $boton        = 'Guardar'; 
        return view($this->folderview.'.apertura')->with(compact('persona_id' , 'num_caja', 'movimiento', 'cierre_ultimo', 'formData', 'entidad', 'boton', 'listar'));
    }

    public function turnoRepartidor(Request $request)
    {
        $listar       = Libreria::getParam($request->input('listar'), 'NO');
        $entidad      = 'Caja';
        $movimiento   = null;
        $formData     = array('caja.store');
        $formData     = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        
        $sucursal_id  = $request->input('sucursal_id');
        $user = Auth::user();
        $persona_id = $user->person_id;
        $num_caja   = Movimiento::where('sucursal_id', '=' , $sucursal_id)->max('num_caja') + 1;

        $maxcierre = Movimiento::where('concepto_id', 2)
                ->where('sucursal_id', "=", $sucursal_id)
                ->where('estado', "=", 1)
                ->max('num_caja');
        $cierre_ultimo = Movimiento::where('concepto_id', 2)
                    ->where('sucursal_id', "=", $sucursal_id)
                    ->where('estado', "=", 1)
                    ->where('num_caja',"=", $maxcierre)
                    ->first();

        $turnos_iniciados = Turnorepartidor::join('person', 'person.id', '=', 'turno_repartidor.trabajador_id')
                                            ->where('turno_repartidor.estado','I')
                                            ->where('person.sucursal_id', $sucursal_id)
                                            ->get();
        // TRABAJADORES EN TURNO
        $trabajadores_iniciados = array();
        foreach ($turnos_iniciados as $key => $value) {
            $trabajador = Person::find($value->trabajador_id);
            array_push($trabajadores_iniciados, $trabajador);
        }
        // TODOS TRABAJADORES
        $todos = Person::where('tipo_persona','T')
                        ->where('sucursal_id', $sucursal_id)
                        ->get();
        $todos_trabajadores = array();
        foreach ($todos as $key => $value) {
            $trabajador = Person::find($value->id);
            array_push($todos_trabajadores, $trabajador);
        }

        // TRABAJADORES POR INICIAR TURNO
        $trabajadores_sinturno = array_diff($todos_trabajadores, $trabajadores_iniciados);
        $boton        = 'Guardar'; 
        return view($this->folderview.'.turnoRepartidor')->with(compact('persona_id' , 'trabajadores_sinturno' ,'num_caja', 'movimiento', 'cierre_ultimo', 'formData', 'entidad', 'boton', 'listar'));
    }

    public function cierre(Request $request)
    {
        $listar       = Libreria::getParam($request->input('listar'), 'NO');
        $entidad      = 'Caja';
        $movimiento   = null;
        $formData     = array('caja.store');
        $formData     = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');

        $sucursal_id  = $request->input('sucursal_id');
        $user = Auth::user();
        $persona_id = $user->person_id;
        $num_caja   = Movimiento::where('sucursal_id', '=' , $sucursal_id)->max('num_caja') + 1;

        $sucursal = Sucursal::find(1);

        $lstsucursal = Sucursal::all();

        foreach ($lstsucursal as $key => $value) {
            if($value == $sucursal){
               unset($lstsucursal[$key]);
            }
        }

        $cant_cajas_sucursales_abiertas = 0;

        $sucursales_cerradas_no_ingresadas = 0;

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


            if($aperturas_sucursal == $cierres_sucursal){ // habilitar apertura de caja = caja cerrada

                $cierre_max_sucursal = Movimiento::where('concepto_id', 2)
                ->where('sucursal_id', "=", $value->id)
                ->where('estado', "=", 1)
                ->max('id');

                $cierre_max  = Movimiento::find($cierre_max_sucursal);

                if(!is_null($cierre_max)){

                    if( $cierre_max->ingreso_caja_principal == null ){

                        $sucursales_cerradas_no_ingresadas++;

                    }

                }


            }else if($aperturas_sucursal != $cierres_sucursal){ //habilitar cierre de caja = caja abierta
                $cant_cajas_sucursales_abiertas++;
            }

        }
        

        $turnos_iniciados = Turnorepartidor::where('estado','I')->get();
        // TRABAJADORES EN TURNO
        $trabajadores_iniciados = array();
        foreach ($turnos_iniciados as $key => $value) {
            $trabajador = Person::find($value->trabajador_id);
            if($trabajador->sucursal_id == $sucursal_id){
                array_push($trabajadores_iniciados, $trabajador);
            }
        }

        if(!empty($trabajadores_iniciados)){
            $turnos_cerrados = "NO";
        }else{
            $turnos_cerrados = "SI";
        }

        $boton        = 'Guardar';
        return view($this->folderview.'.cierre')->with(compact('sucursales_cerradas_no_ingresadas', 'cant_cajas_sucursales_abiertas', 'persona_id' , 'num_caja', 'sucursal_id','movimiento', 'turnos_cerrados' ,'formData', 'entidad', 'boton', 'listar'));
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
        if($request->input('concepto_id') == 1 ){
            $reglas     = array('num_caja' => 'required|numeric',
                                'fecha'      => 'required',
                                'concepto_id'   => 'required',
                                'persona_id' => 'required',
                                'monto'      => 'required|numeric',
                            );
        }else{
            $reglas     = array('num_caja' => 'required|numeric',
                                'fecha'      => 'required',
                                'concepto_id'   => 'required',
                                'persona_id' => 'required',
                                'total'      => 'required|numeric',
                            );
        }
        $mensajes   = array();
        $validacion = Validator::make($request->all(), $reglas, $mensajes);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $error = DB::transaction(function() use($request){
            $movimiento       = new Movimiento();
            $movimiento->tipomovimiento_id = 1;
            $movimiento->concepto_id    = $request->input('concepto_id');
            $movimiento->num_caja   = $request->input('num_caja');

            if($request->input('concepto_id') != 17){

                if($request->input('concepto_id') == 1){
                    $movimiento->total          = $request->input('monto');
                    $movimiento->subtotal          = $request->input('monto');
                }else{
                    $movimiento->total             = $request->input('total');
                    $movimiento->subtotal          = $request->input('total');
                    $movimiento->montoefectivo    = $request->input('total');
                }

                $movimiento->estado         = 1;

                $trabajador = Person::find($request->input('persona_id'));
                if($request->input('concepto_id') == 1 || $request->input('concepto_id') == 2 || $request->input('concepto_id') == 12 || $request->input('concepto_id') == 14 || $request->input('concepto_id') == 13 || $request->input('concepto_id') == 15){
                    if($trabajador->tipo_persona == "T"){
                        $movimiento->trabajador_id     = $request->input('persona_id');
                    }
                }else{
                    $movimiento->persona_id     = $request->input('persona_id');
                }

                $user           = Auth::user();
                $movimiento->usuario_id     = $user->id;
                $movimiento->sucursal_id   = $request->input('sucursal');
                $movimiento->comentario     = strtoupper($request->input('comentario'));
                $movimiento->save();

            }else{

                $caja_cerrada = Movimiento::find($request->input("caja_cerrada_sin_ingresar"));

                $movimiento->total  = $caja_cerrada->total;

                $movimiento->estado         = 1;

                $movimiento->trabajador_id     = $caja_cerrada->trabajador_id;

                $user           = Auth::user();

                $movimiento->usuario_id     = $user->id;
                $movimiento->sucursal_id   = $request->input('sucursal');
                $movimiento->comentario     = "INGRESO DE CAJA DE " . $caja_cerrada->sucursal->nombre ." - ".strtoupper($request->input('comentario'));
                $movimiento->save();

                $caja_cerrada->ingreso_caja_principal = 1;
                $caja_cerrada->ingreso_cierre_id = $movimiento->id;

                $caja_cerrada->save();


            }

            if($request->input('concepto_id') == 12  || $request->input('concepto_id') == 14 || $request->input('concepto_id') == 13 || $request->input('concepto_id') == 15){
                $sucursal_id = $request->input('sucursal');
                $maxapertura = Movimiento::where('concepto_id', 1)
                ->where('sucursal_id', "=", $sucursal_id)
                ->where('estado', "=", 1)
                ->max('num_caja');
                $apertura = Movimiento::where('concepto_id', 1)
                        ->where('sucursal_id', "=", $sucursal_id)
                        ->where('estado', "=", 1)
                        ->where('num_caja',$maxapertura)->first();
                $turno_repartidor = Turnorepartidor::where('estado','I')->where('trabajador_id', $request->input('persona_id'))->first();
                if(is_null($turno_repartidor)){
                    $turno_repartidor = new Turnorepartidor();
                    $turno_repartidor->estado    = "I";
                    $turno_repartidor->inicio = date('Y-m-d H:i:s');
                    $turno_repartidor->apertura_id = $apertura->id;
                    //$turno_repartidor->vuelto_id = $movimiento->id;
                    $turno_repartidor->trabajador_id = $request->input('persona_id');
                    $turno_repartidor->save();
                }

                $detalle_turno_pedido =  new Detalleturnopedido();
                $detalle_turno_pedido->pedido_id = $movimiento->id;
                $detalle_turno_pedido->turno_id = $turno_repartidor->id;
                $detalle_turno_pedido->save();
            }
        });
        return is_null($error) ? "OK" : $error;
    }

    public function ingresarcierres(Request $request)
    {
        $listar       = Libreria::getParam($request->input('listar'), 'NO');
        $entidad      = 'Caja';
        $movimiento   = null;
        $formData     = array('caja.store');
        $formData     = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        
        $sucursal_id  = $request->input('sucursal_id');
        $user = Auth::user();
        $persona_id = $user->person_id;
        $num_caja   = Movimiento::where('sucursal_id', '=' , $sucursal_id)->max('num_caja') + 1;

        $maxcierre = Movimiento::where('concepto_id', 2)
                ->where('sucursal_id', "=", $sucursal_id)
                ->where('estado', "=", 1)
                ->max('num_caja');

        $cierre_ultimo = Movimiento::where('concepto_id', 2)
                    ->where('sucursal_id', "=", $sucursal_id)
                    ->where('estado', "=", 1)
                    ->where('num_caja',"=", $maxcierre)
                    ->first();
                    
        $boton        = 'Guardar'; 

        $lstsucursal = Sucursal::all();

        $sucursal_principal = Sucursal::find(1);

        foreach ($lstsucursal as $key => $value) {
            if($value == $sucursal_principal){
               unset($lstsucursal[$key]);
            }
        }

        $cierres_sucursales = array();

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

            if($aperturas_sucursal != 0){

                if($aperturas_sucursal == $cierres_sucursal){ // habilitar apertura de caja = caja cerrada

                    $cierre_max_sucursal = Movimiento::where('concepto_id', 2)
                            ->where('sucursal_id', "=", $value->id)
                            ->where('estado', "=", 1)
                            ->max('id');

                    $cierre_max  = Movimiento::find($cierre_max_sucursal);

                    if( $cierre_max->ingreso_caja_principal != 1 ){

                        array_push($cierres_sucursales, $cierre_max);

                    }

                }else if($aperturas_sucursal != $cierres_sucursal){ //habilitar cierre de caja = caja abierta
                    unset($lstsucursal[$key]);
                }
            }

        }


        return view($this->folderview.'.ingresarcierres')->with(compact('cierres_sucursales','persona_id' , 'num_caja', 'movimiento', 'cierre_ultimo', 'formData', 'entidad', 'boton', 'listar'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function saldoCaja(Request $request)
    {
        $sucursal_id      = Libreria::getParam($request->input('sucursal_id'));

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

        $aperturaycierre = null;

        if($aperturas == $cierres){ // habilitar apertura de caja
            $aperturaycierre = 0;
        }else if($aperturas != $cierres){ //habilitar cierre de caja
            $aperturaycierre = 1;
        }

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

        return $monto_caja;

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

            if($movimiento->concepto_id == 15){ //* Monto vuelto al iniciar turno
                $detalle_turno = Detalleturnopedido::where('pedido_id',$movimiento->id)->first();
                $turno = Turnorepartidor::find($detalle_turno->turno_id);
                $turno->delete();
            }

            if($movimiento->concepto_id == 17){ //* Ingreso de caja de otra sucursal
                $caja_cerrada = Movimiento::where('ingreso_cierre_id',$id)->first();
                $caja_cerrada->ingreso_caja_principal = null;
                $caja_cerrada->ingreso_cierre_id = null;
                $caja_cerrada->save();
            }

            if($movimiento->venta_id != null){ // mov de caja con pedido
               
                $pagos = Detallepagos::where('pedido_id', $movimiento->venta_id)
                                        ->join('movimiento', 'detalle_pagos.pago_id', '=', 'movimiento.id')                       
                                        ->where('movimiento.estado', 1)->get();

                if( count($pagos) == 1 ) { // pedido a credito con un solo pago, solo así se puede eliminar

                    $movimientoventa = Movimiento::find($movimiento->venta_id);
                    $movimientoventa->estado = 0;
                    $movimientoventa->comentario_anulado  = strtoupper($request->input('motivo'));  
                    $movimientoventa->save();

                    $kardexs = Kardex::rightjoin('detalle_mov_almacen', 'kardex.detalle_mov_almacen_id', '=', 'detalle_mov_almacen.id')
                            ->where('detalle_mov_almacen.movimiento_id', $movimiento->venta_id)
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

                }else if( count($pagos) == 0){ //pedido que no es a crédito

                    $movimientoventa = Movimiento::find($movimiento->venta_id);
                    $movimientoventa->estado = 0;
                    $movimientoventa->comentario_anulado  = strtoupper($request->input('motivo'));  
                    $movimientoventa->save();

                    $kardexs = Kardex::rightjoin('detalle_mov_almacen', 'kardex.detalle_mov_almacen_id', '=', 'detalle_mov_almacen.id')
                            ->where('detalle_mov_almacen.movimiento_id', $movimiento->venta_id)
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

                }

            }

            if($movimiento->compra_id != null){ // mov de caja con compra
               
                $pagos = Detallepagos::where('pedido_id', $movimiento->compra_id)
                                        ->join('movimiento', 'detalle_pagos.pago_id', '=', 'movimiento.id')                       
                                        ->where('movimiento.estado', 1)->get();

                if( count($pagos) == 1 ) { // compra a credito con un solo pago, solo así se puede eliminar

                    $movimientocompra = Movimiento::find($movimiento->compra_id);
                    $movimientocompra->estado = 0;
                    $movimientocompra->comentario_anulado  = strtoupper($request->input('motivo'));  
                    $movimientocompra->save();

                    $kardexs = Kardex::rightjoin('detalle_mov_almacen', 'kardex.detalle_mov_almacen_id', '=', 'detalle_mov_almacen.id')
                            ->where('detalle_mov_almacen.movimiento_id', $movimiento->compra_id)
                            ->get();

                    foreach ($kardexs as $key => $value) {
                        $stock = Stock::where('producto_id', $value->producto_id )->where('sucursal_id', $movimiento->sucursal_id)->first();
                        
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

                }else if( count($pagos) == 0){ //pedido que no es a crédito

                    $movimientocompra = Movimiento::find($movimiento->compra_id);
                    $movimientocompra->estado = 0;
                    $movimientocompra->comentario_anulado  = strtoupper($request->input('motivo'));  
                    $movimientocompra->save();

                    $kardexs = Kardex::rightjoin('detalle_mov_almacen', 'kardex.detalle_mov_almacen_id', '=', 'detalle_mov_almacen.id')
                            ->where('detalle_mov_almacen.movimiento_id', $movimiento->compra_id)
                            ->get();

                    foreach ($kardexs as $key => $value) {
                        $stock = Stock::where('producto_id', $value->producto_id )->where('sucursal_id', $movimiento->sucursal_id)->first();
                        
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

                }

            }

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
        $entidad  = 'Caja';
        $formData = array('route' => array('caja.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Anular';
        $mensaje  = '<blockquote><p class="text-danger">¿Está seguro de anular el registro?</p></blockquote>';
        return view('app.caja.confirmarAnular')->with(compact( 'mensaje' ,'modelo', 'formData', 'entidad', 'boton', 'listar'));
    }

    public function clienteautocompletar($searching)
    {
        $resultado = Person::where(DB::raw('CONCAT(apellido_pat," ",apellido_mat," ",nombres)'), 'LIKE', '%'.strtoupper($searching).'%')
        ->where('tipo_persona','C')
        ->whereNull('person.deleted_at')
        ->orderBy('apellido_pat', 'ASC')
        ->orderBy('apellido_mat', 'ASC')
        ->orderBy('nombres', 'ASC');
        $list      = $resultado->get();
        $data = array();
        foreach ($list as $key => $value) {
            $data[] = array(
                            'label' => $value->nombres.' '.$value->apellido_pat.' '.$value->apellido_mat,
                            'id'    => $value->id,
                            'value' => $value->nombres.' '.$value->apellido_pat.' '.$value->apellido_mat,
                        );
        }
        return json_encode($data);
    }

    public function empleadoautocompletar($searching)
    {
        $entidad    = 'Cliente';
        $resultado = Person::where(DB::raw('CONCAT(apellido_pat," ",apellido_mat," ",nombres)'), 'LIKE', '%'.strtoupper($searching).'%')
        ->where('tipo_persona','T')
        ->whereNull('person.deleted_at')
        ->orderBy('apellido_pat', 'ASC')
        ->orderBy('apellido_mat', 'ASC')
        ->orderBy('nombres', 'ASC');
        $list      = $resultado->get();
        $data = array();
        foreach ($list as $key => $value) {
            $data[] = array(
                            'label' => $value->nombres.' '.$value->apellido_pat.' '.$value->apellido_mat,
                            'id'    => $value->id,
                            'value' => $value->nombres.' '.$value->apellido_pat.' '.$value->apellido_mat,
                        );
        }
        return json_encode($data);
    }

    public function generarConcepto(Request $request)
    {
        //QUITAR APERTURA Y CIERRE DE CAJA
        $tipoconcepto_id  = $request->input('tipoconcepto_id');   
        $conceptos = Concepto::where('tipo', '=' , $tipoconcepto_id)
                                ->where('id','!=',1)
                                ->where('id','!=',2)
                                //->where('id','!=',3)
                                ->where('id','!=',11)
                                ->where('id','!=',12)
                                ->where('id','!=',13)
                                ->where('id','!=',14)
                                ->where('id','!=',15)
                                ->where('id','!=',17)
                                ->where('id','!=',18)
                                ->orderBy('concepto','ASC')->get();
        $html = "";
        foreach($conceptos as $key => $value){
            $html = $html . '<option value="'. $value->id .'">'. $value->concepto .'</option>';
        }
        return $html;
    }

    public function persona(Request $request)
    {
        $listar         = Libreria::getParam($request->input('listar'), 'NO');
        $entidad        = 'Persona'; //es personamaestro
        $persona        = null;
        $formData       = array('caja.guardarpersona');
        $formData       = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton          = 'Guardar'; 
        $ruta             = $this->rutas;
        $accion = 0;
        $tipo_persona     = array('T' => 'Trabajador',
            'C'      => 'Cliente',
            'P'   => 'Proveedor',
        );
        return view($this->folderview.'.persona')->with(compact( 'accion' , 'ruta', 'persona', 'tipo_persona', 'formData', 'entidad', 'boton', 'listar'));
    }

    public function guardarpersona(Request $request)
    {
        $listar     = Libreria::getParam($request->input('listar'), 'NO');
        $cant = $request->input('cantc');
        $reglas = array(
            );
        if($cant == 8){
            $reglas = array(
                'dni'       => 'required|max:11',
                'nombres'    => 'required|max:100',
                'apellido_pat'    => 'required|max:100',
                'apellido_mat'    => 'required|max:100',
                );
        }else if($cant == 11){
            $reglas = array(
                'dni'       => 'required|max:11',
                'razon_social'    => 'required|max:200',
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
            $cliente->tipo_persona  = $request->input('tipo_persona');
            $cliente->save();
        });
        return is_null($error) ? "OK" : $error;
    }

    //REPORTES 

    public function pdfDetalleCierre(Request $request){

        $sucursal_id = $request->input('sucursal_id');
        $sucursal    = Sucursal::find($sucursal_id);
        $rst         = Movimiento::where('tipomovimiento_id','=',1)->where('sucursal_id','=',$sucursal->id)->where('concepto_id','=',1)->orderBy('id','DESC')->limit(1)->first();

        if(count($rst)>0){
            $apertura_ultima = $rst->id;
        }else{
            $apertura_ultima = 0;
        }

        $nombrepdf = "DETALLE CIERRE DE CAJA ACTUAL - SUCURSAL " . $sucursal->nombre;

        $totalingresos     = 0;
        $totalvuelto   = 0;
        $totalegresos  = 0;
        
        $pdf = new TCPDF();
        $pdf::SetTitle($nombrepdf);
        $pdf::AddPage('L');
        $pdf::SetFont('helvetica','B',12);
        $pdf::Cell(0,10,$nombrepdf,0,0,'C');
        $pdf::Ln();
        $pdf::SetFont('helvetica','B',7);
        //$pdf::MultiCell(10, 7,'NRO', 1, 'C', 0, 0, '', '', true, 0, false, true, 14, 'M');
        $pdf::Cell(10,7,utf8_decode("NRO"),1,0,'C');

        //$pdf::MultiCell(25, 7,'FECHA', 1, 'C', 0, 0, '', '', true, 0, false, true, 14, 'M');
        $pdf::Cell(25,7,utf8_decode("FECHA"),1,0,'C');

        //$pdf::MultiCell(50, 7,'PERSONA', 1, 'C', 0, 0, '', '', true, 0, false, true, 14, 'M');
        $pdf::Cell(50,7,utf8_decode("TRABAJADOR"),1,0,'C');

        //$pdf::MultiCell(28, 7,'COMPROBANTE', 1, 'C', 0, 0, '', '', true, 0, false, true, 14, 'M');
        $pdf::Cell(28,7,utf8_decode("COMPROBANTE"),1,0,'C');

        //$pdf::MultiCell(45, 7,'CONCEPTO', 1, 'C', 0, 0, '', '', true, 0, false, true, 14, 'M');
        $pdf::Cell(45,7,utf8_decode("CONCEPTO"),1,0,'C');

        //$pdf::MultiCell(45, 7,'TRABAJADOR', 1, 'C', 0, 0, '', '', true, 0, false, true, 14, 'M');
        $pdf::Cell(50,7,utf8_decode("PERSONA / CLIENTE"),1,0,'C');

        //$pdf::MultiCell(15, 7,'EGRESO', 1, 'C', 0, 0, '', '', true, 0, false, true, 14, 'M');
        $pdf::Cell(15,7,utf8_decode("EGRESO"),1,0,'C');

        //$pdf::Ln();
        //$pdf::Cell(218,7,utf8_decode(""),0,0,'C');
        $pdf::Cell(15,7,utf8_decode("INGRESO"),1,0,'C');

        $pdf::Cell(45,7,utf8_decode("COMENTARIO"),1,0,'C');
        $pdf::Ln();

        $pdf::SetFont('helvetica','B',8.5);
        $pdf::Cell(283,7,'APERTURA DE CAJA',1,0,'L');
        $pdf::Ln();
        $pdf::SetFont('helvetica','',6);  
        $pdf::Cell(10,7, $rst->num_caja ,1,0,'C');                 
        $pdf::Cell(25,7,date("d/m/Y h:i:s a", strtotime($rst->fecha)),1,0,'C');
        $persona = Person::find($rst->trabajador_id);
        $nombrepersona = "";
        if(!is_null($persona)){
            if($persona->dni == null){
                $nombrepersona = $persona->razon_social;
            }else{
                $nombrepersona = $persona->apellido_pat . " " . $persona->apellido_mat . " " . $persona->nombres;
            }
        }
        $pdf::Cell(50,7,$nombrepersona,1,0,'L');
        $pdf::Cell(10,7, "" ,1,0,'C');
        $pdf::Cell(18,7, "" ,1,0,'C');
        $concepto = Concepto::find($rst->concepto_id);
        $pdf::Cell(45,7,$concepto->concepto,1,0,'L');
        $pdf::Cell(50,7, "" ,1,0,'L');
        $pdf::Cell(15,7,"",1,0,'C');

        if($rst->estado == 1) {
            $valuetp = number_format($rst->total,2,'.','');
            if($valuetp == 0){$valuetp='';}
            $pdf::Cell(15,7,$valuetp,1,0,'R');                    
            $pdf::Cell(45,7,$rst->comentario,1,0,'L');     
        } else {
            $pdf::Cell(60,7,'ANULADO',1,0,'C');                    
        }  
        $pdf::Ln(); 
        $pdf::SetFont('helvetica','B',8.5);
        $pdf::Cell(223,7,'SUBTOTAL',1,0,'R');
        $pdf::Cell(15,7,number_format($rst->total,2,'.',''),1,0,'R');
        $pdf::Ln();   

        //MONTO VUELTO

        $rstvueltos = Movimiento::leftjoin('concepto','movimiento.concepto_id','=','concepto.id')
                            ->Where(function($subquery)
                            {
                                $subquery->where('concepto.id', '=', 12)->orwhere('concepto.id', '=', 15);
                            })
                            //->where('movimiento.estado','=', 1)
                            ->where('movimiento.sucursal_id', '=', $sucursal_id)
                            ->where('movimiento.tipomovimiento_id', '=', 1)
                            ->where('movimiento.id', '>=', $apertura_ultima);

        $rstvueltos = $rstvueltos->orderBy('movimiento.num_caja', 'asc');

        $listarvueltos        = $rstvueltos->get();

        if(count($listarvueltos)>0){
            $pdf::SetFont('helvetica','B',8.5);
            $pdf::Cell(283,7,'VUELTOS A REPARTIDORES',1,0,'L');
            $pdf::Ln();
            foreach ($listarvueltos as $row) { 
                $pdf::SetFont('helvetica','',6);   
                $pdf::Cell(10,7, $row['num_caja'] ,1,0,'C');                                 
                $pdf::Cell(25,7,date("d/m/Y h:i:s a", strtotime($row['fecha'])),1,0,'C');
                if(!is_null($row['persona_id'])){
                    $persona = Person::find($row['persona_id']);
                    if($persona->dni == null){
                        $nombrepersona = $persona->razon_social;
                    }else{
                        $nombrepersona = $persona->apellido_pat . " " . $persona->apellido_mat . " " . $persona->nombres;
                    }
                }else if(!is_null($row['trabajador_id'])){
                    $persona = Person::find($row['trabajador_id']);
                    if($persona->dni == null){
                        $nombrepersona = $persona->razon_social;
                    }else{
                        $nombrepersona = $persona->apellido_pat . " " . $persona->apellido_mat . " " . $persona->nombres;
                    }
                }
                $pdf::Cell(50,7,$nombrepersona,1,0,'L');
                $tipodoc = Tipodocumento::find($row['tipodocumento_id']);
                $pdf::Cell(10,7,"",1,0,'C');
                $pdf::Cell(18,7, $row['num_venta'] ,1,0,'C');
                $concepto = Concepto::find($row['concepto_id']);
                $pdf::Cell(45,7,$concepto->concepto,1,0,'L');
                //$trabajador = Persona::find($row['trabajador_id']);
                $pdf::Cell(50,7, "" ,1,0,'L');
                if($row['estado'] == 1) {
                    $pdf::Cell(15,7,number_format($row['total'],2,'.',''),1,0,'R');
                    $totalvuelto += number_format($row['total'],2,'.','');
                    $pdf::Cell(15,7,"",1,0,'R');
                    $pdf::Cell(45,7,$row->comentario,1,0,'L');   
                }else{
                    $pdf::Cell(30,7,'ANULADO',1,0,'C');
                    $pdf::Cell(45,7,$row->comentario_anulado,1,0,'L');   
                }
                $pdf::Ln();                  
            } 
            $pdf::SetFont('helvetica','B',8.5);
            $pdf::Cell(208,7,'SUBTOTAL',1,0,'R');
            $pdf::Cell(15,7,number_format($totalvuelto,2,'.',''),1,0,'R');
           /* $pdf::Cell(15,7,"",1,0,'R');
            $pdf::Cell(15,7,"",1,0,'R');
            $pdf::Cell(15,7,number_format($subtotalefectivo+$subtotalvisa+$subtotalmaster,2,'.',''),1,0,'R');*/
            $pdf::Ln();                   
        }

        // INGRESOS

        $ingresos = Movimiento::leftjoin('concepto','movimiento.concepto_id','=','concepto.id')
                            ->where('concepto.tipo','=', 0)
                            //->where('concepto.id','!=', 3)
                            ->where('concepto.id','!=', 1)
                            //->where('movimiento.estado','=', 1)
                            ->where('movimiento.sucursal_id', '=', $sucursal_id)
                            ->where('movimiento.tipomovimiento_id', '=', 1)
                            ->where('movimiento.id', '>=', $apertura_ultima);

        $ingresos = $ingresos->orderBy('movimiento.num_caja', 'asc');

        $listaotrosingresos        = $ingresos->get();

        if(count($listaotrosingresos)>0){
            $pdf::SetFont('helvetica','B',8.5);
            $pdf::Cell(283,7,'INGRESOS',1,0,'L');
            $pdf::Ln();
            $subtotalingresos = 0;
            foreach ($listaotrosingresos as $row) { 
                $pdf::SetFont('helvetica','',6);   
                $pdf::Cell(10,7, $row['num_caja'] ,1,0,'C');                                 
                $pdf::Cell(25,7,date("d/m/Y h:i:s a", strtotime($row['fecha'])),1,0,'C');
                $nombrepersona_trabajador = null;
                $nombrepersona = null;
                if(!is_null($row['persona_id'])){
                    $persona = Person::find($row['persona_id']);
                    if($persona->dni == null){
                        $nombrepersona = $persona->razon_social;
                    }else{
                        $nombrepersona = $persona->apellido_pat . " " . $persona->apellido_mat . " " . $persona->nombres;
                    }
                }
                if(!is_null($row['trabajador_id'])){
                    $persona_trabajador = Person::find($row['trabajador_id']);
                    if($persona_trabajador->dni == null){
                        $nombrepersona_trabajador = $persona_trabajador->razon_social;
                    }else{
                        $nombrepersona_trabajador = $persona_trabajador->apellido_pat . " " . $persona_trabajador->apellido_mat . " " . $persona_trabajador->nombres;
                    }
                }
                $pdf::Cell(50,7,$nombrepersona_trabajador,1,0,'L');

                if($row->venta_id != null){
                    $tipodoc = Tipodocumento::find($row->venta->tipodocumento_id);
                    $pdf::Cell(10,7, $tipodoc->abreviatura,1,0,'C');
                    $pdf::Cell(18,7, $row->venta->num_venta ,1,0,'C');
                }else{
                    $tipodoc = Tipodocumento::find($row['tipodocumento_id']);
                    $pdf::Cell(10,7,'',1,0,'C');
                    $pdf::Cell(18,7, $row['num_venta'] ,1,0,'C');
                }

                $concepto = Concepto::find($row['concepto_id']);
                $pdf::Cell(45,7,$concepto->concepto,1,0,'L');
                //$trabajador = Persona::find($row['trabajador_id']);
                $pdf::Cell(50,7, $nombrepersona ,1,0,'L');
                if($row['estado'] == 1) {
                    $pdf::Cell(15,7,"",1,0,'C');
                    $total = number_format($row['total'],2,'.','');
                    if($total == 0){$total='';}
                    $pdf::Cell(15,7,$total,1,0,'R');                    
                    if($row->venta_id != null){
                        $pdf::Cell(45,7,'',1,0,'L');     
                    }else{
                        $pdf::Cell(45,7,$row->comentario,1,0,'L');     
                    }
                    $totalingresos += number_format($row['total'],2,'.','');
                    $subtotalingresos += number_format($row['total'],2,'.','');
                } else {
                    $pdf::Cell(30,7,'ANULADO',1,0,'C');    
                    $pdf::Cell(45,7, $row->comentario_anulado ,1,0,'L');                     
                }  
                $pdf::Ln();                  
            } 
            $pdf::SetFont('helvetica','B',8.5);
            $pdf::Cell(223,7,'SUBTOTAL',1,0,'R');
            $pdf::Cell(15,7,number_format($subtotalingresos,2,'.',''),1,0,'R');
            $pdf::Ln();                   
        }

        //EGRESOS

        $rstegresos = Movimiento::leftjoin('concepto','movimiento.concepto_id','=','concepto.id')
                            ->where('concepto.tipo','=', 1)
                            ->where('concepto.id','!=', 2)
                            ->where('concepto.id','!=', 12)
                            ->where('concepto.id','!=', 15)
                            //->where('movimiento.estado','=', 1)
                            ->where('movimiento.sucursal_id', '=', $sucursal_id)
                            ->where('movimiento.tipomovimiento_id', '=', 1)
                            ->where('movimiento.id', '>=', $apertura_ultima);

        $rstegresos = $rstegresos->orderBy('movimiento.num_caja', 'asc');

        $listarstegresos        = $rstegresos->get();

        if(count($listarstegresos)>0){
            $pdf::SetFont('helvetica','B',8.5);
            $pdf::Cell(283,7,'EGRESOS',1,0,'L');
            $pdf::Ln();
            foreach ($listarstegresos as $row) { 
                $pdf::SetFont('helvetica','',6);   
                $pdf::Cell(10,7, $row['num_caja'] ,1,0,'C');                                 
                $pdf::Cell(25,7,date("d/m/Y h:i:s a", strtotime($row['fecha'])),1,0,'C');
                if(!is_null($row['persona_id'])){
                    $persona = Person::find($row['persona_id']);
                    if($persona->dni == null){
                        $nombrepersona = $persona->razon_social;
                    }else{
                        $nombrepersona = $persona->apellido_pat . " " . $persona->apellido_mat . " " . $persona->nombres;
                    }
                }else if(!is_null($row['trabajador_id'])){
                    $persona = Person::find($row['trabajador_id']);
                    if($persona->dni == null){
                        $nombrepersona = $persona->razon_social;
                    }else{
                        $nombrepersona = $persona->apellido_pat . " " . $persona->apellido_mat . " " . $persona->nombres;
                    }
                }
                $pdf::Cell(50,7,"",1,0,'L');

                if($row->compra_id != null){
                    $tipodoc = Tipodocumento::find($row->compra->tipodocumento_id);
                    $pdf::Cell(10,7, $tipodoc->abreviatura,1,0,'C');
                    $pdf::Cell(18,7, $row->compra->num_compra ,1,0,'C');
                }else{
                    $tipodoc = Tipodocumento::find($row['tipodocumento_id']);
                    $pdf::Cell(10,7,'',1,0,'C');
                    $pdf::Cell(18,7, $row['num_compra'] ,1,0,'C');
                }
                
                $concepto = Concepto::find($row['concepto_id']);
                $pdf::Cell(45,7,$concepto->concepto,1,0,'L');
                //$trabajador = Persona::find($row['trabajador_id']);
                $pdf::Cell(50,7, $nombrepersona ,1,0,'L');
                if($row['estado'] == 1) {
                    $pdf::Cell(15,7,number_format($row['total'],2,'.',''),1,0,'R');
                    $totalegresos += number_format($row['total'],2,'.','');
                    $pdf::Cell(15,7,"",1,0,'R');
                    if($row->compra_id != null){
                        $pdf::Cell(45,7,'',1,0,'L');     
                    }else{
                        $pdf::Cell(45,7,$row->comentario,1,0,'L');     
                    }
                }else{
                    $pdf::Cell(30,7,'ANULADO',1,0,'C');
                    $pdf::Cell(45,7, $row->comentario_anulado ,1,0,'L');     
                }
                $pdf::Ln();                  
            } 
            $pdf::SetFont('helvetica','B',8.5);
            $pdf::Cell(208,7,'SUBTOTAL',1,0,'R');
            $pdf::Cell(15,7,number_format($totalegresos,2,'.',''),1,0,'R');
           /* $pdf::Cell(15,7,"",1,0,'R');
            $pdf::Cell(15,7,"",1,0,'R');
            $pdf::Cell(15,7,number_format($subtotalefectivo+$subtotalvisa+$subtotalmaster,2,'.',''),1,0,'R');*/
            $pdf::Ln();                   
        }

        $pdf::SetFont('helvetica','',7);   
        $pdf::Ln();

        $user = Auth::user();
        $persona = Person::find($user->person_id);

        $pdf::Cell(110,7,('RESPONSABLE: '. $persona->apellido_pat . " " . $persona->apellido_mat . " " . $persona->nombres),0,0,'L');
        $pdf::SetFont('helvetica','B',9);
        $pdf::Cell(60,7,utf8_decode("RESUMEN DE CAJA"),1,0,'C');
        $pdf::Ln();
        $pdf::Cell(110,7,utf8_decode(""),0,0,'C');
        $pdf::Cell(40,7,utf8_decode("MONTO APERTURA:"),1,0,'L');
        $pdf::Cell(20,7,number_format($rst->total,2,'.',''),1,0,'R');
        $pdf::Ln();
        $pdf::Cell(110,7,utf8_decode(""),0,0,'C');
        $pdf::Cell(40,7,utf8_decode("MONTO VUELTO:"),1,0,'L');
        $pdf::Cell(20,7,number_format($totalvuelto,2,'.',''),1,0,'R');
        $pdf::Ln();
        $pdf::Cell(110,7,utf8_decode(""),0,0,'C');
        $pdf::Cell(40,7,utf8_decode("INGRESOS :"),1,0,'L');
        $pdf::Cell(20,7,number_format($totalingresos,2,'.',''),1,0,'R');
        $pdf::Ln();
        $pdf::Cell(110,7,utf8_decode(""),0,0,'C');
        $pdf::Cell(40,7,utf8_decode("EGRESOS :"),1,0,'L');
        $pdf::Cell(20,7,number_format($totalegresos,2,'.',''),1,0,'R');
        $pdf::Ln();/*
        $pdf::Cell(110,7,utf8_decode(""),0,0,'C');
        $pdf::Cell(40,7,utf8_decode("SALDO:"),1,0,'L');
        $pdf::Cell(20,7,number_format($rst->total + $totalingresos - $totalegresos,2,'.',''),1,0,'R');
        $pdf::Ln();*/
        $pdf::Cell(110,7,utf8_decode(""),0,0,'C');
        $pdf::Cell(40,7,utf8_decode("CAJA :"),1,0,'L');
        $pdf::Cell(20,7,number_format($rst->total + $totalingresos - $totalegresos - $totalvuelto,2,'.',''),1,0,'R');
        $pdf::Ln();

        if($sucursal->empresa_id == $user->empresa_id){
            $pdf::Output('ReporteCierreCaja.pdf');
        }
    }
}
