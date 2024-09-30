<div class="card card-flush">
    <div class="card-body pt-6">
        <?php if ($accion === "nuevo") {
            $actionform = base_url() . 'proveedores/tipos/guardar';
        } else {
            $actionform = base_url() . 'proveedores/tipos/actualizar/' . $registros[0]['id_tipo'];
        } ?>
        <form id="form" action="<?php echo $actionform; ?>" role="form" method="post" name="form">

            <div class="row mb-5 border-bottom">
                <div class="col-lg-6 mb-5">
                    <label class="form-label">Nombre tipo</label>
                    <input name="nombre" class="form-control form-control-solid" type="text" value="<?=(isset($registros))?$registros[0]['nombre']:''?>" placeholder="Nombre tipo" required />
                </div>
            </div>
            <div class="row mb-5 border-bottom">
                <div class="col-lg-6 mb-5">
                    <label class="form-label">¿ Es un pago a doctor ?<br>Si 
                    <input name="pago_doctor" type="checkbox" <?php if(isset($registros)&&$registros[0]['pago_doctor']=='1'){ echo "checked"; } ?> placeholder="¿ Es un pago a doctor ?" value='1' />
                    </label>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button class="btn btn-primary text-inverse-primary" type="submit">Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>