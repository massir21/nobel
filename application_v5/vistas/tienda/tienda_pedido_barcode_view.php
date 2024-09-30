<div class="card card-flush">
    <div class="card-header align-items-end py-5 gap-2 gap-md-5">
        <form id="form" action="<?php echo base_url(); ?>tienda/pedido_barcode" role="form" method="post" name="form">
            <div class="card-toolbar flex-row-fluid justify-content-end gap-5 align-items-end">
                <div class="w-auto">
                    <label for="" class="form-label">Nº de Pedido</label>
                    <input type="number" id="post_id" name="post_id" value="<?=(isset($post_id))?$post_id:''?>" class="form-control form-control-solid w-auto" placeholder="Introducir Nº de Pedido" / required>
                </div>
                <div class="w-auto    ms-3">
                    <button type="submit" class="btn btn-info text-inverse-info">Comprobar</button>
                </div>
            </div>
        </form>
    </div>
    <div class="card-body pt-6">
        <?php if (isset($no_existe)) {
            if ($no_existe == 1) { ?>
                <div class="alert alert-danger d-flex flex-column flex-sm-row p-5 mb-0">
                    <div class="align-items-baseline d-flex justify-content-center pe-0 pe-sm-10">No existe el pedido: <?=(isset($post_id))?$post_id:''?></div>
                </div>
            <?php } else { ?>
                <div class="alert alert-primary d-flex flex-column flex-sm-row p-5 mb-10">
                    <div class="align-items-baseline d-flex justify-content-center pe-0 pe-sm-10">El barcode asociado es: <?=(isset($barcode))?$barcode:''?></div>
                </div>
        <?php }
        } ?>
    </div>
</div>