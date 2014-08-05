<?php

class admin_ProyectosController extends BaseController {
	
	public function __construct(){
		$this->beforefilter('auth'); //bloqueo de acceso, aplicar FILTRO
	}
	
	//Usando RESTFul
	//Ver Proyectos
	public function getIndex()
	{
		
		$limit=10;
		
		$path="'".$this->pathUpload."proy_id_',id,'/'";
		
		$proyectos = Proyectos::select('*', DB::raw("CONCAT(".$path.") AS path") )
							  ->orderBy('created_at', 'desc')
							  ->orderBy('id', 'desc');
		
		
		$campo=Input::get('campo', '');
		$search=Input::get('search', '');
		
		if( !empty($campo) and !empty($search) ){
			$proyectos=$proyectos->where($campo, 'LIKE', '%'.$search.'%');
		}
		
		if( !empty($search) and empty($campo) ){
			$proyectos=$proyectos->where("id", 'LIKE', '%'.$search.'%')
								 ->orWhere("titulo", 'LIKE', '%'.$search.'%');
		}
		
		
		if ( Request::ajax() ) {
			
			$proyectos=$proyectos->paginate( $limit );
			
			return Response::json(View::make('admin.proyectos.index_ajax_proyectos_list', 
				array(
					'proyectos'=>$proyectos
				)
			)->render());
			
		}
		
		$proyectos=$proyectos->paginate( $limit );
		//return DB::getQueryLog();
		
		
		return View::make('admin.proyectos.index_proyectos', 
				array(
				   'proyectos'		=>$proyectos
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
	
	//
	//Eliminar Proyecto
	public function getAjax_delete_proyecto(){
		return Redirect::to('admin/proyectos');
	}
	
	//Eliminar Proyecto
	public function postAjax_delete_proyecto(){
		
		if( Request::ajax() ){
			
			$msg = '';
			$bandera = false;
			
			$titulo	= Input::get('titulo');
			
			if( Input::has('p_id') ){
				
				$p_id = Input::get('p_id');
				
				$proyecto = Proyectos::find( $p_id );
			
				if( $proyecto ){
					
					if( $proyecto->delete() ){
						$msg.='<p>Proyecto '.$titulo.' Eliminado</p>';
						//eliminar comentarios
						$comentarios = ProyectoComentarios::where('proyecto_id', '=', $p_id);
						
						if( $comentarios ){
							if( $comentarios->delete() ){
								$msg.='<p>Comentarios Eliminados</b></p>';
							}
							else{
								$msg.='<p>Error: <b>No se Eliminaron los comentarios</b></p>';
							}
						}
						else{
							$msg.='<p>El proyecto '.$titulo.' no ten&iacute;a comentarios</p>';
						}
						
						//eliminar imagenes slideshow
						$slideshow = ImagenesSlideshow::where('proyecto_id', '=', $p_id);
						
						if( $slideshow ){
							if( $slideshow->delete() ){
								$msg.='<p>Im&aacute;genes de Slideshow Eliminadas</b></p>';
							}
							else{
								$msg.='<p>Error: <b>No se Eliminaron las im&aacute;genes del Slideshow</b></p>';
							}
						}
						else{
							$msg.='<p>El proyecto '.$titulo.' no tiene im&aacute;genes Slideshow</p>';
						}
						
						//eliminar imagenes mosaico
						$imagenes_mosaico = ImagenesMosaico::where('proyecto_id', '=', $p_id);
						
						if( $imagenes_mosaico ){
							if( $imagenes_mosaico->delete() ){
								$msg.='<p>Im&aacute;genes de Mosaico Eliminadas</b></p>';
							}
							else{
								$msg.='<p>Error: <b>No se Eliminaron las im&aacute;genes de Mosaico</b></p>';
							}
						}
						else{
							$msg.='<p>El proyecto '.$titulo.' no tiene im&aacute;genes de Mosaico</p>';
						}
												
						$path = public_path().'/'.$this->pathUpload.'proy_id_'.$p_id.'/';
						
						if ( File::exists($path) ){
							File::deleteDirectory($path);
							$bandera=true;
						}
					}
					
					
				}
				else{
					$msg.='<p>El proyecto ya no existe</p>';
				}
				
			}
			
			return Response::json(
				array(
					'success' => $bandera,
					'p_id'	  => $p_id,
					'titulo'  => $titulo,
					'msg'	  => $msg,
				)
			);
			
		}
		
		
	}
	
	
	//////////////////////////////
	//////////// SLIDE ///////////
	//////////////////////////////
	
	public function listaSlides($p_id)
	{
		$proyecto = Proyectos::where('id', '=', $p_id)->first();
		
		$salida='';
		if( count($proyecto->imagenes_slideshow()) > 0 ){
			
			ob_start();
			
			foreach( $proyecto->imagenes_slideshow() as $s ){
				if( is_file($s->path.$s->archivo) ){ 
					?>
					<div class="slide-cont cf" id="slide_<?php echo $s->id; ?>" style="display:none;">
                        <img src="<?php echo url($s->path.$s->archivo); ?>" alt="<?php echo $proyecto->titulo; ?>">
                        <span><label>Orden:&nbsp;</label><input type="text" name="orden[<?php echo $s->id; ?>]" value="<?php echo $s->ordering; ?>" class="validate[required, custom[integer]]"/></span>&nbsp;
                        <span id="loader_order_s<?php echo $s->id; ?>">
                            <a style="cursor:pointer;" onclick="orderingSlide('<?php echo $s->id; ?>');" class="save-slide">Guardar</a>
                        </span>                        
                        <span id="loader_del_s<?php echo $s->id; ?>">
                        	<a style="cursor:pointer;" class="right delete-slide" onclick="delSlide('<?php echo $s->id; ?>');">X</a>
                        </span>
                    </div>
                    
                    <?php
				}
			}
			
			$salida = ob_get_clean();
		}
		
		return $salida;
		
	}
	
	//
	public function getAjax_add_slide()
	{
		return Redirect::to('admin/proyectos');
	}
	
	//Agregar imagen con AJAX
	public function postAjax_add_slide()
	{
		$msg='';
		if( Request::ajax() ){
			//Si existe archivo de imagen e imagen de logo
			if( Input::hasFile('slide') and Input::has('p_id') ){
				
				$p_id=Input::get('p_id');
				
				//guardar Registro
				$imagen = new ImagenesSlideshow; //instancia del modelo de la clase Producto
				
				$imagen->proyecto_id= $p_id;
				
				//$imagen->archivo	= $nameArchivoOrigen;
				//$imagen->logo 		= $nameLogoOrigen;
			
				if($imagen->save()){
					
					//Path
					$pre_path = $this->pathUpload.'proy_id_'.$p_id.'/slide/id_'.$imagen->id.'/';
					$path=public_path().'/'.$pre_path;
								
					
					////////#####  Upload File #####////////
					//Instanciar clase para subir archivo(s)
					$upload = new classUpload\upload;
					
					////////////// IMAGEN ///////////////
					
					$inputFile=Input::file('slide');//Input del formulario
					$inputPath=$inputFile->getRealPath();
					
					$pos=strrpos($inputFile->getClientOriginalName(), '.');
					$inputName=substr($inputFile->getClientOriginalName(), 0, $pos);
					$inputExt=substr(strrchr($inputFile->getClientOriginalName(),'.'),1);
					
					$upload->upload($inputPath, 'es_ES');
					
					//Nuevo nombre de archivo
					$upload->file_new_name_body=$inputName;
					$upload->file_new_name_ext=$inputExt;
					//Maximo tamaño del archivo
					$upload->file_max_size = '5242880'; //10MB=10485760 / 5MB=5242880  / 1KB=1024
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
						//$upload->image_convert = 'jpg';
						$upload->image_x = 619;
						$upload->image_y = 471;
						//$upload->image_ratio_y = true;
						$upload->image_ratio_crop      = true;
						//$upload->image_ratio_fill      = true;
						$upload->Process($path);
						if ($upload->processed) {
							$nameArchivoOrigen=$upload->file_dst_name_body.".".$upload->file_dst_name_ext;
							
							// $upload->Clean();
						
						} else {
							$msg.='<p>Error : <b>'.$upload->error.'</b></p>';
						}
						
						
					} 
					else{
						$msg.='<p>Error : <b>No se puede cargar el Archivo</b></p>';
					}
					
					
					//###################    ##################//		
					if( !empty($nameArchivoOrigen) ){
						
						//guardar Registro
						//$imagen = new ImagenesMosaico; //instancia del modelo de la clase Producto
						
						$imagen->archivo	= $nameArchivoOrigen;
					
						if($imagen->save()){
							$msg.='<p>Archivo <b>'.$nameArchivoOrigen.'</b> copiado</p>';
							
							$salida = $this->listaSlides($p_id);
												
							return Response::json(
								array(
									'success' => true,
									'img_id'  => $imagen->id,
									'p_id'	  => $p_id,
									'msg'	  => $msg,
									'salida'  => $salida,
								)
							);
							
						}
						else{
							$msg.="<p>Error: <b>Registro no guardado, intenta nuevamente</b></p>";
							
							return Response::json(
								array(
									'success' => false,
									'img_id'  => '',
									'p_id'	  => $p_id,
									'msg'	  => $msg,
								)
							);
							
						}
						
						
						
						
					}
					///////////////////////////////////////////////////////////
					
				}
				else{
					
					$msg.="<p>Error: <b>Registro no guardado, intenta nuevamente</b></p>";
					
					return Response::json(
						array(
							'success' => false,
							'img_id'  => '',
							'p_id'	  => $p_id,
							'msg'	  => $msg,
						)
					);
					
				}
				
				
				
			}
		}
		
	}
	
	//
	public function getAjax_delete_slide()
	{
		return Redirect::to('admin/proyectos');
	}
	
	//Eliminar imagen con AJAX
	public function postAjax_delete_slide()
	{
		
		if( Request::ajax() ){
			
			$bandera=false;
			
			if( Input::has('s_id') and Input::has('p_id') ){
				
				$imagen = ImagenesSlideshow::find( Input::get('s_id') );
			
				if( $imagen ){
					
					if( $imagen->delete() ){
						
						$path=public_path().'/'.$this->pathUpload.'proy_id_'.Input::get('p_id').'/slide/id_'.$imagen->id.'/';
						
						if ( File::exists($path) ){
							File::deleteDirectory($path);
						}
						
						$bandera=true;
					}
					
					
				}
				
			}
			
			if( $bandera===true ){
				
				return Response::json(
					array(
						'success' => true,
						's_id'	  => $imagen->id,
						'p_id'	  => $imagen->proyecto_id,
					)
				);
				
			}
			else{
				
				return Response::json(
					array(
						'success' => false,
						'id'	  => '',
					)
				);
				
			}
			
		}
		
		
	}
	
	
	//
	public function getAjax_ordering_slide()
	{
		return Redirect::to('admin/proyectos');
	}
	
	//Ordenar las imagenes con AJAX
	public function postAjax_ordering_slide()
	{
		if( Request::ajax() ){
			
			if( Input::has('orden') and Input::has('p_id') ){
				
				$arrOrden = Input::get('orden');
				$p_id	  = Input::get('p_id');
				
				foreach($arrOrden as $key => $value){
					
					$imagen = ImagenesSlideshow::where('id', '=', $key)->where('proyecto_id', '=', $p_id )->first();
					
					if($imagen){
						$imagen->ordering = $value;
						$imagen->save();
					}
					
				}
				
				$salida = $this->listaSlides($p_id);
											
				return Response::json(
							array(
								'success' => true,
								'p_id'	  => $p_id,
								'salida'  => $salida,
							)
						);
				
			}						
						
		}
		
	}
	
	//////////////////////////////
	/////////// MOSAICO //////////
	//////////////////////////////
	
	//
	public function getAjax_add_img()
	{
		return Redirect::to('admin/proyectos');
	}
	
	//Agregar imagen con AJAX
	public function postAjax_add_img()
	{
		$msg='';
		if( Request::ajax() ){
			//Si existe archivo de imagen e imagen de logo
			if( Input::hasFile('mosaico') and Input::hasFile('mosaico_logo') and Input::has('p_id') ){
				
				$p_id=Input::get('p_id');
				
				//guardar Registro
				$imagen = new ImagenesMosaico; //instancia del modelo de la clase Producto
				
				$imagen->proyecto_id= $p_id;
				
				//$imagen->archivo	= $nameArchivoOrigen;
				//$imagen->logo 		= $nameLogoOrigen;
			
				if($imagen->save()){
					
					//Path
					$pre_path = $this->pathUpload.'proy_id_'.$p_id.'/img/id_'.$imagen->id.'/';
					$path=public_path().'/'.$pre_path;
								
					
					////////#####  Upload File #####////////
					//Instanciar clase para subir archivo(s)
					$upload = new classUpload\upload;
					
					////////////// IMAGEN ///////////////
					
					$inputFile=Input::file('mosaico');//Input del formulario
					$inputPath=$inputFile->getRealPath();
					
					$pos=strrpos($inputFile->getClientOriginalName(), '.');
					$inputName=substr($inputFile->getClientOriginalName(), 0, $pos);
					$inputExt=substr(strrchr($inputFile->getClientOriginalName(),'.'),1);
					
					$upload->upload($inputPath, 'es_ES');
					
					//Nuevo nombre de archivo
					$upload->file_new_name_body=$inputName;
					$upload->file_new_name_ext=$inputExt;
					//Maximo tamaño del archivo
					$upload->file_max_size = '5242880'; //10MB=10485760 / 5MB=5242880  / 1KB=1024
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
						$upload->image_convert = 'jpg';
						$upload->image_x = 240;
						$upload->image_y = 240;
						//$upload->image_ratio_y = true;
						$upload->image_ratio_crop      = true;
						//$upload->image_ratio_fill      = true;
						$upload->Process($path);
						
						if ($upload->processed) {
							$nameArchivoOrigen=$upload->file_dst_name_body.".".$upload->file_dst_name_ext;
							// $upload->Clean();
						
						} else {
							$msg.='<p>Error : <b>'.$upload->error.'</b></p>';
						}
						
						
					} 
					else{
						$msg.='<p>Error : <b>No se puede cargar el Archivo</b></p>';
					}
					
					////////////// IMAGEN HOVER ///////////////
					
					$inputFile=Input::file('mosaico_logo');//Input del formulario
					$inputPath=$inputFile->getRealPath();
					
					$pos=strrpos($inputFile->getClientOriginalName(), '.');
					$inputName=substr($inputFile->getClientOriginalName(), 0, $pos);
					$inputExt=substr(strrchr($inputFile->getClientOriginalName(),'.'),1);
					
					$upload->upload($inputPath, 'es_ES');
					
					//Nuevo nombre de archivo
					$upload->file_new_name_body=$inputName;
					$upload->file_new_name_ext=$inputExt;
					//Maximo tamaño del archivo
					$upload->file_max_size = '5242880'; //10MB=10485760 / 5MB=5242880  / 1KB=1024
					//Extensiones permitidas
					$upload->allowed = array('image/png','image/jpg', 'image/jpeg');
					//Maximo de pixeles de la imagen, si es mayor no se carga
					$upload->image_max_width 	= 10000;
					$upload->image_max_height 	= 10000;
					
					$nameLogoOrigen='';
					if ($upload->uploaded) {
						
						$upload->file_safe_name=true;
						$upload->file_name_body_pre = 'logo_';
						$upload->image_resize = true;
						$upload->image_convert = 'png';
						$upload->image_x = 153;
						$upload->image_y = 91;
						//$upload->image_ratio_y = true;
						$upload->image_ratio_crop      = true;
						//$upload->image_ratio_fill      = true;
						$upload->Process($path);
						if ($upload->processed) {
							$nameLogoOrigen=$upload->file_name_body_pre.$upload->file_dst_name_body.".".$upload->file_dst_name_ext;
							// $upload->Clean();
						
						} else {
							$msg.='<p>Error : <b>'.$upload->error.'</b></p>';
						}
						
						
					} 
					else{
						$msg.='<p>Error : <b>No se puede cargar el Logo</b></p>';
					}
					
					
					//###################    ##################//		
					if( !empty($nameArchivoOrigen) and !empty($nameLogoOrigen) ){
						
						//guardar Registro
						//$imagen = new ImagenesMosaico; //instancia del modelo de la clase Producto
						
						$imagen->archivo	= $nameArchivoOrigen;
						$imagen->logo 		= $nameLogoOrigen;
					
						if($imagen->save()){
							$msg.='<p>Archivo <b>'.$nameArchivoOrigen.' y '.$nameLogoOrigen.'</b> copiado</p>';
							
							$proyecto = Proyectos::select('titulo')->find($p_id);
							
							$salida='';
							ob_start();
							?>
							<div class="proy-home" id="img_<?php echo $imagen->id; ?>" style="display:none;">
									<img src="<?php echo url($pre_path.$nameArchivoOrigen); ?>" alt="<?php echo $proyecto->titulo; ?>" class="proy-img-home">
									<?php /*?><div class="proy-home-hover">
										<img src="<?php echo url($pre_path.$nameLogoOrigen); ?>">
									</div><?php */?>
								<div id="loader_del<?php echo $imagen->id; ?>">
									<a style="cursor:pointer;" data-id="<?php echo $imagen->id; ?>" onclick="delImg('<?php echo $imagen->id; ?>');" class="eliminar_img cf">Eliminar</a>
								</div>
							</div>
							<?php
							$salida = ob_get_clean();
												
							return Response::json(
								array(
									'success' => true,
									'img_id'  => $imagen->id,
									'p_id'	  => $p_id,
									'msg'	  => $msg,
									'salida'  => $salida,
								)
							);
							
						}
						else{
							$msg.="<p>Error: <b>Registro no guardado, intenta nuevamente</b></p>";
							
							return Response::json(
								array(
									'success' => false,
									'img_id'  => '',
									'p_id'	  => $p_id,
									'msg'	  => $msg,
								)
							);
							
						}
						
						
						
						
					}
					///////////////////////////////////////////////////////////
					
				}
				else{
					
					$msg.="<p>Error: <b>Registro no guardado, intenta nuevamente</b></p>";
					
					return Response::json(
						array(
							'success' => false,
							'img_id'  => '',
							'p_id'	  => $p_id,
							'msg'	  => $msg,
						)
					);
					
				}
				
				
				
			}
		}
		
	}
	
	//
	public function getAjax_delete_img()
	{
		return Redirect::to('admin/proyectos');
	}
	
	//Eliminar imagen con AJAX
	public function postAjax_delete_img()
	{
		
		if( Request::ajax() ){
			
			$bandera=false;
			
			if( Input::has('img_id') and Input::has('p_id') ){
				
				$imagen = ImagenesMosaico::find( Input::get('img_id') );
			
				if( $imagen ){
					
					if( $imagen->delete() ){
						
						$path=public_path().'/'.$this->pathUpload.'proy_id_'.Input::get('p_id').'/img/id_'.$imagen->id.'/';
						
						if ( File::exists($path) ){
							File::deleteDirectory($path);
						}
						
						$bandera=true;
					}
					
					
				}
				
			}
			
			if( $bandera===true ){
				
				return Response::json(
					array(
						'success' => true,
						'img_id'	  => $imagen->id,
						'p_id'	  => $imagen->proyecto_id,
					)
				);
				
			}
			else{
				
				return Response::json(
					array(
						'success' => false,
						'id'	  => '',
					)
				);
				
			}
			
		}
		
		
	}

}

?>