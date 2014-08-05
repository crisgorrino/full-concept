<div class="items">
	<form id="adminForm" name="adminForm" method="post">
        <div role="pagination">
            <div class="pag">
                {{ $proyectos->appends(Input::except('page', 'enviar', 'reset'))->links() }}
            </div>
        </div>
        <div style="clear:both;"></div>
        <?php /*?><p>&nbsp;</p>
        <h3>Total de Registros: <?php echo $registros; ?></h3><?php */?>
        <table class="adminlist tablesorter tabla_historial" id="admintbl" cellpadding="5" width="100%">
            <thead>
                <tr class="titulo" id="chkrowAll">
                    <th width="1%" class="title">#&nbsp;&nbsp;&nbsp;&nbsp;</th>
                    <th width="30%" class="title">Logo</th>
                    <th width="1%" class="title">Editar</th>
                    <th width="20%" class="title"><strong>Fecha:</strong></th>
                    <th width="40%" class="title"><strong>Proyecto:</strong></th>
                    <th width="8%" class="title">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i=0;
                $k = 0;
                ?>
                @if( $proyectos )
                    @foreach($proyectos as $p)
                        <?php
						$i++;
                        
                        ?>
                        <tr id="fila{{ $p->id }}" class="row{{ $k }}" style="vertical-align:top;">
                            <td nowrap="nowrap">{{ $i+( ($proyectos->getCurrentPage()-1)*$proyectos->getPerPage() ) }}</td>
                            <td align="center">
                            	@if( is_file($p->path.$p->logo) )
	                            	<img src="{{ url($p->path.$p->logo) }}" alt="{{ $p->titulo }}" style="background-color:#CCC;" />
                                @else
                                	<img src="{{ asset('img/logo_nodisponible.jpg') }}" alt="{{ $p->titulo }}" />
                                @endif
                            </td>
                            <td align="center">
                            	<a href="{{ url('admin/proyectos/edit/'.$p->id) }}"><img src="{{ asset('img/icons_admin/edit.png') }}" width="22" height="22" alt="Editar" /></a>
                            </td>
                            <td align="center">
                               {{ $p->created_at }}
                            </td>
                            <td align="center">{{ $p->titulo }}
                            </td>
                            <td align="center"><a href="#" data-p_id="{{ $p->id }}" data-titulo="{{ $p->titulo}}" class="eliminar">Eliminar</a></td>
                        </tr>
                        <?php $k = 1 - $k; ?>
                    @endforeach
                @else
                    <h2><strong>No se encontraron Registros</strong></h2>
                @endif
            </tbody>
        </table>
        <div role="pagination">
            <div class="pag">
                {{ $proyectos->appends(Input::except('page', 'enviar'))->links() }}
            </div>
        </div>
        <div style="clear:both;"></div>
        <input type="hidden" name="id" id="orden_id" value="" />
        <input type="hidden" name="url_return" id="url_return" value="{{ url('admin/proyectos?page='.$proyectos->getCurrentPage() ) }}" />
    </form>
</div>