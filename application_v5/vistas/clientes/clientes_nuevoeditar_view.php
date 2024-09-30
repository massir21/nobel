<div class="card card-flush">
    <div class="card-body pt-6">
        <?php if ($accion == "nuevo") {
            $actionform = base_url().'clientes/gestion/guardar';
        } else { 
            $actionform = base_url().'clientes/gestion/actualizar/'.$registros[0]['id_cliente'];

            if(isset($_GET['frompresupuesto'])){
                $actionform.="?frompresupuesto=".$_GET['frompresupuesto'];
            }
        } ?>

        <form id="form" action="<?php echo $actionform; ?>" role="form" method="post" name="form">
                <div class="row mb-5 border-bottom">
                    <div class="col-md-6 col-lg-3 col-xxl-2 mb-5">
                        <label class="form-label">Nombre (*)</label>
                        <input name="nombre" class="form-control form-control-solid" type="text" value="<?php if (isset($registros)) {echo $registros[0]['nombre'];} ?>" placeholder="Nombre" required />
                    </div>
                    <div class="col-md-6 col-lg-3 col-xxl-2 mb-5">
                        <label class="form-label">Apellidos  (*)</label>
                        <input name="apellidos" class="form-control form-control-solid" type="text" value="<?php if (isset($registros)) {echo $registros[0]['apellidos'];} ?>" placeholder="" required />
                    </div>
                    <div class="col-md-6 col-lg-3 col-xxl-2 mb-5">
                        <label class="form-label">Email</label>
                        <input name="email" class="form-control form-control-solid" type="email" value="<?php if (isset($registros)) {echo $registros[0]['email'];} ?>" placeholder="" />
                    </div>
                    <div class="col-md-6 col-lg-3 col-xxl-2 mb-5">
                        <label class="form-label">Teléfono (*)</label>
                        <input name="telefono" class="form-control form-control-solid" type="number" value="<?php if (isset($registros)) {echo $registros[0]['telefono'];} ?>" placeholder="" required />
                    </div>
                    <div class="col-md-6 col-xl-4">
                        <label class="form-label">Dirección (*)</label>
                        <input name="direccion" class="form-control form-control-solid" type="text" value="<?php if (isset($registros)) {echo $registros[0]['direccion'];} ?>" placeholder="" />
                    </div>
                    <div class="col-md-6 col-lg-3 col-xxl-2 mb-5">
                        <label class="form-label">Código Postal (*)</label>
                        <input name="codigo_postal" class="form-control form-control-solid" type="text" value="<?php if (isset($registros)) {echo $registros[0]['codigo_postal'];} ?>" placeholder="" />
                    </div>
                    <div class="col-md-6 col-lg-3 col-xxl-2 mb-5">
                        <label class="form-label">Fecha de Nacimiento (*)</label>
                        <input class="form-control form-control-solid" id="fecha_nacimiento" name="fecha_nacimiento" type="date" value="<?php if (isset($registros[0]['fecha_nacimiento_aaaammdd'])) {echo $registros[0]['fecha_nacimiento_aaaammdd'];} ?>" onblur="calcEdad()">
                    </div>
                    <!-- 13/11/23 Tutor -->
                    <?php 
                    $displayEstado="none";
                    if (isset($registros)) {
                          if ($registros[0]['edad']<18)
                             $displayEstado="block";
                    }  
                        ?>
                    <div class="row" id="divTutor" style="display: <?php echo $displayEstado; ?>; display:flex" >
                            <div class="col-md-6 col-lg-3 col-xxl-2 mb-5">
                                <label class="form-label">Nombre y Apellido del Tutor</label>
                                <input class="form-control form-control-solid" id="nombre_tutor" name="nombre_tutor" type="text" value="<?php if (isset($registros[0]['nombre_tutor'])) {echo $registros[0]['nombre_tutor'];} ?>">
                            </div>
                            <div class="col-md-6 col-lg-3 col-xxl-2 mb-5">
                                <label class="form-label">D.N.I Tutor (*)</label>
                                <input class="form-control form-control-solid" id="dni_tutor" name="dni_tutor" type="text" value="<?php if (isset($registros[0]['dni_tutor'])) {echo $registros[0]['dni_tutor'];} ?>">
                            </div>
                    </div>
                    <!-- fin -->
                    <div class="col-md-6 col-lg-3 col-xxl-2 mb-5">
                        <label class="form-label">D.N.I (*)</label>
                        <input class="form-control form-control-solid" id="dni" name="dni" type="text" value="<?php if (isset($registros[0]['dni'])) {echo $registros[0]['dni'];} ?>" maxlength="9" style="text-transform:uppercase">
                    </div>
                    <div class="col-xl-6">
                        <label class="form-label">Notas</label>
                        <input name="google_contacts" type="hidden" value="<?php if (isset($registros[0]['google_contacts'])) {echo $registros[0]['google_contacts'];} ?>">
                        <textarea class="form-control form-control-solid" name="notas"><?php if (isset($registros[0]['notas'])) { echo $registros[0]['notas'];} ?></textarea>
                    </div>
                    <div class="col-md-6 col-lg-3 col-xxl-2 mb-5">
                        <label class="form-label">NO quiero recibir publicidad</label>
                        <div class="form-check form-check-solid form-switch form-check-custom fv-row">
                            <input class="form-check-input w-45px h-30px" type="checkbox" id="no_quiere_publicidad" name="no_quiere_publicidad" value="1" <?php if (isset($registros[0]['no_quiere_publicidad'])) {if ($registros[0]['no_quiere_publicidad'] == 1) { echo "checked";}} ?>>
                            <label class="form-check-label" for="no_quiere_publicidad"></label> 
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3 col-xxl-2 mb-5">
                        <label class="form-label">Recordatorio SMS</label>
                        <div class="form-check form-check-solid form-switch form-check-custom fv-row">
                            <input class="form-check-input w-45px h-30px" type="checkbox" id="recordatorio_sms" name="recordatorio_sms" value="1" <?php if (isset($registros[0]['recordatorio_sms'])) {if ($registros[0]['recordatorio_sms'] == 1) { echo "checked";}} ?>>
                            <label class="form-check-label" for="recordatorio_sms"></label> 
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3 col-xxl-2 mb-5">
                        <label class="form-label">Recordatorio Email</label>
                        <div class="form-check form-check-solid form-switch form-check-custom fv-row">
                            <input class="form-check-input w-45px h-30px" type="checkbox" id="recordatorio_email" name="recordatorio_email" value="1" <?php if (isset($registros[0]['recordatorio_email'])) {if ($registros[0]['recordatorio_email'] == 1) { echo "checked";}} ?>>
                            <label class="form-check-label" for="recordatorio_email"></label> 
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3 col-xxl-2 mb-5">
                        <label class="form-label">Activo</label>
                        <div class="form-check form-check-solid form-switch form-check-custom fv-row">
                            <input class="form-check-input w-45px h-30px" type="checkbox" id="activo" name="activo" value="1" <?php if (isset($registros[0]['activo'])) {if ($registros[0]['activo'] == 1) { echo "checked";}} ?>>
                            <label class="form-check-label" for="activo"></label> 
                        </div>
                    </div>


                    <div class="col-md-6 col-lg-3 col-xxl-2 mb-5">
                        <label class="form-label">Cómo nos conoció  (*)</label>
                        <select name="como_conocio" id="como_conocio" class="form-control form-control-solid" data-control="select2" required>
                            <option value="">Indica una opción</option>
                            <option value="Redes sociales" <?php if (isset($registros[0]['como_conocio'])) {if ($registros[0]['como_conocio'] == 'Redes sociales') { echo "selected";}} ?>>Redes sociales</option>
                            <option value="Busqueda en Google" <?php if (isset($registros[0]['como_conocio'])) {if ($registros[0]['como_conocio'] == "Busqueda en Google") { echo "selected";}} ?>>Búsqueda en Google</option>
                            <option value="Referido" <?php if (isset($registros[0]['como_conocio'])) {if ($registros[0]['como_conocio'] == "Referido" ) { echo "selected";}} ?>>Referido</option>
                            <option value="Prensa o radio" <?php if (isset($registros[0]['como_conocio'])) {if ($registros[0]['como_conocio'] == "Prensa o radio") { echo "selected";}} ?>>Prensa o radio</option>
                            <option value="Web" <?php if (isset($registros[0]['como_conocio'])) {if ($registros[0]['como_conocio'] == "Web") { echo "selected";}} ?>>Web</option>
                            <option value="Conocidos" <?php if (isset($registros[0]['como_conocio'])) {if ($registros[0]['como_conocio'] == "Conocidos") { echo "selected";}} ?>>Conocidos</option>
                            <option value="Folletos" <?php if (isset($registros[0]['como_conocio'])) {if ($registros[0]['como_conocio'] == "Folletos") { echo "selected";}} ?>>Folletos</option>
                            <option value="Otros medios" <?php if (isset($registros[0]['como_conocio'])) {if ($registros[0]['como_conocio'] == "Otros medios") { echo "selected";}} ?>>Otros medios</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-5 border-bottom">
                    <div class="col-12">
                        <h3>Datos de Facturación</h3>
                    </div>
                    <div class="col-md-6 col-lg-3 col-xxl-2 mb-5">
                        <label class="form-label">Nombre o Empresa</label>
                        <input name="empresa" class="form-control form-control-solid" type="text" value="<?php if (isset($registros)) { echo $registros[0]['empresa'];} ?>" />
                    </div>
                    <div class="col-md-6 col-lg-3 col-xxl-2 mb-5">
                        <label class="form-label">CIF o NIF</label>
                        <input name="cif_nif" class="form-control form-control-solid" type="text" value="<?php if (isset($registros)) {echo $registros[0]['cif_nif'];} ?>" />
                    </div>
                    <div class="col-md-6 col-xl-4">
                        <label class="form-label">Dirección</label>
                        <input name="direccion_facturacion" class="form-control form-control-solid" type="text" value="<?php if (isset($registros)) { echo $registros[0]['direccion_facturacion'];} ?>" />
                    </div>
                    <div class="col-md-6 col-lg-3 col-xxl-2 mb-5">
                        <label class="form-label">Código Postal</label>
                        <input name="codigo_postal_facturacion" class="form-control form-control-solid" type="text" value="<?php if (isset($registros)) { echo $registros[0]['codigo_postal_facturacion'];} ?>" />
                    </div>
                    <div class="col-md-6 col-lg-3 col-xxl-2 mb-5">
                        <label class="form-label">Localidad</label>
                        <input name="localidad_facturacion" class="form-control form-control-solid" type="text" value="<?php if (isset($registros)) {echo $registros[0]['localidad_facturacion']; } ?>" />
                    </div>
                    <div class="col-md-6 col-lg-3 col-xxl-2 mb-5">
                        <label class="form-label">Provincia</label>
                        <input name="provincia_facturacion" class="form-control form-control-solid" type="text" value="<?php if (isset($registros)) {echo $registros[0]['provincia_facturacion'];} ?>" />
                    </div>
                </div>
                <?php if ($this->session->userdata('id_perfil') == 0) { ?>
                    <div class="row mb-5 border-bottom">
                        <div class="col-12">
                            <h3>Notificaciones Personalizadas</h3>
                        </div>
                        <div class="col-12 mb-5">
                            <textarea class="form-control form-control-solid" id="notificaciones" name="notificaciones"><?php if (isset($registros[0]['notificaciones'])) { echo $registros[0]['notificaciones'];} ?></textarea>
                        </div>
                    </div>
                    <script type="text/javascript" src="<?=base_url()?>assets_v5/plugins/custom/tinymce/tinymce.bundle.js"></script>
                    <script>
                        tinymce.init({
                            selector: 'textarea#notificaciones',
                            language_url: '<?=base_url()?>assets_v5/plugins/custom/tinymce/langs/es.js',
                            language: 'es',
                            menubar: false,
                        });
                    </script>
                    <script>
                        $("iframe").attr("title", "");
                    </script>
                <?php } ?>
                <div class="row">
                    <div class="col-md-12">
                        <button class="btn btn-primary text-inverse-primary" type="submit">Guardar</button>
                    </div>
                </div>
            </form>
    </div>
</div>
<script>
    function calcEdad() {
        var fecha = new Date(document.getElementById("fecha_nacimiento").value);
         //calculate month difference from current date in time
    var month_diff = Date.now() - fecha.getTime();
    
    //convert the calculated difference in date format
    var age_dt = new Date(month_diff); 
    
    //extract year from date    
    var year = age_dt.getUTCFullYear();
    
    //now calculate the age of the user
    var age = Math.abs(year - 1970);
    
    //display the calculated age
    //document.write("Age of the date entered: " + age + " years");
    if (age<18){
        alert ('Edad: '+age+' años, rellene los datos del tutor');
         document.getElementById('divTutor').style.display="block";
         document.getElementById('divTutor').style.display="flex";
    }
    else{
        document.getElementById('divTutor').style.display="none";
    }
    
    }
</script>