<?php

class admin_ComentariosController extends BaseController {
	
	public function __construct(){
		$this->beforefilter('auth'); //bloqueo de acceso, aplicar FILTRO
	}
	
	//Usando RESTFul
	//Ver Proyectos
	public function getIndex()
	{
		
		$limit=10;
		
		$comPendientes =ProyectoComentarios::select( DB::raw('COUNT(*) AS total') )->where('status_id', '=', '0')->first();
		
		$cometarios = ProyectoComentarios::select('proyecto_comentarios.*', 'proyectos.titulo')
							  ->leftJoin('proyectos', 'proyectos.id', '=', 'proyecto_comentarios.proyecto_id')
							  ->orderBy('proyecto_comentarios.created_at', 'desc')
							  ->orderBy('proyecto_comentarios.id', 'desc');
		
		
		$campo=Input::get('campo', '');
		$search=Input::get('search', '');
		
		/*if( !empty($campo) and !empty($search) ){
			$cometarios=$cometarios->where($campo, 'LIKE', '%'.$search.'%');
		}*/
		
		if( !empty($search) and empty($campo) ){
			
			$cometarios->where("proyecto_comentarios.id", 'LIKE', '%'.$search.'%')
								   ->orWhere("proyecto_comentarios.comentario", 'LIKE', '%'.$search.'%')
								   ->orWhere("proyecto_comentarios.nombre", 'LIKE', '%'.$search.'%')
								   ->orWhere("proyectos.titulo", 'LIKE', '%'.$search.'%');
			
		}
		
		
		if ( Request::ajax() ) {
			
			$cometarios->paginate( $limit );
			
			return Response::json(View::make('admin.proyectos.index_ajax_proyectos_list', 
				array(
					'cometarios'=>$cometarios
				)
			)->render());
			
		}
		
		$cometarios=$cometarios->paginate( $limit );
		//return DB::getQueryLog();
		
		$status = StatusComentarios::orderBy('ordering', 'ASC')->get();
		
		
		return View::make('admin.comentarios.index_comentarios', 
				array(
				   'cometarios'			=> $cometarios,
				   'totalPendientes'	=> $comPendientes->total,
				   'status'				=> $status,
				) 
			);
		
		
	}
	
	public function postIndex()
	{
		return $this->getIndex();
	}
	
	//Editar Registro
	public function getEdit($id=null)
	{
		
		/*if( empty($id) ){
			return Redirect::to('admin/proyectos');
		}*/
		
		$path="'".$this->pathUpload."proy_id_',id,'/'";
		
		$proyecto = Proyectos::select('*', DB::raw("CONCAT(".$path.") AS path") )->find($id);
		
		/*if( !$proyecto ){
			return Redirect::to('admin/proyectos');
		}*/
		
		
		return View::make('admin.proyectos.proyecto-edit', 
					array('proyecto'		=>$proyecto)
				);
		
		
	}
	
