@extends('layouts.master')

@section('title')@parent- Proyectos @stop

@include('admin.header')

@section('content-mast')
	
    <!--detalle-->
    <section  class="cont-shadow ">
        <section class="proyectos-det-cont cf  ">
            @if( Session::has('msg') )
                <p>{{ Session::get('msg') }}</p>
            @endif
            <div class="det-image-container proyecto-edit<?php /*?>cycle-slideshow<?php */?> ">
            	@if( !isset($proyecto->id) )
                    <h2>Para agregar im&aacute;genes al Slide primero guarda el registro</h2>
                    <img src="{{ asset('img/detail_nodisponible.jpg') }}" alt="Imagen no disponible" />
                @else
                    <h2>Administrar Im&aacute;genes de SlideShow</h2>
                    <div id="ajax_msg_slide"></div>
                    <p>
                        <form name="frm_add_slide" id="frm_add_slide" method="post" enctype="multipart/form-data" class="cf">
                            <input type="hidden" name="p_id" class="validate[required]" value="{{ $proyecto->id }}" />
                            <label class="yellow">Imagen Slide (619px X 471px)</label><br />
                            <input type="file" name="slide" id="slide" class="validate[required]" />
                            <p><input type="submit" class="save_img_slide" value="Subir" /><span id="ajax_loader_slide"></span></p>
                        </form>
                    </p>
                    <form name="frm_elementos" id="frm_elementos" method="post">
                        <input type="hidden" name="p_id" class="validate[required]" value="{{ $proyecto->id }}" />
                        <div id="ajax_slide" class="cf">            	
                            @if( isset($proyecto) and count($proyecto->imagenes_slideshow()) > 0 )
                                @foreach( $proyecto->imagenes_slideshow() as $s )
                                    @if( is_file($s->path.$s->archivo) )
                                        <div class="slide-cont cf" id="slide_{{ $s->id }}">
                                            <img src="{{ asset($s->path.$s->archivo) }}" alt="{{{ $proyecto->titulo }}}">
                                            <span><label>Orden:&nbsp;</label><input type="text" name="orden[{{ $s->id }}]" value="{{ $s->ordering }}" class="validate[required, custom[integer]]"/></span>&nbsp;
                                            <span id="loader_order_s{{ $s->id }}">
                                                <a style="cursor:pointer;" onclick="orderingSlide('{{ $s->id }}');" class="save-slide">Guardar</a>
                                            </span>
                                            <span id="loader_del_s{{ $s->id }}">
                                                <a style="cursor:pointer;" class="right delete-slide" onclick="delSlide('{{ $s->id }}');">X</a>
                                            </span>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </form>
                @endif
            </div>
            <div class="det-info">
                <form name="frm_edit" id="frm_edit" method="post" enctype="multipart/form-data">
                    <input type="hidden" id="id" name="id" value="{{ isset($proyecto->id)?$proyecto->id:'' }}" readonly="readonly" />
                    <h2><input type="text" name="titulo" placeholder="T&iacute;tulo" value="{{{ isset($proyecto->titulo)?$proyecto->titulo:'' }}}" class="comentarios-area validate[required]" /></h2><br />
                    <p><textarea name="concepto" placeholder="Concepto" class="handle-input validate[required]">{{{ isset($proyecto->concepto)?$proyecto->concepto:'' }}}</textarea></p>
                    @if( isset($proyecto->logo) and is_file($proyecto->path.$proyecto->logo) )
                    <img src="{{ asset($proyecto->path.$proyecto->logo) }}" alt="torre-eMe" class="proyecto-logo">
                    @endif
                    <p><br />
                    <label>Logo proyecto (PNG medida 153 X 91px):</label>
                    <input type="file" name="logo" id="logo" class="handle-input" /><br />
                    </p>
                    <p><textarea name="descripcion" placeholder="Descripci&oacute;n del proyecto" class="handle-input validate[required]">{{{ isset($proyecto->descripcion)?$proyecto->descripcion:'' }}}</textarea></p>
                    <a href="#" class="compartir-detalle left save"><span>GUARDAR</span></a>
                    &nbsp;&nbsp;
                    <a href="{{ url('admin/proyectos') }}" class="compartir-detalle right"><span>REGRESAR</span></a>&nbsp;&nbsp;
                </form>
            </div>
        </section>
    </section>
    <!--detalle-->
    
    
    <!--detalle comentarios y otros proyectos-->
    <section class="comentarios-cont cf">
        @if( !isset($proyecto->id) )
            <h3>Para agregar im&aacute;genes de Mosaico primero guarda el registro</h3>
        @else
            
            <h3 class="yellow">Administrar Im&aacute;genes para MOSAICO</h3>
            <div id="ajax_msg_mosaico"></div>
            <form name="frm_add_img" id="frm_add_img" method="post" enctype="multipart/form-data" class="cf">
                <input type="hidden" name="p_id" class="validate[required]" value="{{ $proyecto->id }}" />
                <p ><label class="yellow">Imagen Mosaico (240xp X 240px, extension JPG)</label><br /><input type="file" name="mosaico" id="mosaico" class="validate[required]" /></p>
                <p><label class="yellow">Logo Transparencia Mosaico (153px X 91px, extension PNG)</label><br /><input type="file" name="mosaico_logo" id="mosaico_logo" class="validate[required]" /><br /></p>
                <p><input type="submit" class="save_img_mosaico" value="Agregar" /><span id="ajax_loader_add"></span></p>
            </form>
            
            <div class="comentarios cf" id="ajax_imagenes">  
                      
                @if( count($proyecto->imagenes_mosaico()) > 0 )
                    @foreach( $proyecto->imagenes_mosaico() as $i )
                        @if( is_file($i->path.$i->archivo) )
                            <div class="proy-home" id="img_{{ $i->id }}">

                                    <img src="{{ url($i->path.$i->archivo) }}" alt="{{{ $i->proyecto()->titulo }}}" class="proy-img-home">
                                    <?php /*?><div class="proy-home-hover">
                                        <img src="{{ url($i->path.$i->logo) }}">
                                    </div><?php */?>
                                <div id="loader_del{{ $i->id }}">
                                    <a style="cursor:pointer;" data-id="{{ $i->id }}" onclick="delImg('{{ $i->id }}');" class="eliminar_img cf">Eliminar</a>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endif
            </div>
        @endif
    </section>
    <!--detalle comentarios y otros proyectos-->
    
