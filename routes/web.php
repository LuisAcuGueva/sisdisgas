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

    Route::get('venta/clienteautocompletar/{searching}', 'VentaController@clienteautocompletar')->name('venta.clienteautocompletar');
    //Route::get('venta/servicioautocompletar/{searching}', 'VentaController@servicioautocompletar')->name('venta.servicioautocompletar');
    //Route::get('venta/productoautocompletar/{searching}', 'VentaController@productoautocompletar')->name('venta.productoautocompletar');
    Route::post('venta/guardarventa', 'VentaController@guardarventa')->name('venta.guardarventa');
    Route::post('venta/guardardetalle', 'VentaController@guardardetalle')->name('venta.guardardetalle');
    Route::post('venta/serieventa', 'VentaController@serieventa')->name('venta.serieventa');
    Route::post('venta/permisoRegistrar', 'VentaController@permisoRegistrar')->name('venta.permisoRegistrar');
    Route::resource('venta', 'VentaController', array('except' => array('show')));

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
    Route::get('caja/persona', 'CajaController@persona')->name('caja.persona');
    Route::post('caja/guardarpersona', 'CajaController@guardarpersona')->name('caja.guardarpersona');
    //Route::get('caja/repetido/{id}/{listarluego}','CajaController@repetido')->name('caja.repetido');
    //Route::post('caja/guardarrepetido','CajaController@guardarrepetido')->name('caja.guardarrepetido');
    //Route::get('caja/aperturaycierre', 'CajaController@aperturaycierre')->name('caja.aperturaycierre');
    Route::resource('caja', 'CajaController', array('except' => array('show')));

    Route::post('producto/buscar','ProductoController@buscar')->name('producto.buscar');
    Route::get('producto/eliminar/{id}/{listarluego}','ProductoController@eliminar')->name('producto.eliminar');
    Route::resource('producto', 'ProductoController', array('except' => array('show')));

    Route::post('turno/buscar','TurnoController@buscar')->name('turno.buscar');
    Route::get('turno/eliminar/{id}/{listarluego}','TurnoController@eliminar')->name('turno.eliminar');
    Route::resource('turno', 'TurnoController', array('except' => array('show')));

    Route::post('trabajador/buscar','TrabajadorController@buscar')->name('trabajador.buscar');
    Route::get('trabajador/eliminar/{id}/{listarluego}','TrabajadorController@eliminar')->name('trabajador.eliminar');
    Route::resource('trabajador', 'TrabajadorController', array('except' => array('show')));
    Route::get('trabajador/trabajadorautocompleting/{searching}', 'TrabajadorController@trabajadorautocompleting')->name('trabajador.trabajadorautocompleting');

    Route::post('cliente/buscar','ClienteController@buscar')->name('cliente.buscar');
    Route::get('cliente/eliminar/{id}/{listarluego}','ClienteController@eliminar')->name('cliente.eliminar');
    Route::resource('cliente', 'ClienteController', array('except' => array('show')));
    Route::get('cliente/clienteautocompleting/{searching}', 'ClienteController@clienteautocompleting')->name('cliente.clienteautocompleting');

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