@extends('layouts.master')

@section('content-mast')
    <!--detalle-->
    <section  class="cont-shadow">
        <section class="proyectos-det-cont cf ">
            <div class="det-image-container cycle-slideshow">
            	@if( count($proyecto->imagenes_slideshow()) > 0 )
                	@foreach( $proyecto->imagenes_slideshow() as $s )
                    	@if( is_file($s->path.$s->archivo) )
		                	<img src="{{ asset($s->path.$s->archivo) }}" alt="{{{ $proyecto->titulo }}}">
                        @endif
                    @endforeach
                @else
                	<img src="{{ asset('img/detail_nodisponible.jpg') }}" alt="{{{ $proyecto->titulo }}}">
                @endif
            </div>
            <div class="det-info">
                <h2>{{{ $proyecto->titulo }}}</h2>
                <p>{{{ $proyecto->concepto }}}</p>
                @if( is_file($proyecto->path.$proyecto->logo) )
                <img src="{{ asset($proyecto->path.$proyecto->logo) }}" alt="torre-eMe" class="proyecto-logo">
                @endif
                <p>{{{ $proyecto->descripcion }}}</p>
                <a href="mailto:?subject={{{ $proyecto->titulo }}}&body={{ url('proyecto-detalle/'.$proyecto->id) }}" class="compartir-detalle"><span>COMPARTIR</span><img src="{{ asset('img/compartir-ico-yellow.png') }}" alt="compartir"></a>
            </div>
        </section>
    </section>
    <!--detalle-->
	
    <!--detalle comentarios y otros proyectos-->
    <section class="comentarios-cont cf">
        <div class="comentarios cf">
        	<form id="frm_com" name="frm_com" method="post" action="{{ url('add-com') }}">
            	<input type="hidden" id="id" name="id" value="{{ $proyecto->id }}" readonly="readonly" />
                <label class="left">COMENTARIOS</label>
                <input type="text" style="width:0px; height:0px; color:#404041; background-color:#404041; border:0;" id="num_stars" name="num_stars" value="" class="validate[required, min[1]]" readonly="readonly" data-prompt-position="topLeft:400,0" data-errormessage="&iquest;Cuantas estrellas le asignas?" />
                <span class="right stars-cont">
                    <i class="icon-star" data-id="1"></i><i class="icon-star" data-id="2"></i><i class="icon-star" data-id="3"></i><i class="icon-star" data-id="4"></i><i class="icon-star" data-id="5"></i>
                </span>
                
                <textarea class="comentarios-area validate[required]" id="comentario" name="comentario" data-prompt-position="topLeft:100,0" data-errormessage="Por favor ingresa tu comentario"></textarea>    
                
                <label class="left">NOMBRE</label>
				<input type="text" placeholder="" id="nombre" name="nombre" class="handle-input validate[required]" data-prompt-position="bottomLeft:100,0" data-errormessage="Por favor ingresa tu nombre">
		
				<input type="submit" value="Comentar" class="comentar">
                    
            </form>
            <div id="loader"></div>
            
            <div id="ajax_comentarios">
                @include('pages.ajax_comentarios')
            </div>
            
        </div>
        <div class="otros-proy">
        	@if( count($otrosProy) > 0 )
            	<h2>OTROS PROYECTOS</h2>
            	@foreach($otrosProy as $op )
                	@if( is_file($op->path.$op->logo) )
			            <a href="{{ url('proyecto-detalle/'.$op->id) }}"><img src="{{ asset($op->path.$op->logo) }}" alt="{{{ $op->titulo }}}"></a>
                    @endif
            	@endforeach
            @endif
            <a href="{{ url('') }}" class="go-home"><img src="{{ asset('img/full-concept-logo.png') }}" alt="FULL CONCEPT"></a>
        </div>
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
            $("#frm_com").validationEngine({ 
				validateNonVisibleFields: true,
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
      left: 'auto' // Left position relative to parent in px
    };
    </script>
    
    <!-- scroll infinito -->
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
    <!-- FIN scroll infinito -->
    
    <script src="{{ asset('js/jquery.cycle2.js') }}"></script>
    
    <script type="text/javascript">
		$('.icon-star').click(function(e){
			e.preventDefault();
			
			
			$('.icon-star3').addClass('icon-star');
			$('.icon-star3').removeClass('icon-star3');
			
			//Revisar que tenga texto el textarea
			//if( $("#frm_com").validationEngine('validate') )
			{
				
				var data_id = $(this).data('id');
				//alert(data_id);
				$('#num_stars').val(data_id);
				//alert('val: '+data_id+' #num_stars: '+$('#num_stars').val());
				
				$("#frm_com").validationEngine('validate')
				
				$('.icon-star').each(function(index, element) {
					if( $(this).data('id') <= data_id ){
						$(this).addClass('icon-star3');
						$(this).removeClass('icon-star');
						
					}
				});
				
				//Llamada ajax para agregar comentario
				
			}
			
			$('#frm_com').submit(function(e){
				e.preventDefault();
				
				if( $("#frm_com").validationEngine('validate') )
				{
					addComentario();
				}
				
				return false;
				
			});
			
		});
		
		
		function addComentario(){
			
			var datos="id={{ $proyecto->id }}";
				
			$.ajax({
				type: "post",
				url: $('#frm_com').attr('action'),
				data:$('#frm_com').serialize(),
				dataType: 'json',
				beforeSend: function(){
					//
					if (typeof spinner != "undefined")
					{
						//spinner.stop();
					}
					spinner = new Spinner(optsSpin).spin(document.getElementById("loader"));
					
				},
				error: function(datos){
					spinner.stop();
					alert('Error al guardar el Comentario, por favor intenta nuevamente.');
					//return false;
				},
				success:function(datos){
					spinner.stop();
					
					$('#num_stars').val('');
					
					$('.icon-star3').addClass('icon-star');
					$('.icon-star3').removeClass('icon-star3');
					
					$('#comentario').val('');
					$('#nombre').val('');
					
					if( datos.success ){
						$('#ajax_comentarios').prepend(datos.com);
						$('#com_'+datos.id).slideDown('slow');
					}					
					
				}
			});// fin de ajax 
			
			
		}
	</script>
@stop