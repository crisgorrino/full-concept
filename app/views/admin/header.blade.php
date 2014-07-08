@section('header')
    <nav class="main-nav">
        <ul>
            <li><a href="{{ url('admin/proyectos') }}" class="">Proyectos</a></li>
            <li><a href="{{ url('admin/comentarios') }}" class="">Comentarios</a></li>
            <li><a href="{{ url('admin/logout') }}">Cerrar Sesi&oacute;n</a></li>
        </ul>
    </nav>	
@stop