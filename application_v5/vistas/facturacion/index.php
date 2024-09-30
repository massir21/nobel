<style>
  .dataTables_filter {
    text-align: right;
  }
  .sorting_disabled::after { display: none!important; }
  td.form-control, tr.form-control{
    width: auto;
  }
</style>
<div class="page-content">
  <!-- BEGIN PAGE HEADER-->
  <!-- BEGIN PAGE BAR -->
  <div class="page-bar">
    <ul class="page-breadcrumb">
      <li>
        <a href="<?php echo base_url();?>site" style="font-size: 20px;">Panel de Control</a>
        <i class="fa fa-angle-right"></i>
      </li>
      <li>
        <span style="font-size: 20px;"><strong>Datos para la facturación</strong></span>
      </li>
    </ul>
  </div>
  <!-- END PAGE BAR -->
  <!-- END PAGE HEADER-->
  <div class="row ">
    <div class="col-md-12">
      <?php if ($this->session->flashdata('mensaje') != '') {?>
        <div class="alert alert-dismissible alert-primary d-flex flex-column flex-sm-row p-5 mb-10">
          <div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center"></div>
        <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
        <i class="fa-times fas fs-3 text-primary"></i>
    </button>
          <span><?php echo $this->session->flashdata('mensaje'); ?></span>
        </div>
      <?php }?>
      <?php setlocale(LC_MONETARY, 'es_ES');?>
      <!-- BEGIN SAMPLE FORM PORTLET-->
      <div class="portlet light bordered">
          <div class="portlet-title">
            <div class="caption font-dark">
              <i class="icon-settings font-dark"></i>
              <span class="caption-subject bold uppercase"> Crear factura</span>
            </div>
          </div>
          <div class="card-body pt-6">
        <div class="table-responsive">
            <form id="form_facturacion" action="<?php echo base_url();?>Facturacion/seleccion_pedidos" role="form" method="post" name="form_estadisticas" class="form-horizontal">
              <div class="form-group">
                <label class="control-label col-sm-3" for="id_centro">Centro</label>
                <div class="col-sm-6">
                  <select name="id_centro" id="id_centro" class="form-control form-control-solid">
                    <option value="">Todos</option>
                    <?php if (isset($centros)) {
                      if ($centros != 0) {
                        foreach ($centros as $key => $row) {
                          if ($row['id_centro'] > 1) { ?>
                            <option value='<?php echo $row['id_centro']; ?>' <?php if (isset($id_centro)) { if ($row['id_centro']==$id_centro) { echo "selected"; } } ?>>
                              <?php echo $row['nombre_centro']; ?>
                            </option>
                    <?php }}}} ?>
                  </select>
                  <?php echo form_error('id_centro');?>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-sm-3" for="fecha_desde">Desde</label>
                <div class="col-sm-6">
                  <input type="date" id="fecha_desde" class="form-control form-control-solid" name="fecha_desde" value="<?php if (isset($fecha_desde)) { echo date('Y-m-d', strtotime($fecha_desde)); } ?>" />
                  <?php echo form_error('fecha_desde');?>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-sm-3" for="fecha_hasta">Hasta</label>
                <div class="col-sm-6">
                  <input type="date" id="fecha_hasta" class="form-control form-control-solid" name="fecha_hasta" value="<?php if (isset($fecha_hasta)) { echo date('Y-m-d', strtotime($fecha_hasta)); } ?>" />
                  <?php echo form_error('fecha_hasta');?>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-sm-3" for=""></label>
                <div class="col-sm-6">
                  <button type="submit" class="btn btn-primary">Buscar pedidos</button>
                </div>
              </div>
            </form>
          </div>
      </div>
        <div style="overflow: hidden; display:none">
              <div class="col-sm-3">
                <div class="form-group">
                  <label class="form-label" for="fecha_desde">Fecha desde</label>
                  <input type="date" id="fecha_desde" class="form-control form-control-solid" name="fecha_desde" value="<?php if (isset($fecha_desde)) { echo date('Y-m-d', strtotime($fecha_desde)); } ?>" />
                </div>
              </div>
              <div class="col-sm-3">
                <div class="form-group">
                  <label class="form-label" for="fecha_hasta">Fecha hasta</label>
                  <input type="date" id="fecha_hasta" class="form-control form-control-solid" name="fecha_hasta" value="<?php if (isset($fecha_hasta)) { echo date('Y-m-d', strtotime($fecha_hasta)); } ?>" />
                </div>
              </div>
              <div class="col-sm-3">
                <div class="form-group">
                  <label class="form-label" for="id_centro">Centro</label>
                  <select name="id_centro" id="id_centro" class="form-control form-control-solid">
                    <option value="">Todos</option>
                    <?php if (isset($centros)) { if ($centros != 0) { foreach ($centros as $key => $row) { if ($row['id_centro'] > 1) { ?>
                      <option value='<?php echo $row['id_centro']; ?>' <?php if (isset($id_centro)) { if ($row['id_centro']==$id_centro) { echo "selected"; } } ?>>
                        <?php echo $row['nombre_centro']; ?>
                      </option>
                    <?php }}}} ?>
                  </select>
                </div>
              </div>
              <div class="col-sm-3">
                <div class="form-group">
                  <label class="control-label btn-block">&nbsp;</label>
                  <input type="submit" value="Buscar" class="btn btn-primary text-inverse-primary" />
                </div>
              </div>
            </form>
        </div>
        <div class="portlet light bordered hide">
          <div class="portlet-title">
            <div class="caption font-dark">
              <i class="icon-settings font-dark"></i>
              <span class="caption-subject bold uppercase"> Pedidos del centro</span>
            </div>
          </div>
          <div class="card-body pt-6">
        <div class="table-responsive">
            <?php $attr=['class'=> '']; echo form_open('facturacion/crear_factura', $attr);
            echo (isset($id_centro)) ? form_hidden('id_centro', $id_centro) : '';
            echo (isset($id_centro)) ? form_hidden('fecha_desde', $fecha_desde) : '';
            echo (isset($id_centro)) ? form_hidden('fecha_hasta', $fecha_hasta) : '';
            ?>
            <table id="pedidos" class="table table-striped table-hover table-bordered">
              <thead>
                <tr>
                  <th class="no-sort" style="width: 25px"><input type="checkbox" id="selectall" name="selectall" value="true"></th>
                  <th>Nº Pedido</th>
                  <th>Fecha pedido</th>
                  <th>Fecha entrega</th>
                  <th>Total factura</th>
                </tr>
              </thead>
              <tbody id="pedidos_body">
                <?php if (isset($pedidos)) { if ($pedidos != 0) { foreach ($pedidos as $key => $row) { ?>
                  <tr>
                    <td>
                      <?php //echo form_checkbox('id_pedido', $row->id_pedido); ?>
                      <input type="checkbox" name="id_pedido[]" value="<?php echo $row->id_pedido;?>" class="checkbox">
                    </td>
                    <td style="text-align: center;">
                      <?php echo $row->id_pedido; ?>
                    </td>
                    <td style="text-align: center;">
                      <?php echo $row->fecha_pedido; ?>
                    </td>
                    <td style="text-align: center;">
                      <?php echo $row->fecha_entrega; ?>
                    </td>
                    <td class="text-end">
                      <?php echo number_format($row->total_factura, 2, ",", ".");?>
                    </td>
                  </tr>
                <?php } } }?>
              </tbody>
            </table>
          </div>
        </div>
        <div class="portlet light bordered hide">
          <div class="portlet-title">
            <div class="caption font-dark">
              <i class="icon-settings font-dark"></i>
              <span class="caption-subject bold uppercase"> Cobros puntuales</span>
            </div>
          </div>
          <div class="card-body pt-6">
        <div class="table-responsive">
            <table id="extras" style="width: auto;" class="table table-striped table-hover table-bordered">
              <thead>
                <tr>
                  <th>Referencia</th>
                  <th>Descripcion</th>
                  <th>Cantidad</th>
                  <th>Descuento %</th>
                  <th>Precio</th>
                  <th>Total</th>
                  <th></th>
                </tr>
              </thead>
              <tbody class="text-gray-700 fw-semibold">
                <tr>
                  <td><input type="text" name="ref"  class="form-control form-control-solid"></td>
                  <td><input type="text" name="descripcion" class="form-control form-control-solid"  style="width: 350px"></td>
                  <td><input type="text" name="cantidad" class="form-control form-control-solid"></td>
                  <td><input type="text" name="descuento" class="form-control form-control-solid"></td>
                  <td><input type="text" name="precion" class="form-control form-control-solid"></td>
                  <td><input type="text" name="total" class="form-control form-control-solid"></td>
                  <td><button type="button" class="btn btn-danger">-</button><button type="button" class="btn btn-default">+</button></td>
                </tr>
              </tbody>
              <tfoot>
                <tr>
                  <th></th>
                  <th  colpan="2"></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th>Total</th>
                  <th><input type="text" name="total_all" class="form-control form-control-solid"></th>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      <?php if(isset($pedidos)) {?>
        <div class="portlet light bordered hide">
          <div class="portlet-title">
            <div class="caption font-dark">
              <i class="icon-settings font-dark"></i>
              <span class="caption-subject bold uppercase"> Creación de la factura</span>
            </div>
          </div>
          <div class="card-body pt-6">
        <div class="table-responsive">
              <div class="row">
                <div class="col-sm-4">
                  <div class="form-group">
                    <label class="form-label" for="fecha_hasta">Fecha de la factura</label>
                    <input type="date" id="fecha_factura" class="form-control form-control-solid" name="fecha_factura" value="" placeholder="Fecha de la factura" />
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group">
                    <label class="form-label" for="n_factura">Nº Factura</label>
                    <input type="text" id="n_factura" class="form-control form-control-solid" name="n_factura" value="" placeholder="Nº Factura"  />
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group">
                    <input type="submit" value="Crear factura" class="btn btn-primary text-inverse-primary" />
                  </div>
                </div>
              </div>
            </div>
          </div>
          <?php } ?>
            <?php echo form_close();?>
      </div>
      <!-- END SAMPLE FORM PORTLET-->
    </div>
  </div>
</div>
<!-- END CONTENT BODY -->
</div>
<!-- END CONTENT BODY -->
<script type="text/javascript">
  $('#pedidos').dataTable( {
    "columnDefs": [ {
      "targets": 'no-sort',
      "orderable": false,
    } ]
  } );
  $("#selectall").click(function(){
    var check = $(this).prop('checked');
    if(check == true) {
      $('.checker').find('span').addClass('checked');
      $('.checkbox').prop('checked', true);
    } else {
      $('.checker').find('span').removeClass('checked');
      $('.checkbox').prop('checked', false);
    }
  });
  $(".checkbox").click(function(){
    var check = $(this).prop('checked');
    var selectAll = $('#selectall').prop('checked');
    if(check != true) {
      if(selectAll == true) {
        $('#selectall').prop('checked', false);
        $('#uniform-selectall').find('span').removeClass('checked');
      }
    }
  });
</script>