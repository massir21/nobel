<style>
  .dataTables_filter {
    text-align: right;
  }
  .sorting_disabled::after { display: none!important; }
  td.form-control, tr.form-control{
    width: auto;
  }
  thead>tr>th, .total{
    padding: 8px;
    background: #888;
    color: #fff;
  }
  td:first-child{
    width: 130px
  }
  .numeral{
    width: 60px;
    text-align:right;
  }
  table{
    margin-bottom: 50px !important;
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
        <span style="font-size: 20px;"><strong>Factura</strong></span>
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
              <span class="caption-subject bold uppercase"> Factura - Vista previa</span>
            </div>
          </div>
          <div class="card-body pt-6">
        <div class="table-responsive">
            <h1 style="border-bottom: 1px solid #888; padding-bottom: 15px;">FACTURA <small class="pull-right text-right">Factura nº <?php echo $num_fact;?><br>Fecha: <?php echo date('d-m-Y', strtotime($fecha_fact));?></small></h1>
            <div class="row">
              <div class="col-sm-6">
                <h2><?php echo $tdm->empresa;?></h2>
                <p>
                  <?php echo $tdm->cif;?>
                  <br>
                  <?php echo $tdm->dir1;?>
                  <br>
                  <?php echo $tdm->dir2;?>
                </p>
                <p>
                  <?php echo $tdm->tel;?>
                  <br>
                  <?php echo $tdm->mail;?>
                </p>
              </div>
              <div class="col-sm-6 text-right">
                <h2><?php echo $centro->empresa;?></h2>
                <p>
                  <?php echo $centro->cif;?>
                  <br>
                  <?php echo $centro->dir1;?>
                  <br>
                  <?php echo $centro->dir2;?>
                </p>
                <p>
                  <?php echo $centro->tel;?>
                  <br>
                  <?php echo $centro->email;?>
                </p>
              </div>
            </div>
            <?php if (isset($extra->rows)) { if (count($extra->rows) > 0) {?>
              <table id="extra" class="table table-striped table-hover table-bordered">
                <thead>
                  <tr><th colspan="7" class="text-center"><strong>VARIOS</strong></th></tr>
                  <tr>
                    <th>REF</th>
                    <th colspan="2">Descripcion</th>
                    <th>Cantidad</th>
                    <th>PRECIO</th>
                    <th>TOTAL</th>
                  </tr>
                </thead>
                <tbody id="extra_body">
                  <?php foreach ($extra->rows as $key => $row) { ?>                  
                    <tr>
                      <td><?php echo $row->ref; ?></td>
                      <td colspan="2"><?php echo $row->descripcion; ?></td>
                      <td class="numeral"><?php echo $row->cantidad; ?></td>
                      <td class="numeral"><?php echo $row->coste; ?></td>
                      <td class="numeral"><?php echo $row->total; ?></td>
                    </tr>
                  <?php } ?>
                </tbody>
                <tfoot>
                  <tr>
                    <th colspan="5" class="text-right">Total</th>
                    <th class="total"><?php echo $extra->total; ?></th>
                  </tr>
                </tfoot>
              </table>
            <?php } }?>
            <?php if (isset($productos->rows)) { if (count($productos->rows) > 0) {?>
              <table id="productos" class="table table-striped table-hover table-bordered">
                <thead>
                  <tr><th colspan="7" class="text-center"><strong>PRODUCTOS</strong></th></tr>
                  <tr>
                    <th>REF</th>
                    <th colspan="2">Descripcion</th>
                    <th>Cantidad</th>
                    <th>%DTO</th>
                    <th>PRECIO</th>
                    <th>TOTAL</th>
                  </tr>
                </thead>
                <tbody id="productos_body">
                  <?php foreach ($productos->rows as $key => $row) { ?>                  
                    <tr>
                      <td><?php echo $row->ref; ?></td>
                      <td><?php echo $row->familia; ?></td>
                      <td><?php echo $row->producto; ?></td>
                      <td class="numeral"><?php echo $row->cantidad; ?></td>
                      <td class="numeral"><?php echo $row->descuento; ?></td>
                      <td class="numeral"><?php echo $row->precio_sin_iva; ?></td>
                      <td class="numeral"><?php echo $row->subtotal; ?></td>
                    </tr>
                  <?php } ?>
                </tbody>
                <tfoot>
                  <tr>
                    <th colspan="6" class="text-right">Total productos</th>
                    <th class="total"><?php echo $productos->total; ?></th>
                  </tr>
                </tfoot>
              </table>
            <?php } }?>
            <?php if (isset($citas->rows)) { if (count($citas->rows) > 0) {?>
              <table id="citas_online" class="table table-striped table-hover table-bordered">
                <thead>
                  <tr><th colspan="7" class="text-center"><strong>Citas ONLINE</strong></th></tr>
                  <tr>
                    <th>FECHA</th>
                    <th>SERVICIO</th>
                    <th>CLIENTE</th>
                    <th>EMPLEADO</th>
                    <th>COMISIÓN</th>
                    <th>PRECIO</th>
                  </tr>
                </thead>
                <tbody id="citas_online_body">
                  <?php foreach ($citas->rows as $key => $row) { ?>
                    <tr>
                      <td><?php echo date('d/m/Y', strtotime($row->fecha)); ?></td>
                      <td><?php echo $row->servicio;?></td>
                      <td><?php echo $row->cliente;?></td>
                      <td ><?php echo $row->empleado;?></td>
                      <td class="numeral"><?php echo $row->comision; ?></td>
                      <td class="numeral"><?php echo $row->precio_sin_iva; ?></td>
                    </tr>
                  <?php } ?>
                </tbody>
                <tfoot>
                  <tr>
                    <th colspan="5" class="text-right">Total (sin IVA)</th>
                    <th class="total"><?php echo $citas->total; ?></th>
                  </tr>
                  <tr>
                    <th colspan="5" class="text-right">Comisión (25%)</th>
                    <th class="total"><?php echo $citas->comision; ?></th>
                  </tr>
                </tfoot>
              </table>
            <?php } }?>
            <?php if (isset($intercentros->rows)) { if (count($intercentros->rows) > 0) {?>
              <table id="carnets_deotros" class="table table-striped table-hover table-bordered">
                <thead>
                  <tr><th colspan="8" class="text-center"><strong>Intercentros</strong></th></tr>
                  <tr>
                    <th>FECHA</th>
                    <th>SERVICIO</th>
                    <th>TEMPLOS</th>
                    <th>CARNET</th>
                    <th>TIPO</th>
                    <th>ORIGINAL DE -> USADO EN</th>
                    <th>COMISIÓN</th>                   
                    <th>TOTAL(C/I)</th>
                  </tr>
                </thead>
                <tbody id="citas_online_body">
                  <?php foreach ($intercentros->rows as $key => $value) {?>
                    <tr>
                      <td><?php echo $value->fecha;?></td>
                      <td><?php echo $value->servicio;?></td>
                      <td><?php echo $value->templos;?></td>
                      <td><?php echo $value->carnet;?></td>
                      <td><?php echo $value->carnet_tipo;?></td>
                      <td ><?php echo $value->original_de;?> -> <?php echo $value->usado_en;?></td>
                      <td class="numeral"><?php echo $value->total_comision;?></td>
                      <td class="numeral"><?php echo $value->total_servicio;?></td>
                    </tr>
                  <?php } ?>
                </tbody>
                <tfoot>
                  <tr>
                    <td colspan="7" class="text-right">Balance pagos intercentros</td>
                    <td class="total"><?php echo $intercentros->total;?></td>
                  </tr>
                  <tr>
                    <td colspan="7" class="text-right">Total comisiones</td>
                    <td class="total"><?php echo $intercentros->comision;?></td>
                  </tr>
                </tfoot>
              </table>
            <?php } }?>
             <table id="tabla_totales" class="table table-striped table-hover table-bordered">
                <thead>
                  <tr>
                    <th colspan="2" class="text-center"><strong>Total Factura</strong></th>
                  </tr>
                </thead>
                <tbody class="text-gray-700 fw-semibold">
                  <tr>
                    <td class="text-right" style="width: 75%;">Subtotal</td>
                    <td><?php echo $total_factura['bruto'];?></td>
                  </tr>
                  <tr>
                    <td class="text-right" style="width: 75%;">IVA</td>
                    <td><?php echo $total_factura['IVA'];?></td>
                  </tr>
                </tbody>
                <tfoot>
                  <tr>
                    <th class="text-right" style="width: 75%;"><h3>Total Factura</h3></th>
                    <th class="total"><h3><?php echo $total_factura['total'];?></h3></th>
                  </tr>
                </tfoot>
              </table>
            <?php $attr=['id' => 'form_facturacion']; echo form_open('facturacion', $attr);
            echo (isset($id_centro)) ? form_hidden('id_centro', $id_centro) : '';
            echo (isset($id_centro)) ? form_hidden('fecha_desde', $fecha_desde) : '';
            echo (isset($id_centro)) ? form_hidden('fecha_hasta', $fecha_hasta) : '';
            echo form_close();?>
            <div class="text-center">
               <?php $attr=['class' => 'form-horizontal', 'id' => 'form_facturacion', 'target' => '_blank']; echo form_open('facturacion/excel', $attr);
               echo form_hidden('datos', 1);?>
              <button type="submit" class="btn btn-primary">Exportar</button>
              <?php echo form_close();?>
            </div>
          </div>
      </div>
      <!-- END SAMPLE FORM PORTLET-->
    </div>
  </div>
</div>
<!-- END CONTENT BODY -->
</div>
<!-- END CONTENT BODY -->
<script type="text/javascript">
</script>