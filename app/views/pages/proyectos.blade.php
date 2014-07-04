@extends('layouts.master')

@section('content-mast')
    <!--projectos-->
    <section class="proyectos-cont cf">
    @if( count( $mosaico ) > 0 )
    	@foreach( $mosaico as $m )
        	@if( is_file($m->path.$m->archivo) )
                <!--proy-->
                <div class="proy-home">
                    <a href="{{ url('proyecto-detalle/'.$m->proyecto_id) }}">
                        <img src="{{ url($m->path.$m->archivo) }}" alt="{{{ $m->proyecto()->titulo }}}" class="proy-img-home">
                        <div class="proy-home-hover">
                            <img src="{{ url($m->path.$m->logo) }}">
                        </div>
                    </a>
                </div>
                <!--proy-->
            @endif
        @endforeach
    @else
    	<h2 class="no-result">No se encontraron Resultados.</h2>
    @endif
    </section>
    <!--projectos-->
@stop

@section('scripts')
	@parent
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
    </script>
    
@stop