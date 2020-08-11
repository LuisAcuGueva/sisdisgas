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
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CajaController extends Controller
{

    protected $folderview      = 'app.caja';
    protected $tituloAdmin     = 'Caja';
    protected $tituloRegistrar = 'Registrar Movimiento de Caja';
    protected $tituloEliminar  = 'Anular Movimiento de Caja';
    protected $tituloApertura  = 'Apertura de Caja';
    protected $tituloTurnoRepartidor  = 'Iniciar Turno de Repartidor';
    protected $tituloCierre    = 'Cierre de Caja';
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
        $empresa_id = $sucursal->empresa_id;

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
        $cabecera[]       = array('valor' => 'CLIENTE', 'numero' => '1');
        $cabecera[]       = array('valor' => 'TRABAJADOR', 'numero' => '1');
        $cabecera[]       = array('valor' => 'INGRESOS', 'numero' => '1');
        $cabecera[]       = array('valor' => 'EGRESOS', 'numero' => '1');
        $cabecera[]       = array('valor' => 'COMENTARIO', 'numero' => '1');
        $cabecera[]       = array('valor' => 'USUARIO', 'numero' => '1');
        
        $tituloTurnoRepartidor = $this->tituloTurnoRepartidor;
        $titulo_eliminar  = $this->tituloEliminar;
        $titulo_registrar = $this->tituloRegistrar;
        $titulo_apertura  = $this->tituloApertura;
        $titulo_cierre    = $this->tituloCierre;
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
            return view($this->folderview.'.list')->with(compact('montoapertura','monto_vuelto', 'lista', 'ingresos_efectivo', 'monto_caja', 'ingresos_visa', 'ingresos_master' , 'ingresos_total', 'egresos' , 'saldo',  'aperturas' , 'cierres' , 'ruta', 'paginacion', 'inicio', 'fin', 'entidad', 'cabecera', 'aperturaycierre', 'tituloTurnoRepartidor', 'titulo_eliminar', 'titulo_registrar', 'titulo_apertura', 'titulo_cierre', 'ruta'));
        }
        return view($this->folderview.'.list')->with(compact('montoapertura', 'monto_vuelto', 'lista', 'ingresos_efectivo', 'monto_caja', 'ingresos_visa', 'ingresos_master' , 'ingresos_total', 'egresos' , 'saldo', 'aperturas' , 'cierres' , 'ruta', 'aperturaycierre', 'titulo_registrar', 'tituloTurnoRepartidor', 'titulo_apertura', 'titulo_cierre', 'entidad'));
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
        $boton        = 'Registrar'; 
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
                    
        $boton        = 'Registrar'; 
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

        $turnos_iniciados = Turnorepartidor::where('estado','I')->get();
        // TRABAJADORES EN TURNO
        $trabajadores_iniciados = array();
        foreach ($turnos_iniciados as $key => $value) {
            $trabajador = Person::find($value->trabajador_id);
            array_push($trabajadores_iniciados, $trabajador);
        }
        // TODOS TRABAJADORES
        $todos = Person::where('tipo_persona','T')->get();
        $todos_trabajadores = array();
        foreach ($todos as $key => $value) {
            $trabajador = Person::find($value->id);
            array_push($todos_trabajadores, $trabajador);
        }

        // TRABAJADORES POR INICIAR TURNO
        $trabajadores_sinturno = array_diff($todos_trabajadores, $trabajadores_iniciados);

        /*foreach ($trabajadores_iniciados as $value) {
            foreach ($todos_trabajadores as $key ) {
                if($value->id != $key->id){
                    array_push($trabajadores_sinturno, $key);
                }
            }
        }*/
        $boton        = 'Registrar'; 
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
        

        $turnos_iniciados = Turnorepartidor::where('estado','I')->get();
        // TRABAJADORES EN TURNO
        $trabajadores_iniciados = array();
        foreach ($turnos_iniciados as $key => $value) {
            $trabajador = Person::find($value->trabajador_id);
            array_push($trabajadores_iniciados, $trabajador);
        }

        if(!empty($trabajadores_iniciados)){
            $turnos_cerrados = "NO";
        }else{
            $turnos_cerrados = "SI";
        }

        $boton        = 'Registrar';
        return view($this->folderview.'.cierre')->with(compact('persona_id' , 'num_caja', 'sucursal_id','movimiento', 'turnos_cerrados' ,'formData', 'entidad', 'boton', 'listar'));
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

            if($movimiento->venta_id != null){
                $movimientoventa = Movimiento::find($movimiento->venta_id);
                $movimientoventa->estado = 0;
                $movimientoventa->comentario_anulado  = strtoupper($request->input('motivo'));  
                $movimientoventa->save();
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
                                ->where('id','!=',3)
                                ->where('id','!=',12)
                                ->where('id','!=',13)
                                ->where('id','!=',14)
                                ->where('id','!=',15)
                                ->where('id','!=',16)
                                ->orderBy('id','ASC')->get();
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
        $boton          = 'Registrar'; 
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
}
