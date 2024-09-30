<!DOCTYPE html>
<!--[if IE 8]> <html lang="es" class="ie8 no-js"> 
<!--[if IE 9]> <html lang="es" class="ie9 no-js"> 
<!--[if !IE]><!-->
<html lang="es">
        <!--
        <!-- BEGIN HEAD -->
        <head>
                <meta charset="utf-8" />
                <title><?= SITETITLE ?></title>
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta content="width=device-width, initial-scale=1" name="viewport" />
                <meta content="" name="description" />
                <meta content="" name="author" />
                <!-- BEGIN GLOBAL MANDATORY STYLES -->
                <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
                <link href="<?php echo base_url();?>assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
                <link href="<?php echo base_url();?>assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
                <link href="<?php echo base_url();?>assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
                <link href="<?php echo base_url();?>assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css" />
                <link href="<?php echo base_url();?>assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
                <!-- END GLOBAL MANDATORY STYLES -->
                <!-- BEGIN PAGE LEVEL PLUGINS -->
                <link href="<?php echo base_url();?>assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
                <link href="<?php echo base_url();?>assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
                <!-- BEGIN THEME GLOBAL STYLES -->
                <link href="<?php echo base_url();?>assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
                <link href="<?php echo base_url();?>assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
                <!-- END THEME GLOBAL STYLES -->
                <!-- BEGIN PAGE LEVEL STYLES -->
                <link href="<?php echo base_url();?>assets/pages/css/login.min.css" rel="stylesheet" type="text/css" />
                <!-- END PAGE LEVEL STYLES -->
                <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
                <style>
                        .control-label {
                                font-weight: bold;
                        }
                        #ui-datepicker-div {
                                width: 300px;
                        }
                        #datepicker::-webkit-input-placeholder {
                                color: red;                    
                        }                                                
                </style>
                <script type="text/javascript"> base_url = "<?php echo base_url();?>"</script>
        </head>
        <body class="login" style="background-color: #412210!important;">
                <div class="menu-toggler sidebar-toggler"></div>
                <!-- END SIDEBAR TOGGLER BUTTON -->
                <!-- BEGIN LOGO -->
                <!--<div class="logo">
                <a href="">
                        <img src="https://tienda.templodelmasaje.com/wp-content/uploads/2016/11/logo_trans.png" width="200px"> </a>
                </div>-->
                <!-- END LOGO -->
                <!-- BEGIN LOGIN -->
                <div id="contenido" class="content" style="background-color: #fff !important; padding: 10px;">
                        <?php if (isset($finalizado)) { ?>
                         <div class="row mb-5 border-bottom">
                                <div class="col-md-12" style="font-size: 18px;">
                                    <p style="text-align: center; color: green">
                                        <b>
                                                TU CITA HA SIDO ENVIADA CORRECTAMENTE
                                        </b>
                                    </p>
                                    <p>Nombre: <?php echo $nombre." ".$apellidos; ?></p>                                    
                                    <p>Teléfono: <?php echo $telefono; ?></p>
                                    <p>Servicio: <?php echo $que_servicio; ?></p>
                                    <p>Profesional: <?php echo $que_empleado; ?></p>
                                    <p>Fecha: <?php echo $fecha; ?></p>
                                    <p>Hora: <?php echo $horario; ?></p>
                                    <?php if ($observaciones != "") { ?>
                                    <p>Observaciones: <?php echo $observaciones; ?></p>
                                    <?php } ?>
                                    <p style="color: red; text-align: center;">
                                        <b>
                                        SI NECESITAS MODIFICAR O ANULAR TU CITA, POR FAVOR LLAMA AL 91 000 00 00. GRACIAS.
                                        </b>
                                    </p>
                                    <p style="text-align: center; padding-top: 20px;">
                                        <a href="/velazquez" class="btn btn-info text-inverse-info">FINALIZAR</a>
                                    </p>
                                </div>
                        </div>
                        <?php } else { ?>
                        <form accion="/velazquez" method="post" namr="form" id="form" onsubmit="return Guardar();">
                                 <div class="row mb-5 border-bottom">
                                        <div class="col-md-12">
                                            <label class="form-label">Nombre</label>
                                            <div class="input-icon">
                                                <i class="fa fa-user"></i>
                                                <input name="nombre" class="form-control form-control-solid" type="text" value="<?php if (isset($nombre)) { echo $nombre; } ?>" required />
                                            </div>
                                        </div>
                                </div>
                                 <div class="row mb-5 border-bottom">
                                        <div class="col-md-12">
                                            <label class="form-label">Apellidos</label>
                                            <div class="input-icon">
                                                <i class="fa fa-user"></i>
                                                <input name="apellidos" class="form-control form-control-solid" type="text" value="<?php if (isset($apellidos)) { echo $apellidos; } ?>" required/>
                                            </div>
                                        </div>
                                </div>
                                 <div class="row mb-5 border-bottom">
                                        <div class="col-md-12">
                                            <label class="form-label">Teléfono (9 digitos completos sin espacios)</label>
                                                <div class="input-icon">
                                                    <i class="fa fa-phone"></i>
                                                    <input type="tel" name="telefono" class="form-control form-control-solid" pattern="[0-9]{9}" value="<?php if (isset($telefono)) { echo $telefono; } ?>" required>
                                                </div>
                                        </div>
                                </div>
                                 <div class="row mb-5 border-bottom">
                                        <div class="col-md-12">
                                            <label class="form-label">¿Qué servicio necesitas?</label>                                        
                                                    <select id="id_servicio" name="id_servicio" class="form-control form-control-solid" onchange="Recarga('empleado');" required/>
                                                        <option value="">Elegir servicio ....</option>
                                                        <?php if ($servicios != 0) { foreach ($servicios as $key => $row) { ?>
                                                        <option value="<?php echo $row['id_servicio'] ?>" <?php if (isset($id_servicio)) { if ($id_servicio==$row['id_servicio']) { echo "selected"; } } ?>>
                                                                <?php echo $row['nombre_servicio'] ?>
                                                        </option>
                                                        <?php }} ?>
                                                    </select>                                        
                                        </div>
                                </div>
                                 <div class="row mb-5 border-bottom">
                                        <div class="col-md-12">
                                                <a name="empleado"></a>
                                            <label class="form-label">¿Quién quieres que te atienda?</label>                                        
                                                    <select id="id_empleado" name="id_empleado" class="form-control form-control-solid" onchange="Recarga('fecha');" required />
                                                        <option value="">Elegir profesional ...</option>
                                                        <?php if ($empleados != 0) { foreach ($empleados as $key => $row) { ?>
                                                        <option value="<?php echo $row['id_usuario'] ?>" <?php if (isset($id_empleado)) { if ($id_empleado==$row['id_usuario']) { echo "selected"; } } ?>>
                                                                <?php echo $row['nombre']." ".$row['apellidos']; ?>
                                                        </option>
                                                        <?php }} ?>
                                                    </select>                                        
                                        </div>
                                </div>
                                 <div class="row mb-5 border-bottom">
                                        <div class="col-md-12">
                                                <a nmae="fecha"></a>
                                            <label class="form-label">¿Qué día quieres tu cita?</label>
                                                <div class="input-icon">
                                                    <i class="fa fa-calendar"></i>
                                                    <input type="text" name="fecha" id="datepicker" class="form-control form-control-solid" value="<?php if (isset($fecha)) { echo $fecha; } ?>" onchange="Recarga('hora');" placeholder="Pulsa aquí, para elegir la fecha de la cita..." readonly>
                                                </div>
                                        </div>
                                </div>
                                 <div class="row mb-5 border-bottom">
                                        <div class="col-md-12">
                                            <label class="form-label">¿A qué hora?</label>
                                                        <a name="hora"></a>
                                                    <select id="horario" name="horario" class="form-control form-control-solid" <?php if ($horarios == 0) { ?>style="color: red;"<?php } ?>required />
                                                        <?php if ($horarios == 0) { ?>                                                
                                                                <option value="">No hay horarios disponibles con los datos indicados</option>
                                                        <?php } ?>
                                                        <?php if ($horarios != 0) { foreach ($horarios as $key => $row) { ?>
                                                        <option value="<?php echo $row ?>" <?php if (isset($horario)) { if ($horario==$row) { echo "selected"; } } ?>>
                                                                <?php echo $row; ?>
                                                        </option>
                                                        <?php }} ?>                                                
                                                    </select>                                        
                                        </div>
                                </div>
                                 <div class="row mb-5 border-bottom">
                                        <div class="col-md-12">
                                                <label class="form-label">Observaciones</label>
                                                <textarea name="observaciones" class="form-control form-control-solid"><?php if (isset($observaciones)) { echo $observaciones; } ?></textarea>
                                        </div>
                                </div>
                                 <div class="row mb-5 border-bottom">
                                        <div class="col-md-12">
                                                <center>
                                                        <input type="submit" value="PEDIR CITA" class="btn btn-primary text-inverse-primary" />
                                                </center>
                                        </div>
                                </div>
                                <input type="hidden" name="pedir_cita" id="pedir_cita" value="" />
                        </form>
                        <?php } ?>
                </div>
                <div class="copyright"> 2017 © Templo del Masaje</div>
                <!-- BEGIN CORE PLUGINS -->
                <script src="<?php echo base_url();?>assets/global/plugins/jquery.min.js" type="text/javascript"></script>
                <script src="<?php echo base_url();?>assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
                <script src="<?php echo base_url();?>assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
                <script src="<?php echo base_url();?>assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
                
                <script src="<?php echo base_url();?>assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
                <script src="<?php echo base_url();?>assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
                <script src="<?php echo base_url();?>assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
                <!-- END CORE PLUGINS -->
                <!-- BEGIN PAGE LEVEL PLUGINS -->
                <script src="<?php echo base_url();?>assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
                <script src="<?php echo base_url();?>assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
                <script src="<?php echo base_url();?>assets/pages/scripts/login.min.js" type="text/javascript"></script>
                <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
                <script>
                $.datepicker.regional['es'] = {
                        closeText: 'Cerrar',
                        prevText: '<Ant',
                        nextText: 'Sig>',
                        currentText: 'Hoy',
                        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                        monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
                        dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
                        dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
                        dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
                        weekHeader: 'Sm',
                        dateFormat: 'dd/mm/yy',
                        firstDay: 1,
                        isRTL: false,
                        showMonthAfterYear: false,
                        yearSuffix: ''
                };
                $( function() {
                        $( "#datepicker" ).datepicker(
                                {
                                        minDate: -0,
                                        maxDate: "+1M",
                                        beforeShowDay: function(date) {
                                                return [date.getDay() != 0,''];
                                        }
                                }                                
                        );
                } );
                function Recarga(tag) {
                        document.getElementById("form").action = "/velazquez#hora";
                        document.getElementById("form").submit();                        
                }
                function Guardar() {
                        var s = document.getElementById("id_servicio");
                        var servicio = s.options[s.selectedIndex].text;
                        var e = document.getElementById("id_empleado");
                        var empleado = e.options[e.selectedIndex].text;
                        var fecha = document.getElementById("datepicker").value;
                        var h = document.getElementById("horario");
                        var hora = h.options[h.selectedIndex].text;
                        if (confirm("HAS ELEGIDO SERVICIO: "+servicio.toUpperCase()+ "\n\nTE ATENDERÁ: "+empleado.toUpperCase()+"\n\nFECHA: "+fecha+"\n\nHORA: "+hora+"\n\n¿DESEAS CONFIRMAR LA CITA?\n")) {
                                document.getElementById("pedir_cita").value=1;
                                document.getElementById("form").action = "/velazquez";
                                return true;
                        }
                        else {
                                return false;
                        }                        
                }                
                </script>
        </body>
</html>