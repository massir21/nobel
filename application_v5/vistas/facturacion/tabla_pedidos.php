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
                        <span class="caption-subject bold uppercase"> Seleccionar Pedidos</span>
                    </div>
                </div>
                <div class="card-body pt-6">
        <div class="table-responsive">
                    <?php $attr=['class'=> 'form-horizontal', 'id' => 'form_seleccionar_pedido', 'target' => '_blank']; echo form_open('facturacion/mas_facturacion', $attr);
                    echo (isset($id_centro)) ? form_hidden('id_centro', $id_centro) : '';
                    echo (isset($id_centro)) ? form_hidden('fecha_desde', $fecha_desde) : '';
                    echo (isset($id_centro)) ? form_hidden('fecha_hasta', $fecha_hasta) : '';
                    ?>
                    <div class="form-group">
                        <label class="control-label col-sm-4">Empresa</label>
                        <div class="col-sm-6">
                            <h3><?php echo $centro->empresa;?></h3>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4">Fecha de la factura</label>
                        <div class="col-sm-6">
                            <input type="date" name="fecha_fact" class="form-control form-control-solid" required="required">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4">Número de factura</label>
                        <div class="col-sm-6">
                            <input type="text" name="num_fact" class="form-control form-control-solid" required="required">
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="pedidos" class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                                    <thead class="">
                                        <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
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
                        <caption>Facturables fuera de DB</caption>
                        <table id="facturables" class="table table-striped table-hover table-bordered" style="min-width: 500px;">
                            <thead>
                                <tr>
                                    <th style="width: 10%;">REF</th>
                                    <th style="width: 50%;">Desctipción</th>
                                    <th style="width: 15%;">Cantidad</th>
                                    <th style="width: 15%;">Coste Unitario(sin IVA)</th>
                                    <th class="text-right"><button type="button" class="btn btn-transparent green btn-outline btn-circle btn-sm active" onClick="create_new_td()"><i class="fa fa-plus"></i></button></th>
                                </tr>
                            </thead>
                            <tbody id="facturables_body">
                                <tr id="tr_clone" style="display: none;" data-value="0">
                                    <td><input type="text" name="ref[]" class="form-control form-control-solid"></td>
                                    <td><input type="text" name="descripcion[]" class="form-control form-control-solid"></td>
                                    <td><input type="number" name="cantidad[]" class="form-control form-control-solid" value="1" step="1"></td>
                                    <td><input type="number" name="coste_u[]" class="form-control coma" step="any"></td>
                                    <td class="text-right">
                                        <button type="button" class="btn btn-transparent red btn-outline btn-circle btn-sm" onClick="delete_td($(this))"><i class="fa fa-minus"></i></button>
                                        <button type="button" class="btn btn-transparent green btn-outline btn-circle btn-sm active" onClick="create_new_td()"><i class="fa fa-plus"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <button type="submit" id="submit_form_seleccionar_pedido" class="hide" style="position: fixed;left: -100%;"></button>
                    <?php echo form_close();?>
                    <?php $attr=['id' => 'form_facturacion']; echo form_open('facturacion', $attr);
                    echo (isset($id_centro)) ? form_hidden('id_centro', $id_centro) : '';
                    echo (isset($id_centro)) ? form_hidden('fecha_desde', $fecha_desde) : '';
                    echo (isset($id_centro)) ? form_hidden('fecha_hasta', $fecha_hasta) : '';
                    echo form_close();?>
                    <div class="text-center">
                        <button type="button" class="btn btn-primary" onClick="$('#form_facturacion').submit()">Atrás</button>
                        <button type="button" class="btn btn-primary" id="continuar_submit">Continuar</button>
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
    function create_new_td(){
        var $tr    = $('#tr_clone');
        var $clone = $tr.clone();
        $clone.find(':text').val('');
        $('#facturables_body').append($clone.show());
    }
    function delete_td(elem){
        $(elem).parents("tr").remove();
    }
    $(document).on('keyup', '.coma', function(){
        coste = $(this).val()
        if($.isNumeric(coste) === false) {
            this.value = this.value.slice(0,-1);
        }
    });
    $('#continuar_submit').click(function(){
        $('#submit_form_seleccionar_pedido').trigger('click')
    })
</script>