@if( isset($comentarios) )
    @foreach( $comentarios as $com)
        <div class="comentario cf">
            <p>{{{ $com->comentario }}}</p>
            
            <span class="left by">{{{ $com->nombre }}}</span> 
            
            <span class="right stars-cont-small">
            	           	
            </span>
            
            <span class="right stars-cont-small cf">
            	@if( $com->stars > 0 )
                	@for($i=0; $i < $com->stars; $i++)
                    	<i class="icon-star3"></i>
                    @endfor
                @endif
            </span>            
        </div>
    @endforeach
@endif