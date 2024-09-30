<?php $this->load->view($this->config->item('template_dir') . '/head'); ?>

<body class="p-5">
    <div class="card card-flush m-5">
        <div class="card-body p-5 mb-5">
            <h3 class="text-center text-uppercase">Registros en la  TAREA</h3>
            <?php
            if(!is_array($iteraciones) || count($iteraciones)==0){
                echo "No hay registros en la tarea";
            }
            else{
            ?>
            <div class="table-responsive p-4 mb-5 border">
                <table class="table align-middle table-striped table-row-dashed fs-6 gy-5">
                    <thead class="">
                        <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                            <th>Fecha</th>
                            <th>Usuario</th>
                            <th>Comentario</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 fw-semibold">
                        <?php foreach ($iteraciones as $key => $row) { ?>
                            <tr>
                                <td><?php echo $row['fecha_creacion'] ?></td>
                                <td><?php echo $row['nombre']." ".$row['apellidos'] ?></td>
                                <td><?php echo $row['comentario'] ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <?php
            }
            ?>
            <hr>
            <h3 class="text-center text-uppercase">Añadir registro</h3>
            <form id="form_tarea" action="<?php echo base_url();?>avisos/iteraciones_tareas/grabar" role="form" method="post" name="form_tarea" >
                <div class="row align-items-end">
                    <div class="col-12 mb-5">
                        <label for="" class="form-label">Comentario</label>
                        <textarea name="comentario" id="comentario" class="form-control form-control-solid" required></textarea>
                    </div>
                    <div class="col-12 mb-5">
                        <label for="" class="form-label">Estado actual de la tarea</label>
                        <select name="estado" id="estado" class="form-select form-select-solid">
                            <option value="Pendiente" <?php if ($tarea[0]['estado']=="Pendiente"){echo "selected"; } ?> >Pendiente</option>
                            <option value="Finalizado" <?php if ($tarea[0]['estado']=="Finalizado"){echo "selected"; } ?>>Finalizado</option>
                        </select>
                    </div>
                    <input type="hidden" name="id_tarea" value="<?php echo $id_tarea; ?>" >
                </div>

                <div class="row mb-5">
                    <div class="col-md-12 text-center">
                        <button class="btn btn-sm btn-primary text-inverse-primary" type="submit">Registrar</button>
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