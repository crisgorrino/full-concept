<?php

class ImagenesMosaico extends Eloquent {
	
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
	protected $table = 'proyecto_imagenes';
	
	//Una imagen de mosaico está vinculada ocn un proyecto
	public function proyecto()
	{
		return $this->hasOne('Proyectos','id','proyecto_id')->first();
	}
		
	
	//Un proyecto tiene una o varias imagenes
	public function imagenes_mosaico()
	{
		$path="'".$this->pathUpload."proy_id_',proyecto_id,'/img/id_', id,'/'";
		
		return ImagenesMosaico::select('*', DB::raw("CONCAT(".$path.") AS path") )
					->where('published', '=', '1')
					->where('removed', '=', '0')
					->orderBy(DB::raw('RAND()'))
					->get();
				
	}
	
	
}
