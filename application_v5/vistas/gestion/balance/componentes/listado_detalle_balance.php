<?php if(is_array($registros)) { ?>
<?php foreach ($registros as $key => $row) { ?>
<tr>
    <td><?php echo $row['nombre_centro']; ?></td>
    <td><?php echo $row['mes']; ?></td>
    <td><?php echo $row['anio_actual']; ?></td>
    <td><?php echo euros($row['total_facturado']) ?></td>
    <td><?php echo euros(0.00) ?></td>
    <td><?php echo euros(0.00) ?></td>
</tr>

<?php } // fin del foreach ?>

<?php } else { ?>
    <tr>
        <td colspan="5" class="text-center">No se encontraron registros disponibles.</td>
    </tr>
<?php } ?>