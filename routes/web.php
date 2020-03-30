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

    Route::post('personal/buscar','PersonalController@buscar')->name('personal.buscar');
    Route::get('personal/eliminar/{id}/{listarluego}','PersonalController@eliminar')->name('personal.eliminar');
    Route::resource('personal', 'PersonalController', array('except' => array('show')));
    Route::get('personal/personalautocompleting/{searching}', 'PersonalController@personalautocompleting')->name('personal.personalautocompleting');
    Route::get('personal/verificadorautocompleting/{searching}', 'PersonalController@verificadorautocompleting')->name('personal.verificadorautocompleting');
    Route::get('personal/supervisorautocompleting/{searching}', 'PersonalController@supervisorautocompleting')->name('personal.supervisorautocompleting');
    
    Route::post('contribuyente/buscar','ContribuyenteController@buscar')->name('contribuyente.buscar');
    Route::get('contribuyente/eliminar/{id}/{listarluego}','ContribuyenteController@eliminar')->name('contribuyente.eliminar');
    Route::resource('contribuyente', 'ContribuyenteController', array('except' => array('show')));
    //Route::get('contribuyente/personalautocompleting/{searching}', 'PersonalController@personalautocompleting')->name('contribuyente.personalautocompleting');

    Route::post('esquela/buscar','EsquelaController@buscar')->name('esquela.buscar');
    Route::get('esquela/eliminar/{id}/{listarluego}','EsquelaController@eliminar')->name('esquela.eliminar');
    Route::resource('esquela', 'EsquelaController', array('except' => array('show')));

    Route::post('carta/buscar','CartaController@buscar')->name('carta.buscar');
    Route::get('carta/eliminar/{id}/{listarluego}','CartaController@eliminar')->name('carta.eliminar');
    Route::resource('carta', 'CartaController', array('except' => array('show')));

    Route::post('carga/buscar','CargaController@buscar')->name('carga.buscar');
    Route::get('carga/eliminar/{id}/{listarluego}','CargaController@eliminar')->name('carga.eliminar');
    Route::resource('carga', 'CargaController', array('except' => array('show')));

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

Route::get('provincia/cboprovincia/{id?}', array('as' => 'provincia.cboprovincia', 'uses' => 'ProvinciaController@cboprovincia'));
Route::get('distrito/cbodistrito/{id?}', array('as' => 'distrito.cbodistrito', 'uses' => 'DistritoController@cbodistrito'));

/*Route::get('provincias/{id}', function($id)
{
	$departamento_id = $id;

	$provincias = Departamento::find($departamento_id)->provincias;

    return Response::json($provincias);
});
*/

Route::get('provincias/{id}','ProvinciaController@getProvincias');
Route::get('distritos/{id}','DistritoController@getDistritos');