<?php

class UserLoginController extends BaseController {
	
	//Login Ajax
	public function adminLogin()
	{
		$this->beforeFilter('csrf', array('on' => 'post'));
		
		if( Request::ajax() ){
			
			$remember=Input::get('remember', 0);
			
			//reglas de validacion
			$rules = array(
				'email' 	=> 'required|max:255',
				'password' 	=> 'required|max:20',
			);
	
			$validation = Validator::make(Input::all(), $rules);
			if( $validation->fails() ){
				//Enviar errores encontrados
				return Response::json(
					array(
						'success' => false,
						'msg'  => $validation->getMessageBag()->toArray()
					)
				);
				//Enviar a la vista anterior con los errores encontrados
				//return Redirect::back()->withInput()->withErrors($validation);
			}
			
			$user = User::where(function($where){
						$where->where('email', Input::get('email'))
						->orWhere('username', Input::get('email'));
					})
					->first();
			
			if(!$user) {
				$attempt = false;
			} else {
				$credentials = array(
					'email' 	=> $user->email,
					'password' 	=> Input::get('password'),
					'status'	=> 'A',//Que este activo
					'nivel_id'	=> '1',//Que su nivel sea Administrador
				);
				
				$attempt = Auth::attempt($credentials, $remember);
			}
			
			if($attempt) {
				//return Redirect::intended('/')->with(array('flash_message' => 'Successfully logged in', 'flash_type' => 'success') );
				//return Redirect::to('admin');
				//Enviar respuesta
				return Response::json(
					array(
						'success' => true,
						'msg'	  => array('Logueado correctamente'),
					)
				);
				//return Redirect::intended('admin');
			}
			
			Auth::logout();
			$msg = array('El nombre de usuario o password no son correctos');
			return Response::json(
				array(
					'success' => false,
					'msg'	  => $msg,
				)
			);
			//return Redirect::back()->with('login_errors', true)->withInput();//agrega variable de sesion temporal login_errors con valor true
			
		}
		else{
			$msg = array('No existe peticion Ajax');
			return Response::json(
				array(
					'success' => false,
					'msg'	  => $msg,
				)
			);
		}
		
		
	}

}

?>