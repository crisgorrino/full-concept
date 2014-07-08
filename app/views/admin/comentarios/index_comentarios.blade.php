@extends('layouts.master')

@section('title')@parent- Proyectos @stop

@section('style')
	@parent
    <?php /*?><link rel="stylesheet" href="{{ asset('css/estilosAngular.css') }}" type="text/css" media="all"><?php */?>
    <!-- CSS Administrador -->
    <link rel="stylesheet" href="{{ asset('css/admin/general.css') }}" type="text/css" media="screen" charset="utf-8"/>
@stop

@include('admin.header')

@section('content-mast')   
    <!--MAST-->
    <section  class="cont-shadow">
        <section class="proyectos-det-cont cf ">
        	<h2 class="yellow">Comentarios</h2>
            <form action="" method="post" name="buscador" id="buscador" onsubmit="$.trim($('#search').val());">
                <?php /*?><table class="admintable" border="0" width="75%" style="border:solid 1px #CCC;" align="center">
                    
                    <tr>
                        @if( Input::has('fecha_ini') and Input::get('fecha_ini') )
                        <th style="background-color:#FC9; width:5%">
                        @else
                        <th style="width:5%;">
                        @endif
                        Desde:</th>
                        <td nowrap="nowrap">
                            <input type="text" name="fecha_ini" size="15" class="inputbox" id="f_ini" value="{{ Input::get('fecha_ini') }}" readonly="readonly">
                            <input type="button" name="lanzador_inicio" id="lanzador_inicio" value="Calendario" class="btn" />
                        </td>
                        <td>&nbsp;</td>
                        @if( Input::has('fecha_fin') and Input::get('fecha_fin') )
                        <th style="background-color:#FC9; width:5%">
                        @else
                        <th style="width:5%;">
                        @endif
                        Hasta:</th>
                        <td nowrap="nowrap">
                            <input type="text" name="fecha_fin" size="15" class="inputbox" id="f_fin" value="{{ Input::get('fecha_fin') }}" readonly="readonly">
                            <input type="button" name="lanzador_fin" id="lanzador_fin" value="Calendario" class="btn" />
                        </td>
                        <td>&nbsp;</td>
                        <td>
                            <p><input type="button" onclick="$('#buscador').attr('action', ''); $('#buscador').attr('target', ''); this.form.submit();" class="btn" value="Filtrar por Fecha" />&nbsp;
                            <input type="button" onclick="$('#buscador').attr('action', ''); $('#buscador').attr('target', ''); this.form.f_ini.value=''; this.form.f_fin.value=''; this.form.submit();" class="btn" value="Restablecer" /></p>
                        </td>
                        <!--<td>
                            <input type="button" onclick="$('#buscador').attr('action', 'sys/export_excel/models/Exportar_SQL.php'); $('#buscador').attr('target', '_blank'); $('#buscador').submit();" class="btn" value="Generar EXCEL" />
                            <input type="hidden" name="nivel_id" value="" >
                        </td>-->
                    </tr>
                </table><?php */?>
                <br>
                <table class="admintable" align="center" border="0" width="75%" cellspacing="0" cellpadding="0">
                <tbody> 
                    <tr>
                        <th width="1%" align="right" nowrap="nowrap" class="yellow">Buscar en:&nbsp;</th>
                        <td align="left">
                            <input type="text" name="search" id="search" value="{{ Input::get('search', '') }}" /><br>
                        </td>
                        <td width="660" align="left">&nbsp;
                        <input type="submit" value="Buscar / Filtrar" name="enviar" id="enviar" class="btn" onclick="$('#buscador').attr('action', ''); $('#buscador').attr('target', '');" />&nbsp;
                        <input type="button" id="reset" name="reset" value="Restablecer" onclick="$('#buscador').attr('action', ''); $('#buscador').attr('target', ''); $('#envio_id').val(''); $('#envio_metodo_id').val(''); $('#tipo_pago_id').val(''); $('#orden_status_id').val(''); $('#f_visto').val(''); $('#origen_id').val(''); <?php /*?>$('#pago_status_id').val('');<?php */?> $('#campo').val(''); $('#campo').val(''); $('#usuario_id').val('');   $('#search').val(''); this.form.submit();" class="btn" />
                        </td>
                    </tr>
                </tbody>
                </table>
            	<p>&nbsp;</p>
            </form>
            
            @include('admin.comentarios.index_ajax_comentarios_list')
            	
        </section>
    </section>
    <!--MAST-->
    
