<?php

class Proyectos extends Eloquent {
	
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
	protected $table = 'proyectos';
	
	//get detalle
	public function detalle($id){
		
		$path="'".$this->pathUpload."proy_id_',proyecto_id,'/'";
		
		return Productos::select('productos.*', DB::raw("CONCAT(".$path.") AS path"))
				 ->where('id', '=', $id)
				 ->where('productos.published', '=', 1)
				 //fotos que no esten eliminadas logicamente
				 ->where('productos.removed', '=', 0)
				 //->whereNotNull('productos.archivo')
				 //->where('productos.archivo', '!=', '')
				 ->orderBy('productos.created_at', 'DESC')
				 ->orderBy('productos.id', 'DESC')
				 ;
	}
		
	//Un proyecto tiene una o varias imagenes slideshow
	public function imagenes_slideshow()
	{
		
		$path="'".$this->pathUpload."proy_id_',proyecto_id,'/slide/id_', id,'/'";
		
		return $this->hasMany('ImagenesSlideshow', 'proyecto_id', 'id')
					->select('*', DB::raw("CONCAT(".$path.") AS path") )
					->where('published', '=', '1')
					->where('removed', '=', '0')
					->orderBy('ordering', 'ASC')
					->orderBy('id', 'DESC')
					->get();
	}
	
	//Un proyecto tiene una o varias imagenes para mosaico
	public function imagenes_mosaico()
	{
		
		$path="'".$this->pathUpload."proy_id_',proyecto_id,'/img/id_', id,'/'";
		
		return $this->hasMany('ImagenesMosaico', 'proyecto_id', 'id')
					->select('*', DB::raw("CONCAT(".$path.") AS path") )
					->where('published', '=', '1')
					->where('removed', '=', '0')
					->orderBy('id', 'DESC')
					->get();
	}
	
}
