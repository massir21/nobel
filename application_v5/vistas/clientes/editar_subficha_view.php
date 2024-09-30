<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="content-type" content="text/html" />
	<meta name="author" content="teo_app" />
	<title><?= SITETITLE ?></title>
    <link href="https://extranet.templodelmasaje.com/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
            <script src="https://extranet.templodelmasaje.com/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
        <script src="https://extranet.templodelmasaje.com/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
                <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="https://extranet.templodelmasaje.com/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="https://extranet.templodelmasaje.com/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="https://extranet.templodelmasaje.com/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="https://extranet.templodelmasaje.com/assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css" />
        <link href="https://extranet.templodelmasaje.com/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
        <link href="https://extranet.templodelmasaje.com/assets/global/plugins/fullcalendar/fullcalendar.min.css" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="https://extranet.templodelmasaje.com/assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="https://extranet.templodelmasaje.com/assets/global/css/components-rounded.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="https://extranet.templodelmasaje.com/assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
         </head>
        <link rel="stylesheet" type="text/css" href="https://extranet.templodelmasaje.com/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css"/>
        <link rel="stylesheet" type="text/css" href="https://extranet.templodelmasaje.com/assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css"/>
        <link rel="stylesheet" type="text/css" href="https://extranet.templodelmasaje.com/assets/global/plugins/bootstrap-colorpicker/css/colorpicker.css"/>
        <link rel="stylesheet" type="text/css" href="https://extranet.templodelmasaje.com/assets/global/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css"/>
        <link rel="stylesheet" type="text/css" href="https://extranet.templodelmasaje.com/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css"/>
	<link href="https://extranet.templodelmasaje.com/assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="https://extranet.templodelmasaje.com/assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
	<script src="https://extranet.templodelmasaje.com/assets/global/plugins/respond.min.js"></script>
	<script src="https://extranet.templodelmasaje.com/assets/global/plugins/excanvas.min.js"></script>
        <!-- BEGIN CORE PLUGINS -->
        <!-- END CORE PLUGINS -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <script src="https://extranet.templodelmasaje.com/assets/global/scripts/datatable.js" type="text/javascript"></script>
        <script src="https://extranet.templodelmasaje.com/assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
        <script src="https://extranet.templodelmasaje.com/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
</head>
<body>
<div class="content" style="padding: 48px;">
<h3>Cliente: <?php echo $subficha[0]['cliente']; ?> </h3>
<div class="row">
                     <div class="col-md-10">
                     <form id="form_sub_ficha_editar" action="<?php echo RUTA_WWW ?>/clientes/modificar_subficha/<?php echo $subficha[0]['id'] ?>/<?php echo $subficha[0]['id_cliente'] ?>" role="form" method="post" name="form_sub_ficha_editar">
                             <div class="row mb-5 border-bottom">
                            <label for="selectFichas">Tratamientos</label>
                            <select class="form-control form-control-solid" id="selectFichas" name="selectFichas">
                              <option value="Tratamiento Facial" <?php if ($subficha[0]['tratamiento']=="Tratamiento Facial"){?> selected <?php } ?>  >Tratamiento Facial</option>
                              <option value="Tratamiento Corporal" <?php if ($subficha[0]['tratamiento']=="Tratamiento Corporal"){?> selected <?php } ?>>Tratamiento Corporal </option>
                              <option value="Acupuntura" <?php if ($subficha[0]['tratamiento']=="Acupuntura"){?> selected <?php } ?>>Acupuntura</option>
                            </select>
                          </div>
                            <div class="row mb-5 border-bottom">
                              <textarea style="width: 100%;" name="nota_ficha" id="editor" class="form-control form-control-solid" rows="6" style="height: 300px;" placeholder="Fichas"><?php echo $subficha[0]['contenido'] ?></textarea>
                          </div>
                                <div class="margiv-top-10">
                                    <button class="btn btn-primary text-inverse-primary" onclick="cerrar()">Actualizar</button>
                                </div>
                            </form>
                            </div>
                           </div> 
 </div>
</body>
</html>
<script type="text/javascript" src="https://extranet.templodelmasaje.com/recursos/tinymce/js/tinymce/tinymce.min.js"></script>  
<script>
    tinymce.init({
        selector:'textarea#editor',
        plugins: 'textcolor',
        toolbar: 'undo redo cut paste | fontselect fontsizeselect | bold italic underline forecolor backcolor  | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat',
        language: 'es', menubar:false,
    });
 function cerrar() { 
       // $("body").html('<div alignt="center"><h1>Actualizando!!!</h1></div>'); //Marca nuevo contenido con un mensaje que se envio exitosamente
        //setTimeout(function(){
            //window.close();
        //},3000); //Dejara un tiempo de 3 seg para que el usuario vea que se envio el formulario correctamente
        document.getElementById('form_sub_ficha_editar').submit();
          //window.close();
       setTimeout(function(){
          opener.window.location.reload(); 
          window.close();
       },3000);
    }   
</script>