@stop

@section('scripts')
	@parent
    
    <!-- Para JAlerts -->
    <script src="{{ asset('js/jqueryAlerts/jquery-1.8.2.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/jqueryAlerts/jquery.ui.draggable.js') }}" type="text/javascript"></script>
    <!-- Core files -->
    <script src="{{ asset('js/jqueryAlerts/jquery.alerts.js') }}" type="text/javascript"></script>
    <link href="{{ asset('js/jqueryAlerts/jquery.alerts.css') }}" rel="stylesheet" type="text/css" media="screen" />
    <!-- Fin JAlerts -->
    
    <?php /*?><!--Hoja de estilos del CALENDARIO-->
    <link rel="stylesheet" type="text/css" media="all" href="{{ asset('js/jscalendar/calendar-blue2.css') }}" title="blue" />
    <link rel="stylesheet" type="text/css" media="all" href="{{ asset('js/jscalendar/calendar-system.css') }}" title="system" />
    
    <!-- librería principal del CALENDARIO --> 
    <script type="text/javascript" src="{{ asset('js/jscalendar/calendar.js') }}"></script>
    
    <!-- librería para cargar el lenguaje deseado --> 
    <script type="text/javascript" src="{{ asset('js/jscalendar/lang/calendar-es.js') }}"></script>
    
    <!-- librería que declara la función Calendar.setup, que ayuda a generar un calendario en unas pocas líneas de código --> 
    <script type="text/javascript" src="{{ asset('js/jscalendar/calendar-setup.js') }}"></script>
    
    <!-- Este codigo es un estilo para resaltar los dias especiales  -->
    <style type="text/css">
      .special { background-color: #000; color: #fff; }
    </style>
    <!-- fin jscalendar -->
	<script type="text/javascript">
		function comparar_fechas(fechaI, fechaF) {
			var date1 = new Date(fechaI);
			var date2 = new Date(fechaF);
			if (date2.getTime() < date1.getTime()) {
				return true;
			} else {
				return false;
			}
		}

		function dateChanged_ini(calendar) {
			
			//var time = calendar.date.getTime();
			//var date1  = new Date(time);
			var date1 = calendar.date;
			
			var date2 = new Date(document.getElementById("f_fin").value);
	
			//if (calendar.dateClicked) {
				
				
				//jAlert('ini:'+date1.print("%Y-%m-%d")+' fin:'+date2 );
	
				if (comparar_fechas(date1.print("%Y-%m-%d"), date2 )) {
					jAlert("La fecha DESDE no debe ser mayor que la fecha HASTA", "Mensaje!"
				  );
					document.getElementById("f_ini").value = "";
					//quitar selección
	
	
				} else {
	
					//if (dateFull) {
					//alert("fecha:" + dateFull);
					//alert("fecha:" + date.print("%d/%m/%Y %H:%M:%S"));
					document.getElementById("f_ini").value = date1.print("%Y-%m-%d");
	
	
					//}
				}
	
	
			//}
		}
		
		var inicio = Calendar.setup({
			inputField     :    "f_ini",    	// id del campo de texto 
			//flat         : "calendar-container-ini", // ID of the parent element
			//flatCallback : dateChanged_ini,
			
			ifFormat     :     "%Y-%m-%d",    // formato de la fecha que se escriba en el campo de texto 
			showsTime      :    false,            // will display a time selector
			weekNumbers	   :	true,			//mostrar/nomostrar numero de la semana
			//displayArea    :    "laFechaI",
			//daFormat       :    "%A, %d de %B de %Y %I:%M:%S %p",
			//button     :    "lanzador_inicio",    // el id del botón que lanzará el calendario 
			align: 'Tl',
			//range:[2014,2060],
			onUpdate       :    dateChanged_ini,
			//onUpdate       :    catcalc,	//llama a la función catcalc que cuando presionan un campo de texto se muestra el calendario
			singleClick    :    true,           // double-click mode
			//position	   :[520,900],			//posicionar la fecha		
			electric	   :true				//indica que se pondrá la fecha solo si se da click
			//dateStatusFunc :    function (date) { // disable weekend days (Saturdays == 6 and Subdays == 0)
			//				  return (date.getDay() == 6 || date.getDay() == 0) ? true : false;                           			}
		});

		Calendar.setup({
			inputField     :    "f_ini",    	// id del campo de texto 
			//flat         : "calendar-container-ini", // ID of the parent element
			//flatCallback : dateChanged_ini,
			
			ifFormat     :     "%Y-%m-%d",    // formato de la fecha que se escriba en el campo de texto 
			showsTime      :    false,            // will display a time selector
			weekNumbers	   :	true,			//mostrar/nomostrar numero de la semana
			//displayArea    :    "laFechaI",
			//daFormat       :    "%A, %d de %B de %Y %I:%M:%S %p",
			button     :    "lanzador_inicio",    // el id del botón que lanzará el calendario 
			align: 'Tl',
			//range:[2014,2060],
			onUpdate       :    dateChanged_ini,
			//onUpdate       :    catcalc,	//llama a la función catcalc que cuando presionan un campo de texto se muestra el calendario
			singleClick    :    true,           // double-click mode
			//position	   :[520,900],			//posicionar la fecha		
			electric	   :true				//indica que se pondrá la fecha solo si se da click
			//dateStatusFunc :    function (date) { // disable weekend days (Saturdays == 6 and Subdays == 0)
			//				  return (date.getDay() == 6 || date.getDay() == 0) ? true : false;                           			}
		});
	
	</script>
    
    <script type="text/javascript"> 
		function dateChanged_fin(calendar) {
			
			//var time = calendar.date.getTime();
			//var date1 = new Date(time);
			var date1 = calendar.date;
			
			var date2 = new Date(document.getElementById("f_ini").value);
	
			//if (calendar.dateClicked) {
								
				//jAlert('ini:'+date2+' fin:'+date1.print("%Y-%m-%d") );
	
				if (comparar_fechas( date2, date1.print("%Y-%m-%d"))) {
					jAlert("La fecha DESDE no debe ser mayor que la fecha HASTA", "Mensaje!"
				  );
					document.getElementById("f_fin").value = "";
					
				} else {
	
					//if (dateFull) {
	
					document.getElementById("f_fin").value = date1.print("%Y-%m-%d");
					//}
				}
	
	
			//}
		}

		var fin = Calendar.setup({
			inputField     :    "f_fin",    	// id del campo de texto 
		   //flat         : "calendar-container-fin", // ID of the parent element
		   //flatCallback : dateChanged_fin,
		   
		   ifFormat     :     "%Y-%m-%d",    // formato de la fecha que se escriba en el campo de texto 
		   showsTime      :    false,            // will display a time selector
		   weekNumbers	   :	true,			//mostrar/nomostrar numero de la semana
		   //displayArea    :    "laFechaI",
		   //daFormat       :    "%A, %d de %B de %Y %I:%M:%S %p",
		   //button     :    "lanzador_inicio",    // el id del botón que lanzará el calendario 
		   align: 'Tl',
		   //range:[2014,2060],
		   onUpdate       :    dateChanged_fin,
		   //onUpdate       :    catcalc,	//llama a la función catcalc que cuando presionan un campo de texto se muestra el calendario
		   singleClick    :    true,           // double-click mode
		   //position	   :[520,900],			//posicionar la fecha		
		   electric	   :true				//indica que se pondrá la fecha solo si se da click
		   //dateStatusFunc :    function (date) { // disable weekend days (Saturdays == 6 and Subdays == 0)
		   //				 return (date.getDay() == 6 || date.getDay() == 0) ? true : false;                           			}
		});

		Calendar.setup({
			inputField     :    "f_fin",    	// id del campo de texto 
		   //flat         : "calendar-container-fin", // ID of the parent element
		   //flatCallback : dateChanged_fin,
		   
		   ifFormat     :     "%Y-%m-%d",    // formato de la fecha que se escriba en el campo de texto 
		   showsTime      :    false,            // will display a time selector
		   weekNumbers	   :	true,			//mostrar/nomostrar numero de la semana
		   //displayArea    :    "laFechaI",
		   //daFormat       :    "%A, %d de %B de %Y %I:%M:%S %p",
		   button     :    "lanzador_fin",    // el id del botón que lanzará el calendario 
		   align: 'Tl',
		   //range:[2014,2060],
		   onUpdate       :    dateChanged_fin,
		   //onUpdate       :    catcalc,	//llama a la función catcalc que cuando presionan un campo de texto se muestra el calendario
		   singleClick    :    true,           // double-click mode
		   //position	   :[520,900],			//posicionar la fecha		
		   electric	   :true				//indica que se pondrá la fecha solo si se da click
		   //dateStatusFunc :    function (date) { // disable weekend days (Saturdays == 6 and Subdays == 0)
		   //				 return (date.getDay() == 6 || date.getDay() == 0) ? true : false;                           			}
		});

	</script><?php */?>
    
    <!-- Para Table Sorter -->
    <link href="{{ asset('js/jquery.tablesorter/themes/blue/style.css') }}" rel="stylesheet" type="text/css" media="screen" />
    <?php /*?><script type="text/javascript" src="{{ asset('js/jquery.tablesorter/jquery-1.8.2.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.tablesorter/jquery.tablesorter.min.js') }}"></script>
    <script type="text/javascript">
    $(document).ready(function($){ 
	
		$(".avoid-sort").data("sorter", false);
		
            $(".tablesorter").tablesorter({
                headers: { 
                    // assign the secound column (we start counting zero) 
                    1: { 
                        sorter: false 
                    }, 
                    3: { 
                        sorter: false 
                    },
					4: { 
                        sorter: false 
                    },
					
                }
            }); 
        } 
    ); 
    </script><?php */?> 
    <!-- Fin Table Sorter -->
        
    <!-- Spinner -->
    <script src="{{ asset('js/spin.min.js') }}"></script>
    <script type="text/javascript" language="javascript">
    var optsSpin = {
      lines: 13, // The number of lines to draw
      length: 4, // The length of each line
      width: 3, // The line thickness
      radius: 7, // The radius of the inner circle
      corners: 1, // Corner roundness (0..1)
      rotate: 0, // The rotation offset
      direction: 1, // 1: clockwise, -1: counterclockwise
      color: '#fff', // #rgb or #rrggbb
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
    <!-- Spinner -->
    
    <!-- Lightbox agregar comentarios -->
    <script type="text/javascript" src="{{ asset('js/jquery.lightbox_me.js') }}"></script>
    
    <!-- Lightbox agregar comentarios -->
    
    <script type="text/javascript">
	$(document).ready(function(e) {
		
		//Actualizar Status
		$('.save_status').click(function(e){
			e.preventDefault();
			
			var com_id = $(this).data('com_id');
			var nombre = $(this).data('nombre');
			
			var status = $('#status_id'+com_id).val();
			
			jConfirm('&iquest;Est&aacute;s seguro de Modificar El status al comentario <strong>'+nombre+'</strong>?', 'ALERTA', function(r){
				if(r){
					
					
					var datos="com_id="+com_id+"&nombre="+nombre+"&status="+status;
					
					$.ajax({
						type: "POST",
						url: '{{ url("admin/comentarios/ajax-status") }}',
						data:datos,
						dataType: 'json',
						beforeSend: function(){
							//							
							/*if (typeof spinner != "undefined"){
								spinner.stop();
							}
							spinner = new Spinner(optsSpin).spin(document.getElementById("resultado_mensaje"));*/
							
						},
						error: function(datos){
							
							/*spinner.stop();
							alert('Error al guardar el Comentario');*/
							//return false;
						},
						success:function(datos){
							//spinner.stop();
							if( datos.success ){
								
								// Animation complete.
								$('#ajax_totalPendientes').html(function(){
									$(this).css({opacity: 0, visibility: "visible"}).animate({opacity: 1},'slow');
									return datos.totalPendientes;
								});							
								
							}
							
							jAlert(datos.msg, 'MENSAJE');
							
						}
					});// fin de ajax 
					
					
					
					
				}
				else{
					return false;
				}		
			});
			
		});
		
		
		// Eliminar Registro
        $('.eliminar').click(function(e){
			e.preventDefault();
			
			var com_id = $(this).data('com_id');
			var nombre = $(this).data('nombre');
			
			jConfirm('&iquest;Est&aacute;s seguro de Eliminar el Comentario <strong>'+nombre+'</strong>?', 'ALERTA', function(r){
				if(r){
					
					
					var datos="com_id="+com_id+"&nombre="+nombre;
					
					$.ajax({
						type: "POST",
						url: '{{ url("admin/comentarios/ajax-delete") }}',
						data:datos,
						dataType: 'json',
						beforeSend: function(){
							//							
							/*if (typeof spinner != "undefined"){
								spinner.stop();
							}
							spinner = new Spinner(optsSpin).spin(document.getElementById("resultado_mensaje"));*/
							
						},
						error: function(datos){
							
							/*spinner.stop();
							alert('Error al guardar el Comentario');*/
							//return false;
						},
						success:function(datos){
							//spinner.stop();
							if( datos.success ){
								
								$('#fila'+datos.com_id).fadeOut("slow", function() {
									$('#fila'+datos.com_id).remove();
								});
								
								
							}
							
							jAlert(datos.msg, 'MENSAJE');
							
						}
					});// fin de ajax 
					
					
					
					
				}
				else{
					return false;
				}		
			});
			
		});
    });	
	</script>
    
    <?php /*?><script type="text/javascript">
 	var entrar=true;
	$(window).on('hashchange', function() {
		
		if( entrar==true )
		{
			//if (window.location.hash) 
			{
				var page = window.location.hash.replace('#', '');
				//alert(page);
				if (page == Number.NaN || page <= 0) {
					entrar=true;
					return false;
				} else {
					getItems(page);
				}
			}
		}
		entrar=true;
		
	});
	 
	$(document).ready(function() {
		$(document).on('click', '.pagination a', function (e) {
			entrar=false;
			getItems($(this).attr('href').split('page=')[1]);
			e.preventDefault();
		});
				
		$('#enviar').click(function(){
			getItems(1);
		});
		
		$('#reset').click(function(){
			$('#search').val(''); 
			getItems(1);
		});
		
		$('#por_fecha').click(function(){
			getItems(1);
		});
		
		$('#reset_por_fecha').click(function(){
			$('#f_ini').val('');
			$('#f_fin').val('');
			getItems(1);
		});
		
	});
	 
	function getItems(page) {
		
		var s=$('#search').val();
		var f_ini=$('#f_ini').val();
		var f_fin=$('#f_fin').val();
		
		$.ajax({
			url : '?page=' + page,
			data: "search="+s+"&fecha_ini="+f_ini+"&fecha_fin="+f_fin,
			dataType: 'json',
		}).done(function (data) {
			
			$('.items').css({opacity: 1.0, visibility: "visible"}).animate({opacity: 0}, 400, function() {
				//spinner.stop();
				// Animation complete.
				$(this).html(function(){
					$(this).css({opacity: 0, visibility: "visible"}).animate({opacity: 1},400);
					return data;
				});
			});
			
			//$('.items').html(data);
			entrar=false;
			location.hash = page;
		}).fail(function () {
			jAlert('Elementos no cargados.', 'ERROR');
		});
	}
	</script> <?php */?>   
    
@stop