@stop

@section('scripts')
	@parent    
    <!--jQuery Validation Engine -->
    <link rel="stylesheet" href="{{ asset('js/jQuery-Validation-Engine/css/validationEngine.jquery.css') }}" type="text/css"/>
    <?php /*?><script src="{{ asset('js/jQuery-Validation-Engine/js/jquery-1.8.2.min.js') }}" type="text/javascript"><?php */?>
    </script>
    <script src="{{ asset('js/jQuery-Validation-Engine/js/languages/jquery.validationEngine-es.js') }}" type="text/javascript" charset="utf-8">
    </script>
    <script src="{{ asset('js/jQuery-Validation-Engine/js/jquery.validationEngine.js') }}" type="text/javascript" charset="utf-8">
    </script>
    <script type="text/javascript">
		$(document).ready(function(e) {
            $("#frm_edit, #frm_add_img, #frm_elementos, #frm_add_slide").validationEngine({ 
		        updatePromptsPosition:true,
				scroll:false
			});
        });
	</script>
    <!--jQuery Validation Engine -->
    
    <script src="{{ asset('js/spin.min.js') }}"></script>
    <style type="text/css">
		.spinner{
			margin: 3%;
			/*margin:20% 0;*/
		}
	</style>
    <script type="text/javascript" language="javascript">
    var optsSpin = {
      lines: 13, // The number of lines to draw
      length: 5, // The length of each line
      width: 4, // The line thickness
      radius: 7, // The radius of the inner circle
      corners: 1, // Corner roundness (0..1)
      rotate: 0, // The rotation offset
      direction: 1, // 1: clockwise, -1: counterclockwise
      color: '#000', // #rgb or #rrggbb
      speed: 1, // Rounds per second
      trail: 60, // Afterglow percentage
      shadow: false, // Whether to render a shadow
      hwaccel: false, // Whether to use hardware acceleration
      className: 'spinner', // The CSS class to assign to the spinner
      zIndex: 2e9, // The z-index (defaults to 2000000000)
      top: 'auto', // Top position relative to parent in px
      left: '100px' // Left position relative to parent in px
    };
    </script>
    
    <?php /*?><!-- scroll infinito -->
	<script type="text/javascript" src="{{ asset('js/infinite-ajax-scroll/jquery.scrollExtend.js') }}"></script>
    <script type="text/javascript">
    $(document).ready(function($)
    {	
		$('#ajax_comentarios').scrollExtend(
        {
            'target': '#ajax_comentarios',
            'url': '{{ url("proyecto-comentarios") }}',
			'beforeStart': function(){
				//alert($('#search').val());
				//this.url = '?search='+$('#search').val();
				//mi.ajaxSettings = { 'data': 'search='+$('#search').val() };
				
				return true;
			},
			'ajaxSettings' : { 'data': 'id={{ $proyecto->id }}' },
        });
		
    });
    </script>
    <style type="text/css">
        div.scrollExtend-loading {height: 32px;background-image:url('{{ asset("js/infinite-ajax-scroll/images/loading.gif") }}');background-position: center center;background-repeat: no-repeat; clear:both;}
    </style>
    <!-- FIN scroll infinito --><?php */?>
    
    <?php /*?><script src="{{ asset('js/jquery.cycle2.js') }}"></script>
    
    <script type="text/javascript" src="{{ asset('js/jquery.hoverdir.js') }}"></script>	
    <script type="text/javascript">
    $(document).ready(function(){
                $(function() {
                
                    $('.proy-home').each( function() { $(this).hoverdir({
                        hoverDelay :50,
                        reverse:true
                    }); } );
    
                });
        });		
    </script><?php */?>
    
    <script type="text/javascript">
	$(document).ready(function() {
        $('.save').click(function(e){
			e.preventDefault();
			
			if( $("#frm_edit").validationEngine('validate') ){
				$('#frm_edit').submit();
			}
			
		});
    });
	</script>
    
    @if( isset($proyecto->id) )
        <!-- Agregar o eliminar img con ajax -->
        <script type="text/javascript">		
        $(document).ready(function(e) {
            
            // Subir texto y archivos de formularios al mismo tiempo con Ajax y jQuery
            $.fn.formajax = function(i){
                // this formulario
                var a = $(this);
                // url
                var b = i.url;
                // success
                var c = i.success;
                
                var e = i.error;
				
				var bs = i.beforeSend;
                
                var dt= i.dataType;
            
                a.each(function(){
                    // this formulario específico
                    var d = $(this);
                    // Encontramos el botón Enviar del formulario al que le hicimos click
                    //##############d.find('input[type="submit"]').click(function(e){
                        // Prevenimos que recargue la página
                        //##############e.preventDefault();    
                        // Creamos un formdata                
                        formdata = new FormData();
                        // En el formdata colocamos todos los archivos que vamos a subir
                        for (var i = 0; i < (d.find('input[type=file]').length); i++) { 
                            // buscará todos los input con el valor "file" y subirá cada archivo. Serán diferenciados en el PHP gracias al "name" de cada uno.
                            formdata.append((d.find('input[type="file"]').eq(i).attr("name")),((d.find('input[type="file"]:eq('+i+')')[0]).files[0]));            
                        }
                            
                        for (var i = 0; i < (d.find('input').not('input[type=file]').not('input[type=submit]').length); i++) { 
                            // buscará todos los input menos el valor "file" y "sumbit . Serán diferenciados en el PHP gracias al "name" de cada uno.
                            formdata.append( (d.find('input').not('input[type=file]').not('input[type=submit]').eq(i).attr("name")),(d.find('input').not('input[type=file]').not('input[type=submit]').eq(i).val()) );            
                        }
            
                        // Arrancamos el ajax    
                        $.ajax({
                            url: b,
                            type: "POST",
                            contentType: false,
                            data:formdata,
                            //dataType: dt,
                            processData:false,
							beforeSend: bs,
                            error: e,
                            success: c 
                        });// fin de ajax    
                    //##############}) ; // fin de click 
                }); //fin del each
            }; // fin de la funcion
			            
			$('.save_img_mosaico').click(function(e){
				e.preventDefault();
								
				if( $('#frm_add_img').validationEngine('validate') ){
					
					$("#frm_add_img").formajax({
						type: "post",
						url:"{{ url('admin/proyectos/ajax-add-img') }}",
						dataType: "json",
						beforeSend: function(){
							//
							if (typeof spinnerAdd != "undefined")
							{
								spinnerAdd.stop();
							}
							spinnerAdd = new Spinner(optsSpin).spin(document.getElementById("ajax_loader_add"));
							
							return true;
							
						},
						error: function(xhr, error){
							//console.debug(xhr); console.debug(error);
							spinnerAdd.stop();
							alert('Error: '+error);
						 },
						success:function(datos){
							
							spinnerAdd.stop();
							
							if( datos.msg!='' ){
								$('#ajax_msg_mosaico').html(datos.msg);
							}
							
							if( datos.success ){
								$('#ajax_imagenes').prepend(datos.salida);
								$('#img_'+datos.img_id).fadeIn('slow');
								
								$("#mosaico").val('');
								$("#mosaico_logo").val('');
							}
							
							
						}
					}); // formajax
					
				}
				else{
					return false;
				}
				
			});
			
			$('.save_img_slide').click(function(e){
				e.preventDefault();
								
				if( $('#frm_add_slide').validationEngine('validate') ){
					
					$("#frm_add_slide").formajax({
						type: "post",
						url:"{{ url('admin/proyectos/ajax-add-slide') }}",
						dataType: "json",
						beforeSend: function(){
							//
							if (typeof spinnerAdd != "undefined")
							{
								spinnerAdd.stop();
							}
							spinnerAdd = new Spinner(optsSpin).spin(document.getElementById("ajax_loader_slide"));
							
							return true;
							
						},
						error: function(xhr, error){
							//console.debug(xhr); console.debug(error);
							spinnerAdd.stop();
							alert('Error: '+error);
						 },
						success:function(datos){
							
							spinnerAdd.stop();
							
							if( datos.msg!='' ){
								$('#ajax_msg_slide').html(datos.msg);
							}
							
							if( datos.success ){
								$('#ajax_slide').html(datos.salida);
								//$('#slide_'+datos.s_id).fadeIn('slow');
								$('.slide-cont').fadeIn('slow');
								
								$("#slide").val('');
							}
							
							
						}
					}); // formajax
					
				}
				else{
					return false;
				}
				
			});
			
        });
		
		var optsSpinDel = {
			  lines: 10, // The number of lines to draw
			  length: 4, // The length of each line
			  width: 3, // The line thickness
			  radius: 5, // The radius of the inner circle
			  corners: 1, // Corner roundness (0..1)
			  rotate: 0, // The rotation offset
			  direction: 1, // 1: clockwise, -1: counterclockwise
			  color: '#fff', // #rgb or #rrggbb
			  speed: 1, // Rounds per second
			  trail: 60, // Afterglow percentage
			  shadow: false, // Whether to render a shadow
			  hwaccel: false, // Whether to use hardware acceleration
			  className: 'spinner_del', // The CSS class to assign to the spinner
			  zIndex: 2e9, // The z-index (defaults to 2000000000)
			  top: 'auto', // Top position relative to parent in px
			  left: 'auto' // Left position relative to parent in px
			};
			
		function delSlide(s_id){
			
			//jConfirm('&iquest;Est&aacute;s seguro de Guardar el Comentario?', 'ALERTA', function(r){
			if( confirm('¿Estás seguro de Eliminar La imagen del Slide?') ){
								
				//var img_id = $(this).data('id');
				var p_id = '{{{ $proyecto->id }}}';
					
				$.ajax({
						type: "post",
						url: '{{ url("admin/proyectos/ajax-delete-slide") }}',
						data: 's_id='+s_id+'&p_id='+p_id,
						dataType: 'json',
						beforeSend: function(){
							//
							/*if (typeof spinnerDelslide != "undefined")
							{
								spinnerDelslide.stop();
							}
							spinnerDelslide = new Spinner(optsSpinDel).spin(document.getElementById("loader_del_s"+s_id));*/
							
						},
						error: function(datos){
							//spinnerDelslide.stop();
							alert('Error, no se eliminó.');
							//return false;
						},
						success:function(datos){
							//spinnerDelslide.stop();
							
							if( datos.success ){
								
								$('#slide_'+datos.s_id).fadeOut('slow', function() {
									$(this).remove();
								});
								
							}					
							
						}
					});// fin de ajax
				
			}
			else{
					return false;
				}
			//});
		}
		
		function delImg(img_id){
			//jConfirm('&iquest;Est&aacute;s seguro de Guardar el Comentario?', 'ALERTA', function(r){
			if( confirm('¿Estás seguro de Eliminar La imagen?') ){
				
				
				//var img_id = $(this).data('id');
				var p_id = '{{{ $proyecto->id }}}';
					
				$.ajax({
						type: "post",
						url: '{{ url("admin/proyectos/ajax-delete-img") }}',
						data: 'img_id='+img_id+'&p_id='+p_id,
						dataType: 'json',
						beforeSend: function(){
							//
							if (typeof spinnerDel != "undefined")
							{
								spinnerDel.stop();
							}
							spinnerDel = new Spinner(optsSpinDel).spin(document.getElementById("loader_del"+img_id));
							
						},
						error: function(datos){
							spinnerDel.stop();
							alert('Error, no se eliminó.');
							//return false;
						},
						success:function(datos){
							spinnerDel.stop();
							
							if( datos.success ){
								
								$('#img_'+datos.img_id).fadeOut('slow', function() {
									$(this).remove();
								});
								
							}					
							
						}
					});// fin de ajax
				
			}
			else{
					return false;
				}
			//});
		}
		
		
		//Orden Elementos
		function orderingSlide(s_id){
			
			//loader_order_s
			
			if( $('#frm_elementos').validationEngine('validate') ){
					
				$("#frm_elementos").formajax({
					type: "post",
					url:"{{ url('admin/proyectos/ajax-ordering-slide') }}",
					dataType: "json",
					beforeSend: function(){
						//
						if (typeof spinnerO != "undefined")
						{
							spinnerO.stop();
						}
						spinnerO = new Spinner(optsSpin).spin(document.getElementById("loader_order_s"+s_id));
						
						return true;
						
					},
					error: function(xhr, error){
						//console.debug(xhr); console.debug(error);
						spinnerO.stop();
						alert('Error: '+error);
					 },
					success:function(datos){
						
						spinnerO.stop();
						
						if( datos.success ){
							$('#ajax_slide').html(datos.salida);
							//$('#slide_'+datos.s_id).fadeIn('slow');
							$('.slide-cont').fadeIn('slow');
						}
						
						
					}
				}); // formajax
				
			}
			else{
				return false;
			}
			
		}
        </script>
    @endif
@stop