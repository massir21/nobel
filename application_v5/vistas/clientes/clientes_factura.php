<table class="table">
  <tr>
    <th>Nombre</th>
    <th>Apellidos</th>
    <th>Teléfono</th>
    <th>Correo</th>
    <th>Saldo sin facturar</th>
    <th></th>
  </tr>
  <?php if(isset($clientes))foreach($clientes as $c){?>
    <tr>
      <td><?= $c->nombre ?></td>
      <td><?= $c->apellidos ?></td>
      <td><?= $c->telefono ?></td>
      <td><?= $c->email ?></td>
      <td>0.00</td>
    </tr>
    <?php } ?>
</table>