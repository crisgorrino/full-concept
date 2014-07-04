<?php

class BaseController extends Controller {
	
	//### Variable Default ###//
	public $proyectName='Full Concept'; 	//Se vera en algunas salidas de Email principalmente
	public $pathUpload='uploads/'; 	//Carpeta default para subir archivos
	public $lang='es';

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

}
