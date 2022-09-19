<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Authentication routes...
// Route::get('auth/login', 'Auth\AuthController@getLogin');
// Route::post('auth/login', ['as' =>'auth/login', 'uses' => 'Auth\AuthController@postLogin']);
// Route::get('auth/logout', ['as' => 'auth/logout', 'uses' => 'Auth\AuthController@getLogout']);
 
// Registration routes...
// Route::get('auth/register', 'Auth\AuthController@getRegister');
// Route::post('auth/register', ['as' => 'auth/register', 'uses' => 'Auth\AuthController@postRegister']);

Auth::routes();
Route::get('logout', 'Auth\LoginController@logout');

Route::get('/', function(){
    return redirect('/dashboard');
});

Route::group(['middleware' => 'guest'], function() {    
    //Password reset routes
    Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset');
    Route::get('password','Auth\ResetPasswordController@showPasswordReset');
    //Register routes
    Route::get('registro','Auth\RegisterController@showRegistrationForm');
    Route::post('registro', 'Auth\RegisterController@register');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', function(){
        return View::make('dashboard.home');
    });

    Route::post('profile/save','ProfileController@save')->name('profile.save');
    Route::resource('profile', 'ProfileController', array('except' => array('show')));

    Route::post('inicio/guardarSucursalRepartidor','InicioController@guardarSucursalRepartidor')->name('inicio.guardarSucursalRepartidor');
    Route::post('inicio/buscarcaja','InicioController@buscarcaja')->name('inicio.buscarcaja');
    Route::post('inicio/buscarproductosvendidos','InicioController@buscarproductosvendidos')->name('inicio.buscarproductosvendidos');
    Route::post('inicio/buscarinventario','InicioController@buscarinventario')->name('inicio.buscarinventario');
    Route::post('inicio/buscarturnos','InicioController@buscarturnos')->name('inicio.buscarturnos');
    Route::post('inicio/buscarcredito','InicioController@buscarcredito')->name('inicio.buscarcredito');
    Route::post('inicio/save','InicioController@save')->name('inicio.save');
    Route::resource('inicio', 'InicioController', array('except' => array('show')));

    Route::get('venta/clienteautocompletar/{searching}', 'VentaController@clienteautocompletar')->name('venta.clienteautocompletar');
    //Route::get('venta/servicioautocompletar/{searching}', 'VentaController@servicioautocompletar')->name('venta.servicioautocompletar');
    //Route::get('venta/productoautocompletar/{searching}', 'VentaController@productoautocompletar')->name('venta.productoautocompletar');
    Route::post('venta/cargarproductos', 'VentaController@cargarproductos')->name('venta.cargarproductos');
    Route::post('venta/guardarventa', 'VentaController@guardarventa')->name('venta.guardarventa');
    Route::post('venta/guardardetalle', 'VentaController@guardardetalle')->name('venta.guardardetalle');
    Route::get('venta/cliente', 'VentaController@cliente')->name('venta.cliente');
    Route::post('venta/guardarcliente', 'VentaController@guardarcliente')->name('venta.guardarcliente');
    Route::post('venta/serieventa', 'VentaController@serieventa')->name('venta.serieventa');
    Route::post('venta/permisoRegistrar', 'VentaController@permisoRegistrar')->name('venta.permisoRegistrar');
    Route::resource('venta', 'VentaController', array('except' => array('show')));
    
    Route::post('compras/buscar','ComprasController@buscar')->name('compras.buscar');
    Route::get('compras/eliminar/{id}/{listarluego}','ComprasController@eliminar')->name('compras.eliminar');
    Route::get('compras/detalle/{id}/', 'ComprasController@detalle')->name('compras.detalle');
    Route::resource('compras', 'ComprasController', array('except' => array('show')));
    Route::get('compras/proveedor', 'ComprasController@proveedor')->name('compras.proveedor');
    Route::post('compras/guardarproveedor', 'ComprasController@guardarproveedor')->name('compras.guardarproveedor');
    Route::post('compras/buscandoproducto','ComprasController@buscandoproducto')->name('compras.buscandoproducto');
    Route::post('compras/consultaproducto','ComprasController@consultaproducto')->name('compras.consultaproducto');
    Route::post('compras/agregarcarritocompra','ComprasController@agregarcarritocompra')->name('compras.agregarcarritocompra');

    Route::post('movalmacen/buscar','MovalmacenController@buscar')->name('movalmacen.buscar');
    Route::get('movalmacen/eliminar/{id}/{listarluego}','MovalmacenController@eliminar')->name('movalmacen.eliminar');
    Route::get('movalmacen/detalle/{id}/', 'MovalmacenController@detalle')->name('movalmacen.detalle');
    Route::resource('movalmacen', 'MovalmacenController', array('except' => array('show')));
    Route::post('movalmacen/buscandoproducto','MovalmacenController@buscandoproducto')->name('movalmacen.buscandoproducto');
    Route::post('movalmacen/consultaproducto','MovalmacenController@consultaproducto')->name('movalmacen.consultaproducto');
    Route::post('movalmacen/agregarcarritocompra','MovalmacenController@agregarcarritocompra')->name('movalmacen.agregarcarritocompra');
    Route::post('movalmacen/generartipodocumento','MovalmacenController@generartipodocumento')->name('movalmacen.generartipodocumento');

    Route::get('caja/pdfDetalleCierre', 'CajaController@pdfDetalleCierre')->name('caja.pdfDetalleCierre');
    Route::get('caja/clienteautocompletar/{searching}', 'CajaController@clienteautocompletar')->name('caja.clienteautocompletar');
    //Route::get('caja/proveedorautocompletar/{searching}', 'CajaController@proveedorautocompletar')->name('caja.proveedorautocompletar');
    Route::get('caja/empleadoautocompletar/{searching}', 'CajaController@empleadoautocompletar')->name('caja.empleadoautocompletar');
    Route::get('caja/generarConcepto','CajaController@generarConcepto')->name('caja.generarConcepto');
    Route::post('caja/buscar','CajaController@buscar')->name('caja.buscar');
    Route::get('caja/eliminar/{id}/{listarluego}','CajaController@eliminar')->name('caja.eliminar');
    Route::get('caja/apertura', 'CajaController@apertura')->name('caja.apertura');
    Route::get('caja/turnoRepartidor', 'CajaController@turnoRepartidor')->name('caja.turnoRepartidor');
    Route::get('caja/cierre', 'CajaController@cierre')->name('caja.cierre');
    Route::get('caja/ingresarcierres', 'CajaController@ingresarcierres')->name('caja.ingresarcierres');
    Route::get('caja/persona', 'CajaController@persona')->name('caja.persona');
    Route::post('caja/guardarpersona', 'CajaController@guardarpersona')->name('caja.guardarpersona');
    Route::post('caja/saldoCaja', 'CajaController@saldoCaja')->name('caja.saldoCaja');
    //Route::get('caja/repetido/{id}/{listarluego}','CajaController@repetido')->name('caja.repetido');
    //Route::post('caja/guardarrepetido','CajaController@guardarrepetido')->name('caja.guardarrepetido');
    //Route::get('caja/aperturaycierre', 'CajaController@aperturaycierre')->name('caja.aperturaycierre');
    Route::resource('caja', 'CajaController', array('except' => array('show')));

    Route::post('baloncredito/buscar','BaloncreditoController@buscar')->name('baloncredito.buscar');
    Route::get('baloncredito/detalle/{id}/', 'BaloncreditoController@detalle')->name('baloncredito.detalle');
    Route::get('baloncredito/pagos/{id}/', 'BaloncreditoController@pagos')->name('baloncredito.pagos');
    Route::get('baloncredito/pagar/{id}/', 'BaloncreditoController@pagar')->name('baloncredito.pagar');
    Route::post('baloncredito/pagardeuda/', 'BaloncreditoController@pagardeuda')->name('baloncredito.pagardeuda');
    Route::get('baloncredito/eliminar/{id}/{listarluego}','BaloncreditoController@eliminar')->name('baloncredito.eliminar');
    Route::resource('baloncredito', 'BaloncreditoController', array('except' => array('show')));

    Route::post('compraspagar/buscar','CompraspagarController@buscar')->name('compraspagar.buscar');
    Route::get('compraspagar/detalle/{id}/', 'CompraspagarController@detalle')->name('compraspagar.detalle');
    Route::get('compraspagar/pagos/{id}/', 'CompraspagarController@pagos')->name('compraspagar.pagos');
    Route::get('compraspagar/pagar/{id}/', 'CompraspagarController@pagar')->name('compraspagar.pagar');
    Route::post('compraspagar/pagardeuda/', 'CompraspagarController@pagardeuda')->name('compraspagar.pagardeuda');
    Route::get('compraspagar/eliminar/{id}/{listarluego}','CompraspagarController@eliminar')->name('compraspagar.eliminar');
    Route::resource('compraspagar', 'CompraspagarController', array('except' => array('show')));

    Route::post('inventario/buscar','InventarioController@buscar')->name('inventario.buscar');
    Route::get('inventario/eliminar/{id}/{listarluego}','InventarioController@eliminar')->name('inventario.eliminar');
    Route::resource('inventario', 'InventarioController', array('except' => array('show')));

    Route::post('kardex/buscar','KardexController@buscar')->name('kardex.buscar');
    Route::get('kardex/eliminar/{id}/{listarluego}','KardexController@eliminar')->name('kardex.eliminar');
    Route::resource('kardex', 'KardexController', array('except' => array('show')));

    Route::post('producto/buscar','ProductoController@buscar')->name('producto.buscar');
    Route::get('producto/eliminar/{id}/{listarluego}','ProductoController@eliminar')->name('producto.eliminar');
    Route::resource('producto', 'ProductoController', array('except' => array('show')));
    Route::get('producto/productoautocompleting/{searching}', 'ProductoController@productoautocompleting')->name('producto.productoautocompleting');

    Route::post('turno/buscar','TurnoController@buscar')->name('turno.buscar');
    Route::get('turno/vuelto', 'TurnoController@vuelto')->name('turno.vuelto');
    Route::get('turno/gastos', 'TurnoController@gastos')->name('turno.gastos');
    Route::get('turno/descargadinero', 'TurnoController@descargadinero')->name('turno.descargadinero');
    Route::get('turno/cierre', 'TurnoController@cierre')->name('turno.cierre');
    Route::get('turno/detalle/{id}/', 'TurnoController@detalle')->name('turno.detalle');
    Route::post('turno/cargarnumerocaja', 'TurnoController@cargarnumerocaja')->name('turno.cargarnumerocaja');
    Route::post('turno/cargarempleados', 'TurnoController@cargarempleados')->name('turno.cargarempleados');
    Route::post('turno/generarSaldoRepartidor', 'TurnoController@generarSaldoRepartidor')->name('turno.generarSaldoRepartidor');
    Route::get('turno/eliminar/{id}/{listarluego}','TurnoController@eliminar')->name('turno.eliminar');
    Route::resource('turno', 'TurnoController', array('except' => array('show')));

    Route::post('pedidos/buscar','PedidosController@buscar')->name('pedidos.buscar');
    Route::get('pedidos/detalle/{id}/', 'PedidosController@detalle')->name('pedidos.detalle');
    Route::get('pedidos/eliminar/{id}/{listarluego}','PedidosController@eliminar')->name('pedidos.eliminar');
    Route::resource('pedidos', 'PedidosController', array('except' => array('show')));

    Route::post('pedidos_actual/buscar','PedidosActualController@buscar')->name('pedidos_actual.buscar');
    Route::get('pedidos_actual/detalle/{id}/', 'PedidosActualController@detalle')->name('pedidos_actual.detalle');
    Route::get('pedidos_actual/prestar/{id}/', 'PedidosActualController@prestar')->name('pedidos_actual.prestar');
    Route::post('pedidos_actual/prestarbalon/', 'PedidosActualController@prestarbalon')->name('pedidos_actual.prestarbalon');
    Route::get('pedidos_actual/eliminar/{id}/{listarluego}','PedidosActualController@eliminar')->name('pedidos_actual.eliminar');
    Route::resource('pedidos_actual', 'PedidosActualController', array('except' => array('show')));

    Route::post('prestamoenvase/buscar','PrestamoController@buscar')->name('prestamoenvase.buscar');
    Route::get('prestamoenvase/detalle/{id}/', 'PrestamoController@detalle')->name('prestamoenvase.detalle');
    Route::get('prestamoenvase/prestar/{id}/', 'PrestamoController@prestar')->name('prestamoenvase.prestar');
    Route::post('prestamoenvase/prestarbalon/', 'PrestamoController@prestarbalon')->name('prestamoenvase.prestarbalon');
    Route::get('prestamoenvase/eliminar/{id}/{listarluego}','PrestamoController@eliminar')->name('prestamoenvase.eliminar');
    Route::resource('prestamoenvase', 'PrestamoController', array('except' => array('show')));

    Route::get('turnoscompletados/pdfDetalleTurno', 'TurnoscompletadosController@pdfDetalleTurno')->name('turnoscompletados.pdfDetalleTurno');
    Route::post('turnoscompletados/buscar','TurnoscompletadosController@buscar')->name('turnoscompletados.buscar');
    Route::post('turnoscompletados/buscardetalles','TurnoscompletadosController@buscardetalles')->name('turnoscompletados.buscardetalles');
    Route::get('turnoscompletados/detalleturno/{id}/', 'TurnoscompletadosController@detalleturno')->name('turnoscompletados.detalleturno');
    Route::post('turnoscompletados/cargarnumerocaja', 'TurnoscompletadosController@cargarnumerocaja')->name('turnoscompletados.cargarnumerocaja');
    Route::post('turnoscompletados/generarSaldoRepartidor', 'TurnoscompletadosController@generarSaldoRepartidor')->name('turnoscompletados.generarSaldoRepartidor');
    Route::resource('turnoscompletados', 'TurnoscompletadosController', array('except' => array('show')));

    Route::post('trabajador/buscar','TrabajadorController@buscar')->name('trabajador.buscar');
    Route::get('trabajador/eliminar/{id}/{listarluego}','TrabajadorController@eliminar')->name('trabajador.eliminar');
    Route::resource('trabajador', 'TrabajadorController', array('except' => array('show')));
    Route::get('trabajador/trabajadorautocompleting/{searching}', 'TrabajadorController@trabajadorautocompleting')->name('trabajador.trabajadorautocompleting');

    Route::post('proveedor/buscar','ProveedorController@buscar')->name('proveedor.buscar');
    Route::get('proveedor/eliminar/{id}/{listarluego}','ProveedorController@eliminar')->name('proveedor.eliminar');
    Route::resource('proveedor', 'ProveedorController', array('except' => array('show')));
    Route::get('proveedor/proveedorautocompleting/{searching}', 'ProveedorController@proveedorautocompleting')->name('proveedor.proveedorautocompleting');
    Route::get('proveedor/buscarEmpresa', 'ProveedorController@buscarEmpresa')->name('proveedor.buscarEmpresa');
    Route::post('proveedor/ultimoproveedor','ProveedorController@ultimoproveedor')->name('proveedor.ultimoproveedor');

    Route::post('cliente/buscar','ClienteController@buscar')->name('cliente.buscar');
    Route::get('cliente/eliminar/{id}/{listarluego}','ClienteController@eliminar')->name('cliente.eliminar');
    Route::resource('cliente', 'ClienteController', array('except' => array('show')));
    Route::get('cliente/clienteautocompleting/{searching}', 'ClienteController@clienteautocompleting')->name('cliente.clienteautocompleting');
    Route::post('cliente/ultimocliente','ClienteController@ultimocliente')->name('cliente.ultimocliente');

    Route::post('concepto/buscar','ConceptoController@buscar')->name('concepto.buscar');
    Route::get('concepto/eliminar/{id}/{listarluego}','ConceptoController@eliminar')->name('concepto.eliminar');
    Route::resource('concepto', 'ConceptoController', array('except' => array('show')));

    Route::post('sucursal/buscar','SucursalController@buscar')->name('sucursal.buscar');
    Route::get('sucursal/eliminar/{id}/{listarluego}','SucursalController@eliminar')->name('sucursal.eliminar');
    Route::resource('sucursal', 'SucursalController', array('except' => array('show')));

    Route::post('categoriaopcionmenu/buscar', 'CategoriaopcionmenuController@buscar')->name('categoriaopcionmenu.buscar');
    Route::get('categoriaopcionmenu/eliminar/{id}/{listarluego}', 'CategoriaopcionmenuController@eliminar')->name('categoriaopcionmenu.eliminar');
    Route::resource('categoriaopcionmenu', 'CategoriaopcionmenuController', array('except' => array('show')));

    Route::post('opcionmenu/buscar', 'OpcionmenuController@buscar')->name('opcionmenu.buscar');
    Route::get('opcionmenu/eliminar/{id}/{listarluego}', 'OpcionmenuController@eliminar')->name('opcionmenu.eliminar');
    Route::resource('opcionmenu', 'OpcionmenuController', array('except' => array('show')));

    Route::post('tipousuario/buscar', 'TipousuarioController@buscar')->name('tipousuario.buscar');
    Route::get('tipousuario/obtenerpermisos/{listar}/{id}', 'TipousuarioController@obtenerpermisos')->name('tipousuario.obtenerpermisos');
    Route::post('tipousuario/guardarpermisos/{id}', 'TipousuarioController@guardarpermisos')->name('tipousuario.guardarpermisos');
    Route::get('tipousuario/obteneroperaciones/{listar}/{id}', 'TipousuarioController@obteneroperaciones')->name('tipousuario.obteneroperaciones');
    Route::post('tipousuario/guardaroperaciones/{id}', 'TipousuarioController@guardaroperaciones')->name('tipousuario.guardaroperaciones');
    Route::get('tipousuario/eliminar/{id}/{listarluego}', 'TipousuarioController@eliminar')->name('tipousuario.eliminar');
    Route::resource('tipousuario', 'TipousuarioController', array('except' => array('show')));
    Route::get('tipousuario/pdf', 'TipousuarioController@pdf')->name('tipousuario.pdf');

    Route::post('usuario/buscar', 'UsuarioController@buscar')->name('usuario.buscar');
    Route::get('usuario/eliminar/{id}/{listarluego}', 'UsuarioController@eliminar')->name('usuario.eliminar');
    Route::resource('usuario', 'UsuarioController', array('except' => array('show')));

    Route::get('subirdata', 'ExcelController@subirdata');;
    Route::resource('subirdata', 'ExcelController', array('except' => array('show')));
});

/*
Route::get('provincia/cboprovincia/{id?}', array('as' => 'provincia.cboprovincia', 'uses' => 'ProvinciaController@cboprovincia'));
Route::get('distrito/cbodistrito/{id?}', array('as' => 'distrito.cbodistrito', 'uses' => 'DistritoController@cbodistrito'));

Route::get('provincias/{id}', function($id)
{
	$departamento_id = $id;

	$provincias = Departamento::find($departamento_id)->provincias;

    return Response::json($provincias);
});


Route::get('provincias/{id}','ProvinciaController@getProvincias');
Route::get('distritos/{id}','DistritoController@getDistritos');

*/