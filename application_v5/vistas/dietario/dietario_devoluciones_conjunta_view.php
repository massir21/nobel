<?php $this->load->view($this->config->item('template_dir') . '/head'); ?>

<body class="p-5">

    <h1 class="fs-2x fw-bolder my-0 text-center text-uppercase">DEVOLUCIÓN CONJUNTA</h1>
    <?php
    if($accion=='error'){
        ?>
        <div class="alert alert-danger d-flex align-items-center p-5" style="margin-top:10px">
            <div class="d-flex flex-column">
                <h4 class="mb-1 text-danger">No se puede realizar la devolucón</h4>
                <span><?php echo $errorDevolucion;?></span>
            </div>
        </div>
    <?php
    }
    ?>
    
    <?php if ( $accion == 'listar'){ ?>
    <div class="card card-flush m-5">
        
        <div class="card-body p-5">
            <form id="form_devolver" action="<?php echo base_url(); ?>dietario/devolucion_conjunta/realizar" role="form" method="post" name="form_devolver">
                <div class="row">
                    <div class="col-3">
                        <strong>Cliente</strong>
                    </div>
                    <div class="col-3">
                        <strong>Servicio o Producto</strong>
                    </div>
                    <div class="col-3">
                        <strong>Forma devolución</strong>
                    </div>
                    <div class="col-3">
                        <strong>Importe en euros</strong>
                    </div>
                </div>
                <?php foreach ( $lineas_devolucion as $dietario ) { ?>
                <div class="row">    
                    <div class="col-3">
                        <?php echo $dietario[0]['cliente']; ?>
                    </div>
                    <div class="col-3">
                        <?php
                        $mostrarProducto=true;
                        $mostrarServicio=true;
                        $mostrarSaldo=true;
                        if(isset($dietario)){
                            $mostrarProducto=true && ((intval($dietario[0]['id_producto']) > 0));
                            $mostrarServicio=true && (intval($dietario[0]['id_servicio'] )> 0);
                            $mostrarSaldo=!$mostrarServicio && !$mostrarProducto && true && $dietario[0]['pago_a_cuenta']=1;
                        }
                        
                        if($mostrarProducto){
                            echo ucfirst($dietario[0]['producto']);
                            echo '<input type="hidden" name="que_devolver_'.$dietario[0]['id_dietario'].'" value="1" />';
                        }
                        if($mostrarServicio){
                            echo ucfirst($dietario[0]['servicio']);
                            echo '<input type="hidden" name="que_devolver_'.$dietario[0]['id_dietario'].'" value="2" />';
                        }
                        if($mostrarSaldo){
                            echo 'Pago a cuenta';
                            echo '<input type="hidden" name="que_devolver_'.$dietario[0]['id_dietario'].'" value="3" />';
                        }
                        ?>
                    </div>
                    <div class="col-3">
                        <select id="forma_pago_<?php echo $dietario[0]['id_dietario']; ?>" name="forma_pago_<?php echo $dietario[0]['id_dietario']; ?>" data-placeholder="Elegir ..." class="form-select form-select-solid" tabindex="-1" aria-hidden="true" required>
                            <option value="#efectivo">Efectivo</option>
                            <option value="#tarjeta">Tarjeta</option>
                            <option value="#transferencia">Transferencia</option>
                            <option value="#tpv2">TPV2</option>
                            <option value="#saldo_cuenta" selected>Saldo Cliente</option>
                            <?php if ($this->session->userdata('id_centro_usuario') == 9) { ?>
                                <option value="#habitacion">Habitación</option>
                            <?php } ?>
                            <option value="#templos">Templos</option>
                            <?php if ($codigo_carnet_especial != "") { ?>
                                <option value="#especial">Carnet Especial</option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-3">
                        <input id="importe_devolver_<?php echo $dietario[0]['id_dietario']; ?>" type="number" step="0.01" min="0" name="importe_devolver_<?php echo $dietario[0]['id_dietario']; ?>" class="form-control form-control-solid"
                        <?= ($dietario[0]['importe_total_final'] > 0) ? 'value="' . $dietario[0]['importe_total_final'] . '"' :
                            ( $dietario[0]['importe_euros'] > 0  ? 'value="'.$dietario[0]['importe_euros'].'"' : "" ) ?> required />
                    </div>
                </div>
                <?php } ?>
                
                <div style="margin-top: 10px;" id="importe">
                    <div class="row mb-5 align-items-end">
                        <div class="col-md-12">
                            <label><b>Motivo de la Devolución</b></label>
                            <textarea id="motivo_devolucion" name="motivo_devolucion" class="form-control form-control-solid" required><?php
                                echo isset($original_param['motivo_devolucion']) ? $original_param['motivo_devolucion'] :'' ;
                            ?></textarea>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <button class="btn btn-sm btn-secondary text-inverse-secondary m-2" type="button" onclick="window.close();">Cerrar sin Cambios</button>
                            <button class="btn btn-sm btn-primary text-inverse-primary" type="submit">Realizar Devolución</button>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="ids" value="<?php echo $ids ?>" />
            </form>
        </div>
    </div>
    <?php } ?>
    
    <?php if ($accion == "realizar") { ?>
    <script>
        window.opener.location.reload();
        window.close();
    </script>
    <?php } ?>  
</body>

<?php echo '</html>';