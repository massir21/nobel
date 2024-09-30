<div class="card card-flush">
    <div class="card-body pt-6">
        <?php if ($accion == "nuevo") {
            $actionform = base_url() . 'usuarios/gestion/guardar';
        } else {
            $actionform = base_url() . 'usuarios/gestion/actualizar/' . $registros[0]['id_usuario'];
        } ?>
        <form id="form" action="<?php echo $actionform; ?>" role="form" method="post" name="form">
            <h3>Datos Personales</h3>
            <div class="row mb-5 border-bottom">        
                <div class="col-md-6 col-lg-4 mb-5">
                    <label class="form-label">Nombre</label>
                    <input name="nombre" class="form-control form-control-solid" type="text" value="<?= (isset($registros)) ? $registros[0]['nombre']:''?>" placeholder="" required />
                </div>
                <div class="col-md-6 col-lg-4 mb-5">
                    <label class="form-label">Apellidos</label>
                    <input name="apellidos" class="form-control form-control-solid" type="text" value="<?= (isset($registros)) ? $registros[0]['apellidos']:''?>" placeholder="" required/>
                </div>
                <div class="col-md-6 col-lg-4 mb-5">
                    <label class="form-label">Contraseña</label>
                    <input name="password" class="form-control form-control-solid" type="password" value="<?= (isset($registros)) ? $registros[0]['password']:''?>" placeholder="" required/>
                </div> 
                <div class="col-md-6 col-lg-4 mb-5">
                    <label class="form-label">Email / Usuario</label>
                    <input name="email" class="form-control form-control-solid" type="email" value="<?= (isset($registros)) ? $registros[0]['email']:''?>" placeholder="" required/>
                </div>
                <div class="col-md-6 col-lg-4 mb-5">
                    <label class="form-label">Teléfono</label>
                    <input name="telefono" class="form-control form-control-solid" type="number" value="<?= (isset($registros)) ? $registros[0]['telefono']:''?>" placeholder="" required/>
                </div> 
                <div class="col-md-6 col-lg-4 mb-5">
                    <label class="form-label">Borrado</label>
                    <select name="borrado" class="form-select form-select-solid" required>                                                
                        <option value="0" <?= (isset($registros) && $registros[0]['borrado']==0) ? "selected":''?>>NO</option>
                        <option value="1" <?= (isset($registros) && $registros[0]['borrado']==1) ? "selected":''?>>SI</option>                        
                    </select>
                </div>
            </div>
            <h3>Datos laborales</h3>
            <div class="row mb-5 border-bottom">
                <div class="col-md-6 col-lg-3 mb-5">
                    <label class="form-label">Perfil</label>
                    <select name="id_perfil" class="form-control form-control-solid" required>
                        <option value="">Elegir ....</option>
                        <?php if ($perfiles != 0) { foreach ($perfiles as $key => $row) { ?>
                            <option value="<?php echo $row['id_perfil'] ?>" <?php if (isset($registros)) { if ($registros[0]['id_perfil']==$row['id_perfil']) { echo "selected"; } } ?>><?php echo $row['nombre_perfil'] ?></option>
                        <?php }} ?>
                    </select>
                </div>
                <div class="col-md-6 col-lg-3 mb-5">
                    <label class="form-label">Centro</label>
                    <select name="id_centro" class="form-control form-control-solid" required>
                        <option value="">Elegir ....</option>
                        <?php if ($centros != 0) { foreach ($centros as $key => $row) { ?>                                                    
                            <option value="<?php echo $row['id_centro'] ?>" <?php if (isset($registros)) { if ($registros[0]['id_centro']==$row['id_centro']) { echo "selected"; } } ?>><?php echo $row['nombre_centro'] ?></option>
                        <?php }} ?>
                    </select>
                </div> 
                <div class="col-md-6 col-lg-3 mb-5">
                    <label class="form-label">Color en Agenda</label>
                    <input name="color" class="form-control form-control-solid" type="color" value="<?= (isset($registros)) ? $registros[0]['color']:''?>" />
                </div>
                <div class="col-md-6 col-lg-3 mb-5">
                    <label class="form-label">Horas semanales</label>
                    <input name="horas_semana" class="form-control form-control-solid" type="number" step="0.5" value="<?= (isset($registros)) ? $registros[0]['horas_semana']:''?>" />
                </div>
            </div>

            <h3>Datos fiscales</h3>
            <div class="row mb-5 border-bottom">

                <div class="col-md-6 col-lg-3 mb-5">
                    <label class="form-label">NIF</label>
                    <input name="nif" class="form-control form-control-solid" type="text" value="<?= (isset($registros)) ? $registros[0]['nif']:''?>" placeholder="" />
                </div>
                <div class="col-md-6 col-lg-3 mb-5">
                    <label class="form-label">Nº de colegiado</label>
                    <input name="n_colegiado" class="form-control form-control-solid" type="text" value="<?= (isset($registros)) ? $registros[0]['n_colegiado']:''?>" placeholder=""/>
                </div>
                <div class="col-md-6 col-lg-3 mb-5">
                    <label class="form-label">Domicilio</label>
                    <input name="domicilio" class="form-control form-control-solid" type="text" value="<?= (isset($registros)) ? $registros[0]['domicilio']:''?>" placeholder="" />
                </div>
                <div class="col-md-6 col-lg-3 mb-5">
                    <label class="form-label">Provincia</label>
                    <select name="provincia" class="form-control form-control-solid" data-control="select2">
                        <option value="">Elegir ....</option>
                        <?php if ($provincias != 0) { foreach ($provincias as $key => $row) { ?>                                                    
                            <option value="<?php echo $row['provincia'] ?>" <?php if (isset($registros)) { if ($registros[0]['provincia']==$row['provincia']) { echo "selected"; } } ?>><?php echo $row['provincia'] ?></option>
                        <?php }} ?>
                    </select>
                </div> 
            </div>
            <h3>Datos fiscales de la empresa (solo si son necesarios)</h3>
            <div class="row mb-5 border-bottom">

                <div class="col-md-6 col-lg-3 mb-5">
                    <label class="form-label">Nombre de empresa</label>
                    <input name="empresa" class="form-control form-control-solid" type="text" value="<?= (isset($registros)) ? $registros[0]['empresa']:''?>" placeholder="" />
                </div>

                <div class="col-md-6 col-lg-3 mb-5">
                    <label class="form-label">CIF de la empresa</label>
                    <input name="cif" class="form-control form-control-solid" type="text" value="<?= (isset($registros)) ? $registros[0]['cif']:''?>" placeholder="" />
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