<?php

class ProyectoComentarios extends Eloquent {
	
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'proyecto_comentarios';
	
	//Una comentario esta asociado a un proyecto
	public function proyecto()
	{
		return $this->hasOne('Proyectos','id','proyecto_id')->first();
	}
	
	
}
