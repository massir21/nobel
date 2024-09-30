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
                 </head>
        <body class=" login">
                <div class="menu-toggler sidebar-toggler"></div>
                <!-- END SIDEBAR TOGGLER BUTTON -->
                <!-- BEGIN LOGO -->
                <div class="logo">
    <a href="">
	<img src="https://tienda.templodelmasaje.com/wp-content/uploads/2016/11/logo_trans.png" width="200px"> </a>
</div>
                <!-- END LOGO -->
                <!-- BEGIN LOGIN -->
                <div class="content">
                        <!-- BEGIN LOGIN FORM -->
                        <form class="login-form" action="<?php echo base_url();?>acceso/validar" method="post">                        
                                <h3 class="form-title" style="color: #ffffff;">Extranet</h3>
                                <div class="alert alert-danger display-hide">
                                        <div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center"></div>
                <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                <i class="fa-times fas fs-3 text-primary"></i>
        </button>
                                        <span> Datos de acceso incorrectos </span>
                                </div>
                                <div class="form-group">
                                        <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                                        <label class="control-label visible-ie8 visible-ie9">Usuario:</label>
                                        <input class="form-control form-control-solid placeholder-no-fix" type="text" autocomplete="off" placeholder="Usuario" name="usuario" /> </div>
                                <div class="form-group">
                                        <label class="control-label visible-ie8 visible-ie9">Contraseña:</label>
                                        <input class="form-control form-control-solid placeholder-no-fix" type="password" autocomplete="off" placeholder="Contraseña" name="password" /> </div>
                                <div class="form-actions" style="text-align: center;">
                                        <button type="submit" class="btn btn-success text-inverse-success uppercase">
                                            Entrar
                                            <i class="m-icon-swapright m-icon-white"></i>
                                        </button>
                                        <!--<label class="rememberme check">-->
                                        <!--<input type="checkbox" name="remember" value="1" />Remember </label>-->
                                        <!--<a href="javascript:;" id="forget-password" class="forget-password">¿Olvidó su contraseña?</a>-->
                                </div>
                                <?php if ($error == 1) { ?>
                                    <div class="alert alert-danger display-hide" style="display: block; text-align: center;">
                                        Acceso Incorrecto
                                    </div>
                                <?php } ?>
                                <!--<div class="login-options">
                                        <h4>Or login with</h4>
                                        <ul class="social-icons">
                                                <li>
                                                        <a class="social-icon-color facebook" data-original-title="facebook" href="javascript:;"></a>
                                                </li>
                                                <li>
                                                        <a class="social-icon-color twitter" data-original-title="Twitter" href="javascript:;"></a>
                                                </li>
                                                <li>
                                                        <a class="social-icon-color googleplus" data-original-title="Goole Plus" href="javascript:;"></a>
                                                </li>
                                                <li>
                                                        <a class="social-icon-color linkedin" data-original-title="Linkedin" href="javascript:;"></a>
                                                </li>
                                        </ul>
                                </div>
                                <div class="create-account">
                                        <p>
                                                <a href="javascript:;" id="register-btn btn-warning text-inverse-warning" class="uppercase">Create an account</a>
                                        </p>
                                </div>-->
                        </form>
                        <!-- END LOGIN FORM -->
                        <!-- BEGIN FORGOT PASSWORD FORM -->
                        <form class="forget-form" action="index.html" method="post">
                                <h3 class="font-green">¿Olvidó su contraseña?</h3>
                                <p> Introduce tu cuenta de email asociada y recibirás tu contraseña.</p>
                                <div class="form-group">
                                        <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Email" name="email" /> </div>
                                <div class="form-actions">
                                        <button type="button" id="back-btn btn-warning text-inverse-warning" class="btn btn-default">Volver</button>
                                        <button type="submit" class="btn btn-yellow uppercase pull-right">Enviar</button>
                                </div>
                        </form>
                        <!-- END FORGOT PASSWORD FORM -->                        
                </div>
                <div class="copyright"> 2019 © Templo del Masaje</div>
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
        </body>
</html>