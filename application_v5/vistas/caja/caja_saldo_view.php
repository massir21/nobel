<?php if (isset($estado) && $estado > 0) { ?>
    <div class="alert alert-dismissible alert-primary d-flex flex-column flex-sm-row p-5 mb-10">
        <div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center">EL SALDO SE GUARDÓ CORRECTAMENTE</div>
        <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
            <i class="fa-times fas fs-3 text-primary"></i>
        </button>
    </div>
<?php }?>
<div class="card card-flush">
    <?php if ($id_perfil > 0) { ?>
        <div class="card-header align-items-end py-5 gap-2 gap-md-5">
            <div class="card-title w-100 align-items-end">
            Saldo Inicial de la Caja
            </div>
        </div>
        <?php } ?>
    <div class="card-body pt-6">
        <?php if ($id_perfil > 0) { ?>
            <form id="form" action="<?php echo base_url(); ?>caja/saldo/guardar" role="form" method="post" name="form">
                <div class="row mb-5 border-bottom">
                    <div class="col-md-6 col-lg-3 col-xxl-2 mb-5">
                        <label class="form-label">Saldo inicial €</label>
                        <input type="number" id="saldo_inicial" name="saldo_inicial" value="<?php if (isset($saldo)) {echo $saldo;} ?>" class="form-control form-control-solid w-auto" placeholder="Saldo inicial €" step="0.01" required/>
                    </div>
                    <div class="col-lg-9 mb-5">
                        <label class="form-label">Motivo del cambio del saldo inicial</label>
                        <textarea class="form-control form-control-solid" name="motivo"></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button class="btn btn-primary text-inverse-primary" type="submit">GUARDAR NUEVO SALDO INICIAL</button>
                    </div>
                </div>
          </form>
        <?php } else {?>
            <div class="alert alert-primary d-flex flex-column flex-sm-row p-5 mb-0">
                <div class="align-items-baseline d-flex justify-content-center pe-0 pe-sm-10">Para cambiar el saldo inicial como Máster, se debe realizar desde la <a href="<?php echo base_url();?>usuarios/centros" class="btn btn-outline btn-outline-success ms-5">Gestión de Centros</a>.</div>
            </div>
        <?php } ?>
    </div>
</div>