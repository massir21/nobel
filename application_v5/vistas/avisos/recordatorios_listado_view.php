<table id="listado" class="table table-hover table-bordered" style="background: #fff9d1;">
    <thead style="background: #aaa; color: #fff;">
        <th>Fecha / Hora</th>
        <th style="text-align: center; width: 7%;">Usuario</th>
        <th>Rercodatorio</th>
        <th style="text-align: center;">Posponer 30 minutos</th>
        <th>Finalizar</th>
    </thead>
    <tbody class="text-gray-700 fw-semibold">            
      <?php foreach ($recordatorios as $key => $row) { ?>
      <tr id="fila<?php echo $row['id_recordatorio']; ?>">
        <td style="text-align: center; width: 12%;">          
          <?php echo $row['fecha_hora_ddmmaaaa_hhss']; ?>          
        </td>
        <td>
          <?php echo $row['usuario_creador']; ?>
        </td>    
        <td>
          <?php echo $row['recordatorio']; ?>
        </td>    
        <td style="text-align: center; width: 5%;">
            <input type="checkbox" name="check_pos_<?php echo $row['id_recordatorio']; ?>" onclick="Posponer(<?php echo $row['id_recordatorio']; ?>)">
        </td>
        <td style="text-align: center; width: 5%;">
            <input type="checkbox" name="check<?php echo $row['id_recordatorio']; ?>" onclick="RecordatorioRealizado(<?php echo $row['id_recordatorio']; ?>)">
        </td>    
      </tr>                
      <?php } ?>
    </tbody>                      
</table>