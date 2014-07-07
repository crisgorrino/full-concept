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
	
	//
	public function getAjax_delete()
	{
		return Redirect::to('admin/comentarios');
	}
	
	//Eliminar
	public function postAjax_delete()
	{
		if( Request::ajax() ){
					
			$msg='';
			
			$com_id 	= Input::get('com_id');
			$nombre	= Input::get('nombre');
			
			$comentario = ProyectoComentarios::find($com_id);
			
			//retornar error si no existe registro
			if( $comentario ){
				
				if( $comentario->delete() ){
					
					$msg.="<p>Registro <strong>".$nombre."</strong> Eliminado correctamente.</p>";
					
					return Response::json(
						array(
							'success' => true,
							'com_id'	  => $com_id,
							'msg'	  => $msg,
						)
					);
					
				}
				else{
					
					$msg.="<p>Error: <b>No se Elimin&oacute; el Registro ".$nombre." Intenta nuevamente.</b></p>";
							
					return Response::json(
						array(
							'success' => false,
							'com_id'	  => $com_id,
							'msg'	  => $msg,
						)
					);
					
				}
		
			}			
			else{
				$msg.="<p>Error: <b>No existe el Registro ".$nombre.".</b></p>";
						
				return Response::json(
					array(
						'success' => false,
						'com_id'	  => $com_id,
						'msg'	  => $msg,
					)
				);
	
			}
				
		
		}
		
		
	}
	
	//
	public function getAjax_status()
	{
		return Redirect::to('admin/comentarios');
	}
	
	//Modificar Status
	public function postAjax_status()
	{
		if( Request::ajax() ){
					
			$msg='';
			
			$com_id 	= Input::get('com_id');
			$nombre		= Input::get('nombre');
			$status		= Input::get('status');
			
			$comentario = ProyectoComentarios::find($com_id);
			
			//retornar error si no existe registro
			if( $comentario ){
				
				$comentario->status_id=$status;
				
				if( $comentario->save() ){
					
					//contar el numero de registros pendientes
					$comPendientes =ProyectoComentarios::select( DB::raw('COUNT(*) AS total') )->where('status_id', '=', '0')->first();
					
					$msg.="<p>Status <strong>".$nombre."</strong> Guardado.</p>";
					
					return Response::json(
						array(
							'success' => true,
							'com_id'  => $com_id,
							'msg'	  => $msg,
							'totalPendientes'	=> $comPendientes->total,
						)
					);
					
				}
		
			}			
			else{
				$msg.="<p>Error: <b>No existe el Registro ".$nombre.".</b></p>";
						
				return Response::json(
					array(
						'success' => false,
						'com_id'	  => $com_id,
						'msg'	  => $msg,
					)
				);
	
			}
				
		
		}
		
		
	}
	

}

?>