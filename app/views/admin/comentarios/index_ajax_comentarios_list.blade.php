<div class="items">
	<form id="adminForm" name="adminForm" method="post">
        <?php /*?><div role="pagination">
            <div class="pag">
                {{ $cometarios->appends(Input::except('page', 'enviar', 'reset'))->links() }}
            </div>
        </div>
        <div style="clear:both;"></div><?php */?>
        <?php /*?><p>&nbsp;</p>
        <h3>Total de Registros: <?php echo $registros; ?></h3><?php */?>
        
        <h3>Hay <span id="ajax_totalPendientes">{{ $totalPendientes or '0' }}</span> Registro(s) Pendiente(s)</h3>
        <table class="adminlist tablesorter tabla_historial" id="admintbl" cellpadding="5" width="100%">
            <thead>
                <tr class="titulo" id="chkrowAll">
                    <th width="1%" class="title">#&nbsp;&nbsp;&nbsp;&nbsp;</th>
                    <th width="10%" class="title">Status</th>
                    <th width="41%" class="title">Comentario</th>
                    <th width="20%" class="title"><strong>Nombre:</strong></th>
                    <th width="25%" class="title"><strong>Proyecto:</strong></th>
                    <th width="25%" class="title"><strong>Eliminar:</strong></th>
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
                            <td align="center" nowrap="nowrap">
                            	<a href="#" data-com_id="{{ $c->id }}" data-nombre="{{ $c->nombre }}" class="save_status"><img src="{{ asset('img/icons_admin/save.png') }}" width="22" height="22" alt="Guardar" /></a>
                                <select name="status_id['{{ $c->id }}']" id="status_id{{ $c->id }}">
                                @foreach( $status as $s)
                                	<?php $sel=''; ?>
                                	@if( $s->id == $c->status_id )
                                    	<?php $sel='selected="selected"'; ?>
                                    @endif
                                	<option {{ $sel }} value="{{ $s->id }}">{{ $s->nombre }}</option>
                                @endforeach
                                </select>
                            </td>
                            <td align="left">{{ $c->comentario }}</td>
                            <td align="left">{{ $c->nombre }}</td>
                            <td align="center">{{ $c->proyecto()->titulo }}</td>
                            <td align="center"><a href="#" data-com_id="{{ $c->id }}" data-nombre="{{ $c->nombre }}" class="eliminar">Eliminar</a></td>
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
        <input type="hidden" name="page" value="{{ Input::get('page', '') }}" />
        <input type="hidden" name="id" id="orden_id" value="" />
        <input type="hidden" name="url_return" id="url_return" value="{{ url('admin/cometarios?page='.$cometarios->getCurrentPage() ) }}" />
    </form>
</div>

<div style="display:none; background-color:#CCC; max-width:350px;">
	<div id="ajax_mxg"></div>
</div>