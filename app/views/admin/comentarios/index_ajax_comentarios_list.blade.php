<div class="items">
	<form id="adminForm" name="adminForm" method="post">
        <div role="pagination">
            <div class="pag">
                {{ $cometarios->appends(Input::except('page', 'enviar', 'reset'))->links() }}
            </div>
        </div>
        <div style="clear:both;"></div>
        <?php /*?><p>&nbsp;</p>
        <h3>Total de Registros: <?php echo $registros; ?></h3><?php */?>
        @if( $totalPendientes > 0 )
        <h3>Hay {{ $totalPendientes }} Registros Pendientes</h3>
        @endif
        <table class="adminlist tablesorter tabla_historial" id="admintbl" cellpadding="5" width="100%">
            <thead>
                <tr class="titulo" id="chkrowAll">
                    <th width="1%" class="title">#&nbsp;&nbsp;&nbsp;&nbsp;</th>
                    <th width="1%" class="title">Editar</th>
                    <th width="50%" class="title">Comentario</th>
                    <th width="20%" class="title"><strong>Nombre:</strong></th>
                    <th width="25%" class="title"><strong>Proyecto:</strong></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i=0;
                $k = 0;
                ?>
                @if( $cometarios )
                    @foreach($cometarios as $c)
                        <?php
						$i++;
                        ?>
                        <tr id="fila{{ $c->id }}" class="row{{ $k }}" style="vertical-align:top;">
                            <td nowrap="nowrap">{{ $i+( ($cometarios->getCurrentPage()-1)*$cometarios->getPerPage() ) }}</td>
                            <td align="center">
                            	<a href="{{ url('admin/proyectos/edit/'.$c->id) }}"><img src="{{ asset('img/icons_admin/save.jpg') }}" width="22" height="22" alt="Guardar" /></a>
                            </td>
                            <td align="left">{{ $c->comentario }}</td>
                            <td align="left">{{ $c->nombre }}</td>
                            <td align="center">{{ $c->proyecto()->titulo }}</td>
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
                {{ $cometarios->appends(Input::except('page', 'enviar'))->links() }}
            </div>
        </div>
        <div style="clear:both;"></div>
        <input type="hidden" name="id" id="orden_id" value="" />
        <input type="hidden" name="url_return" id="url_return" value="{{ url('admin/cometarios?page='.$cometarios->getCurrentPage() ) }}" />
    </form>
</div>