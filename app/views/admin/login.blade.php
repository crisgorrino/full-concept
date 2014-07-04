@extends('layouts.master')

@section('title')@parent- Login de Adminisraci&oacute;n @stop

@section('header')
@stop

@section('content-mast')
    <!--login-->
    
    <form class="admin-login center" name="frm_admin_login" id="frm_admin_login" method="">
        <h1 class="admin-h1">Administraci&oacute;n</h1>
        <div class="desvanecer" id="ajax_msg"></div>
        <img src="{{ asset('img/full-concept-logo.png') }}" alt="FULL CONCEPT" class="inline"> 
        <input type="text" placeholder="Usuario" name="email" id="email" value="{{ Input::old('email') }}" class="inline validate[required]">
        <input type="password" placeholder="Contrase&ntilde;a" name="password" id="password" class="inline">
        <input type="submit" value="Ingresar" class="inline">
        {{ Form::token() }}
    </form>
    
    <div id="div_frm_enviar_email" style="color:#000; display:none;">
        <div style="width:450px; background-color:#FFF; padding:15px;">
            <div id="resultado_mensaje" style="width:100%;"></div>
            <br />
            <form onsubmit="enviarMailPassword(); return false;" name="enviar_email" action="">
            <p style="color:#000; text-align:center; margin:0; font-size:18px;">Ingresa tu email &oacute; nombre de usuario para reenviarte la contrase&ntilde;a</p>
            <p>&nbsp;</p>
            <p style="color:#000;">E-mail &oacute; Nombre de Usuario</p>
            <p>
            <input size="30" type="text" id="email_destino" name="email_destino" value="" />
            
            <input type="button" value="Enviar" onclick="enviarMailPassword(); return false;" />
            </p>
            <p>&nbsp;</p>
    
            </form>
        </div>
    </div>
    <!--login-->
@stop

@section('footer')
@stop

@section('scripts')
    @parent
    <!--jQuery Validation Engine -->
    <link rel="stylesheet" href="{{ asset('js/jQuery-Validation-Engine/css/validationEngine.jquery.css') }}" type="text/css"/>
    <script src="{{ asset('js/jQuery-Validation-Engine/js/jquery-1.8.2.min.js') }}" type="text/javascript">
    </script>
    <script src="{{ asset('js/jQuery-Validation-Engine/js/languages/jquery.validationEngine-es.js') }}" type="text/javascript" charset="utf-8">
    </script>
    <script src="{{ asset('js/jQuery-Validation-Engine/js/jquery.validationEngine.js') }}" type="text/javascript" charset="utf-8">
    </script>
    <script type="text/javascript">
        $(document).ready(function(){
            // binds form submission and fields to the validation engine
            $("#frm_admin_login").validationEngine({
				autoPositionUpdate:true,
				focusFirstField : true ,
				validateNonVisibleFields:false,
				updatePromptsPosition: true,
				scroll:false,
				autoHidePrompt: true,
				autoHideDelay: 5000,
				fadeDuration: 400,
				/*onFieldSuccess: function(){
					//$("#form_details").validationEngine('updatePromptsPosition');
				}*/
                
            });
        });
    </script>
    <!--jQuery Validation Engine -->
    
    <!-- spinner -->
    <script src="{{ asset('js/spin.min.js') }}"></script>
    <style type="text/css">
		.spinner{
			margin:20px 0;
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
    <!-- spinner -->
    
    <!-- Ajax Login -->
    <script type="text/javascript">
	$(document).ready(function() {
        $('#frm_admin_login').submit(function(e){
			e.preventDefault();
			
			if( $("#frm_admin_login").validationEngine('validate') ){
				$.ajax({
					type:		'post',
					cache:		false,
					dataType:	"json",
					url:		$(this).attr('action'),
					data:		$(this).serialize(),
					beforeSend: function(){
						//
						$('#ajax_msg').html('').show();
						if (typeof spinner != "undefined"){
							spinner.stop();
						}
						spinner = new Spinner(optsSpin).spin(document.getElementById("ajax_msg"));
						
					},
					
					error: function(){
						spinner.stop();
						alert('Error: No se ejecutó login');
					},
					
					success: function(data){
						spinner.stop();
						msg='<ul class="msg">';
						for( datos in data.msg ){
							msg+='<li>'+data.msg[datos]+'</li>'
						}
						msg+='<ul>';
						//Limpiar formulario
						//$(this).reset();
						
						//Mostrar mensaje
						$('#ajax_msg').show().html(msg);
						
						//redirigir
						if( data.success == true ){ 
							//alert('redirigir');
							window.location='{{ url("admin") }}';
						}
						
						desvanecer();
						
					},
					
				});
			}
		});
    });		
	</script>
    <!-- Ajax Login -->
    
    <script type="text/javascript" src="{{ asset('js/jquery.lightbox_me.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
        $('#lightbox_enviar_email').click(function(e) {
         $('#div_frm_enviar_email').lightbox_me({
        centered: true, 
        onLoad: function() { 
            $('#div_frm_enviar_email').find('input:first').focus()
            }
        });
        e.preventDefault();
        });
        });
    </script>

    <!-- desvanecer div -->
    <script type="text/javascript">    
    $(document).ready(function() {
    setTimeout(function() {
        $(".desvanecer").fadeOut(1500);
    },8000);
    });
    
    function desvanecer(){
        setTimeout(function() {
                $(".desvanecer").fadeOut(1500);
            },8000);
    }    
    </script>

    <script type="text/javascript">
        function focusUsuario(){
            $('#email').focus();
        }
        setTimeout("focusUsuario();",700);
    </script>
@stop