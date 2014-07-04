<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', 'ProyectosController@getIndex');

Route::get('proyecto-detalle/{id?}', 'ProyectosController@getProyecto_detalle');

Route::get('proyecto-comentarios', 'ProyectosController@postProyectoComentarios');

Route::post('add-com', 'ProyectosController@postAddComentario');

Route::get('add-com', function(){
	return Redirect::to('/');
});


##### GROUP ADMINISTRADOR #####
Route::group(array('prefix'=>'admin'), function(){
	
	//Login
	Route::get('login', function(){
		return View::make('admin.login');
	});

	//Route::post('login', 'UserLoginController@user');
	Route::post('login', array('before'=>'csrf', 'uses'=>'UserLoginController@adminLogin'));

	//Logout
	Route::get('logout', function() { 
		Auth::logout(); 
		//Redireccionar a login
	 	return Redirect::to('admin/login'); 
	});
	
	//##### ADMINISTRACION #####//
	//Route::controller('ventas', 'admin_VentasController');
	Route::get('/', function(){
		return Redirect::to('admin/proyectos');
	});
	Route::post('/', function(){
		return Redirect::to('admin/proyectos');
	});
	Route::controller('proyectos', 'admin_ProyectosController');
	//Route::get('proyectos', 'admin_IndexController@getIndex');
	
	
	
});

/*
//NUEVO - CREAR - INSERT
Route::get('new-admin', function()
{
	$user = User::find(1);
	$msg='Usuario ha sido actualizado correctamente!';
	//Si no existe crearlo, caso contrario actualizarlo
	if(!$user){
		$user = new User;//instancia de modelo clase User
		$msg='Nuevo Usuario ha sido creado';
	}
	
	$user->nombre	= 'Administrador';
	$user->genero	= 'M';
	$user->email 	= 'c.torres@bitweb.mx';
	$user->username = 'admin';
	$user->password = Hash::make('holaadmin');
	$user->nivel_id	= 1;
	$user->status	= 'A';
	
	if($user->save()){
		return "<p>".$msg."</p>";
	}
	else{
		return "<p><b style='color:#900;'>Error al crear el Usuario</b></p>";
	}
	
});*/