	//Guardar Proyecto
	public function postEdit()
	{
		$this->beforeFilter('csrf', array('on' => 'post'));
		
		//reglas de validacion
		$rules = array(
			'titulo' 		=> 'required|max:255',
			'concepto' 		=> 'required|max:5000',
			'descripcion' 	=> 'required|max:5000',
		);

		$validation = Validator::make(Input::all(), $rules);
		if( $validation->fails() ){
			//Enviar a la vista anterior con los errores encontrados
			return Redirect::back()->withInput()->withErrors($validation);
		}
		
		$msg='';
		
		$nameArchivoOrigen='';
		
		//Si existe el ID verificar si existe el registro en la BD
		$p_id=Input::get('id', 0);
		
		$proyecto = Proyectos::find($p_id);
		
		//Si NO se encontro registro entonces insertar, caso contrario modificar
		if( !$proyecto ){
			//Insert			
			$proyecto = new Proyectos;//instancia de modelo
		}
				
		$proyecto->titulo 		= Input::get('titulo');
		$proyecto->concepto 	= Input::get('concepto');
		$proyecto->descripcion 	= Input::get('descripcion');		
		
		//Guardar el registro
		if( $proyecto->save() ){
			
			$p_id = $proyecto->id;
			
			$msg.="<p><strong>Registro Actualizado</strong></p>";
						
			//Si existe archivo de logo
			if( Input::hasFile('logo') ){
				
				//Path
				$path=public_path().'/'.$this->pathUpload.'proy_id_'.$p_id.'/';
				
				//Eliminar carpeta si existe
				if ( File::exists($path) ){
					File::deleteDirectory($path);
				}
							
				$inputFile=Input::file('logo');//Input del formulario
				$inputPath=$inputFile->getRealPath();
				
				$pos=strrpos($inputFile->getClientOriginalName(), '.');
				$inputName=substr($inputFile->getClientOriginalName(), 0, $pos);
				$inputExt=substr(strrchr($inputFile->getClientOriginalName(),'.'),1);
				
				////////#####  Upload File #####////////
				//Instanciar clase para subir archivo(s)
				$upload = new classUpload\upload;
				$upload->upload($inputPath, 'es_ES');
				
				//Nuevo nombre de archivo
				$upload->file_new_name_body=$inputName;
				$upload->file_new_name_ext=$inputExt;
				//Maximo tamaño del archivo
				$upload->file_max_size = '10485760'; //10MB=10485760 / 5MB=5242880  / 1KB=1024
				//Extensiones permitidas
				$upload->allowed = array('image/png','image/jpg', 'image/jpeg');
				//Maximo de pixeles de la imagen, si es mayor no se carga
				$upload->image_max_width 	= 10000;
				$upload->image_max_height 	= 10000;
				
				$nameArchivoOrigen='';
				if ($upload->uploaded) {
					
					$upload->file_safe_name=true;
					//$upload->file_name_body_pre = 'redim_';
					$upload->image_resize = true;
					$upload->image_convert = 'png';
					$upload->image_x = 153;
					$upload->image_y = 91;
					//$upload->image_ratio_y = true;
					$upload->image_ratio_crop      = true;
					//$upload->image_ratio_fill      = true;
					$upload->Process($path);
					$nameArchivoOrigen=$upload->file_dst_name_body.".".$upload->file_dst_name_ext;
					if ($upload->processed) {
												  
						//###################    ##################//		
						if( !empty($nameArchivoOrigen) ){
							
							
							//guardar Registro
							//$proyecto = Proyectos::find($p_id);
							
							$proyecto->logo = $nameArchivoOrigen;
								
							if($proyecto->save()){
								$msg.='<p>Archivo <b>'.$nameArchivoOrigen.'</b> copiado</p>';
							}
							else{
								$msg.="<p>Error: <b>Registro no guardado</b></p>";
							}
							
							
						}
						// $upload->Clean();
					
					} else {
						$msg.='<p>Error : <b>'.$upload->error.'</b></p>';
					}
					
					
				} 
				else{
					$msg.='<p>Error : <b>No se puede cargar el Archivo</b></p>';
				}
				
			}
			
		}
		else{
			$msg.='<p>Error : <b>No se pudo guardar el registro</b></p>';
		}
		
		/////////////////////////////////////////////////
		
		//
		/*if( empty($id) ){
			return Redirect::to('admin/proyectos');
		}*/
		
		if( !empty($msg) ){
			Session::flash('msg', $msg);
		}
		
		if( !empty($p_id) ){
			return Redirect::to('admin/proyectos/edit/'.$p_id);
		}
		else{
			return Redirect::back();
		}
		//$path="'".$this->pathUpload."proy_id_',id,'/'";
		
		//$proyecto = Proyectos::select('*', DB::raw("CONCAT(".$path.") AS path") )->find($p_id);
		
		/*return View::make('admin.proyectos.proyecto-edit', 
					array('proyecto'		=>$proyecto)
				)->with('msg', $msg);*/
		
		
	}
	

}

?>