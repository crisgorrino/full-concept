<?php

class ImagenesSlideshow extends Eloquent {
	
	public $pathUpload = '';
	
	public function __construct(){
		$base = new BaseController;
		$this->pathUpload=$base->pathUpload;
	}

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'proyecto_slideshow';
	
	
}
