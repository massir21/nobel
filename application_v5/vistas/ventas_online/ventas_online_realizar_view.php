<?php setlocale(LC_MONETARY, 'es_ES'); ?>
<div class="alert alert-dismissible alert-warning d-flex flex-column flex-sm-row p-5 mb-10">
    <div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center"> A continuación indica el producto y la cantidad vendida online. La cantidad será restada del stock del producto.</div>
    <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
        <i class="fa-times fas fs-3 text-primary"></i>
    </button>
</div>
<div class="card card-flush">
    <div class="card-body pt-6">
        <form id="form" action="<?php echo base_url();?>ventas_online/guardar" role="form" method="post" name="form">
            <div class="row mb-5 border-bottom">
                <div class="col-md-6 mb-5">
                    <label class="form-label">Producto (Stock actual)</label>
                    <select name="id_producto" id="id_producto" class="form-select form-select-solid" data-placeholder="Elegir ..."></select>
                    <script type="text/javascript">
                        $("#id_producto").select2({
                            language: "es",
                            minimumInputLength: 4,
                            ajax: {
                                delay: 0,
                                url: function (params) {
                                    return '<?php echo RUTA_WWW; ?>/productos/jsonselect2/'+ params.term;
                                },
                                dataType: 'json',
                                processResults: function (data) {
                                    return {
                                        results: data
                                    };
                                }
                            }
                        });    
                    </script>
                </div>
                <div class="col-md-3 mb-5">
                    <label class="form-label">Cantidad</label>
                    <input name="cantidad_consumida" id="cantidad_consumida" class="form-control form-control-solid" type="number" value="" min="1" required />
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button class="btn btn-primary text-inverse-primary" type="submit">Guardar Venta Online</button>
                </div>
            </div>            
        </form>
    </div>
</div>