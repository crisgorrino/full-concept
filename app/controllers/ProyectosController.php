<?php

class ProyectosController extends BaseController {
	
	public $limit = 6;
	//Usando RESTFul
	//Ver ordenes
	public function getIndex()
	{
		$mosaico = new ImagenesMosaico;
		return View::make('pages.proyectos', array( 'mosaico' => $mosaico->imagenes_mosaico() ) );
	}
	
	public function getProyecto_detalle($id=null)
	{
		if( empty($id) ){
			return Redirect::to('/');
		}
		
		$path="'".$this->pathUpload."proy_id_',id,'/'";
		
		$proyecto = Proyectos::select('*', DB::raw("CONCAT(".$path.") AS path") )
							 ->where('id', '=', $id)
							 ->first();
		
		//return DB::getQueryLog();
		
		if( !$proyecto ){
			return Redirect::to('/');
		}
		
		//Buscar otros proyectos aleatorios		
		$otrosProy = Proyectos::select('id',  'titulo','logo', DB::raw("CONCAT(".$path.") AS path") )
							  ->where('id', '!=', $proyecto->id)
							  ->whereNotNull('logo')
							  ->where('logo', '!=', '')
							  ->orderBy(DB::raw('RAND()'))
							  ->take(6)
							  ->get();
		//return DB::getQueryLog();
		
		//Obtener los comentarios
		$limit = $this->limit;
		Session::put('sql_limit', $limit);
		
		$comentarios = ProyectoComentarios::where('proyecto_id', '=', $proyecto->id)
					 ->where('published', '=', 1)
					 //registros que no esten eliminadas logicamente
					 ->where('removed', '=', 0)
					 //0=Pendiente, 1=Aprobado, 2=No Aprobado
					 ->where('status_id', '=', 1)
					 //->orderBy('productos.created_at', 'DESC')
					 //->orderBy('ordering', 'ASC')
					 ->orderBy('id', 'DESC')
					 ->take($limit)
					 ->get();
		//return DB::getQueryLog();
		
		return View::make('pages.proyecto-detalle', array('proyecto' => $proyecto, 'otrosProy' => $otrosProy, 'comentarios' => $comentarios) );
		
	}
	
	public function postProyectoComentarios()
	{
		
		$limit = $this->limit;
		
		$id = Input::get('id', '');
		
		if( empty($id) ){
			return Response::json( array() );
		}
		
		
		if ( Request::ajax() ) {
			
			$comentarios = ProyectoComentarios::where('published', '=', 1)
					 //registros que no esten eliminadas logicamente
					 ->where('removed', '=', 0)
					 ->where('proyecto_id', '=', $id)
					 //->orderBy('productos.created_at', 'DESC')
					 //->orderBy('ordering', 'ASC')
					 ->orderBy('id', 'DESC');			
			
			if( !Session::has('sql_limit') ){ 
				Session::put('sql_limit', $limit);
			}
			
			
			
			$comentarios = $comentarios->skip( Session::get('sql_limit') )
			->take($limit)
			//->take( DB::raw( Session::get('sql_limit').",".$limit) )
			->get();
				
			if( count($comentarios) > 0 ){ 
				Session::put('sql_limit', Session::get('sql_limit')+$limit);
			}
			
			//$sql.="LIMIT ".$_SESSION["cantidadcargadas_img"].",".$limite;
					
			//$productos=$productos->paginate( Session::get('sql_limit').",".$limit );
			//return DB::getQueryLog();
			
			return Response::json(View::make('pages.ajax_comentarios', array('comentarios' => $comentarios))->render());
			
		}
		
	}
	
	public function postAddComentario()
	{
		if( Request::ajax() ){
			
			if( Input::has('id') and Input::has('num_stars') and Input::has('comentario') and Input::has('nombre') ){
				
				$com	= strip_tags(Input::get('comentario'));
				$nombre = strip_tags(Input::get('nombre'));
				$stars	= Input::get('num_stars');
				
				$comentario = new ProyectoComentarios;//instancia de modelo
		
				$comentario->proyecto_id	= Input::get('id');
				$comentario->comentario 	= $com;
				$comentario->nombre 		= $nombre;
				$comentario->stars			= $stars;
				
				if($comentario->save()){
					ob_start();
					?>
					<div class="comentario cf" id="com_<?php echo $comentario->id; ?>" style="display:none;">
						<p><?php echo $com; ?></p>
						
						<span class="left by"><?php echo $nombre; ?></span> 
						
						<span class="right stars-cont-small">
										
						</span>
						
						<span class="right stars-cont-small cf">
							<?php 
							if( $stars > 0 ){
								for($i=0; $i < $stars; $i++){ 
									?><i class="icon-star3"></i><?php
								}
							}
							?>
						</span>            
					</div>
					<?php
					$salida = ob_get_clean();
					
					return Response::json(
						array(
							'success' => true,
							'com'	  => $salida,
							'id'	  => $comentario->id,
						)
					);
				}
				
			}
			else{
				return Response::json(
					array(
						'success' => false,
						'com'	  => '',
					)
				);
			}
			
		}
		else{
			
			if( Input::has('id') ){
				return Redirect::back();
			}
			else{
				return Redirect::to('/');
			}
			
		}
		
	}

}

?>