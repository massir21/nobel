<?php $this->load->view($this->config->item('template_dir') . '/head'); ?>

<body class="p-5">
    <div class="card card-flush m-5">
        <div class="card-body p-5">
            <h3 class="text-center">EDITAR TAREA</h3>
            <form id="form_tarea" action="<?php echo base_url();?>avisos/editar_tarea/guardar" role="form" method="post" name="form_tarea" >
                <div class="row align-items-end">
                    <div class="col-12 mb-5">
                        <label for="" class="form-label">Para quienes</label>
                        <select name="quienes[]" id="quienes" class="form-select form-select-solid" data-control="select2" data-placeholder="Elegir ..." multiple data-live-search="true">
                            <?php if (isset($empleados)) {
                                if ($empleados != 0) {
                                    foreach ($empleados as $key => $row) { 
										$seleccionado = (in_array($row['id_usuario'], $asignados)) ? 'selected' : '';?>
                                        <option value='<?php echo $row['id_usuario']; ?>' <?=$seleccionado?>>
                                            <?php echo strtoupper($row['apellidos'].", ".$row['nombre']); ?>
                                        </option>
                                    <?php }
                                }
                            } ?>    
                        </select>
                    </div>
                    
                    <div class="col-12 mb-5">
                        <label for="" class="form-label">Título</label>
                        <input type="text" class="form-control form-control-solid" name="titulo" id="titulo" placeholder="Título" value="<?php echo $tarea[0]['titulo']; ?>" required/>
                    </div>

                    <div class="col-12 mb-5">
                        <label for="" class="form-label">Fecha ejecución</label>
                        <input type="date" class="form-control form-control-solid" name="fecha_ejecucion" id="fecha_ejecucion" value="<?php echo $tarea[0]['fecha_ejecucion']; ?>" required/>
                    </div>
                
                    <div class="col-12 mb-5">
                        <label for="" class="form-label">Descripción de la tarea</label>
                        <textarea name="contenido" id="contenido" class="form-control form-control-solid" required rows="5"><?php echo $tarea[0]['contenido']; ?></textarea>
                    </div>

					<div class="col-12 mb-5">
                        <label for="" class="form-label">Estado de la tarea</label>
                        <select name="estado" id="estado" class="form-select form-select-solid">
                            <option value="Pendiente" <?php if ($tarea[0]['estado']=="Pendiente"){echo "selected"; } ?> >Pendiente</option>
                            <option value="Finalizado" <?php if ($tarea[0]['estado']=="Finalizado"){echo "selected"; } ?>>Finalizado</option>
                        </select>
                    </div>

					<input type="hidden" name="id_tarea" value="<?=$tarea[0]['id']?>" >
                </div>

                <div class="row">
                    <div class="col-md-12 text-center">
                        <button class="btn btn-sm btn-primary text-inverse-primary" type="submit">Guardar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        function Cerrar() {                
            window.opener.location.reload();        
            window.close();
        }        
        <?php if (isset($accion) && $accion == "realizar") { ?>
            Cerrar();
        <?php } ?>
        Mostrar();
    </script>
</body>
</html>