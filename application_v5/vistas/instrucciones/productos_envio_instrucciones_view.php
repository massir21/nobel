<style>
    .dataTables_filter {
        text-align: right;
    }
</style>
<?php if (isset($enviado)) { ?>
    <div class="alert alert-dismissible alert-primary d-flex flex-column flex-sm-row p-5 mb-10">
        <div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center">EL ENVÍO SE REALIZÓ CORRECTAMENTE</div>
        <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
            <i class="fa-times fas fs-3 text-primary"></i>
        </button>
    </div>
<?php } ?>
<?php setlocale(LC_MONETARY, 'es_ES'); ?>
<div class="card card-flush">
    <div class="card-header">
        <div class="card-title">A continuación indica el nombre del cliente, email y los productos de los cuales deseas enviar las instrucciones</div>
    </div>
    <div class="card-body pt-6">
        <form id="form" action="<?php echo base_url(); ?>instrucciones/enviar" role="form" method="post" name="form">
            <div class="row mb-5 border-bottom">
                <div class="col-md-6 col-xl-4 mb-5">
                    <label class="form-label">Nombre del cliente</label>
                    <input name="nombre_cliente" id="nombre_cliente" class="form-control form-control-solid" type="text"/>
                </div>
                <div class="col-md-6 col-xl-4 mb-5">
                    <label class="form-label">Email del cliente</label>
                    <input name="email_cliente" id="email_cliente" class="form-control form-control-solid" type="email"/>
                </div>
                <div class="col-md-12 col-xl-8 mb-5">
                    <label class="form-label">Producto</label>
                    <select name="productos[]" id="productos" class="form-select form-select-solid" data-placeholder="Elegir ..." multiple></select>
                    <script type="text/javascript">
                        $("#productos").select2({
                            language: "es",
                            minimumInputLength: 2,
                            ajax: {
                                delay: 0,
                                url: function (params) {
                                    return '<?php echo RUTA_WWW; ?>/instrucciones/json/'+ params.term;
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
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button class="btn btn-primary text-inverse-primary" type="submit">Enviar</button>
                </div>
            </div>
        </form>
    </div>
</div>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#productos_instrucciones").tokenInput("<?php echo RUTA_WWW; ?>/instrucciones/json/", {
                hintText: "Elegir un producto...",
                noResultsText: "Sin resultados",
                searchingText: "Buscando...",
                minChars: 3
            });
        });
    </script>