<table id="listado" class="table table-hover table-bordered" style="background: #ffe2e2;">
<thead>
  <tr>
    <th style="display: none;"></th>
    <th style="width: 20%;">Fecha / Horas</th>                  
    <th>Cliente</th>
    <th>Servicio</th>
    <th>Empleado</th>
    <?php
    /*****************************************/
    /* añadido para editar la cita en espera */
    /*****************************************/
    ?>
    <th class="text-center">
      Ver/Editar
    </th>
    <?php
    /*****************************************/
    /* añadido para editar la cita en espera */
    /*****************************************/
    ?>
  </tr>
</thead>
<tbody class="text-gray-700 fw-semibold">            
  <?php if (isset($citas_aviso)) { if ($citas_aviso != 0) { foreach ($citas_aviso as $key => $row) { ?>
  <tr>
    <td style="display: none;">
      <?php echo $row['fecha_aaaammdd']; ?>
    </td>
    <td>
      <center>
      <?php echo $row['fecha_ddmmaaaa_abrv']; ?>
      <br>
      <small>(De <?php echo $row['hora_inicio']." a ",$row['hora_fin']; ?>)</small>
      <?php if ($row['id_usuario_creacion']==0) { ?>
                        <img src="<?php echo base_url();?>recursos/images/online.png">
                    <?php } ?>
      </center>
    </td>                  
    <td>
      <a href="<?php echo base_url();?>clientes/historial/ver/<?php echo $row['id_cliente'] ?>" target="_blank">
        <strong><?php echo strtoupper($row['cliente']); ?></strong>
        </a>
        <br>
        <?php echo strtoupper($row['telefono']); ?>
        <?php if ($row['como_contactar'] != "") { ?>
        <br>Contactar por: <?php echo $row['como_contactar']; ?>
        <?php } ?>
    </td>
    <td>
      <?php echo strtoupper($row['nombre_familia']." - ".$row['nombre_servicio']); ?>
      <br>
      <small>(<?php echo strtoupper($row['duracion']); ?> minutos)</small>
      <br>
      <?php if ($row['notas'] != "") { echo $row['notas']; } ?>
    </td>
    <td>
      <?php if ($row['id_usuario_empleado'] > 0) { ?>
      <?php echo $row['empleado']; ?>
      <?php } else { echo "ME DA IGUAL"; } ?>
    </td>
    <?php
    /*****************************************/
    /* añadido para editar la cita en espera */
    /*****************************************/
    ?>
    <td class="text-center">
      <script type="text/javascript">
      function CitasEsperaEditar_<?php echo $row['id_cita_espera'] ?>() {
          var posicion_x;
          var posicion_y;
          var ancho=600;
          var alto=700;
          posicion_x=(screen.width/2)-(ancho/2);
          posicion_y=(screen.height/2)-(alto/2);
          window.open("<?php echo base_url();?>avisos/citas_espera_gestion/editar/<?php echo $row['id_cita_espera'] ?>", "_blank", "toolbar=no,scrollbars=no,resizable=no,top="+posicion_y+",left="+posicion_x+",width="+ancho+",height="+alto);
      }
      </script>
      <span class="label label-sm label-primary">
          <a href="#" onclick="javascript:CitasEsperaEditar_<?php echo $row['id_cita_espera'] ?>();" style="color: #fff; font-weight: bold;">Ver/Editar</a>
      </span>
    </td>
    <?php
    /*****************************************/
    /* añadido para editar la cita en espera */
    /*****************************************/
    ?>
  </tr>                
  <?php } } } ?>
</tbody>                      
</table>