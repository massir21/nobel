<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <!-- BEGIN PAGE BAR -->
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="<?php echo base_url();?>site" style="font-size: 20px;">Panel de Control</a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <span style="font-size: 20px;"><strong>Exportación de Clientes</strong></span>
            </li>
        </ul>
    </div>
    <!-- END PAGE BAR -->
    <!-- END PAGE HEADER-->
    <div class="row ">
        <div class="col-md-12">
            <!-- BEGIN SAMPLE FORM PORTLET-->
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption font-dark">
                        <i class="icon-settings font-dark"></i>
                        <span class="caption-subject bold uppercase"> Exportación de Clientes</span>
                    </div>
                </div>
                <div class="card-body pt-6">
        <div class="table-responsive">
                    <div class="row">
                        <div class="col-sm-12" style="margin-bottom: 20px;">
                            <div style="float: right;">
                                <input type="checkbox" class="checkAll" name="checkAll" />
                                    Todos
                            </div>
                            <h4>Selecciona los grupos de datos a exportar</h4>
                        </div>
                <form id="form_buscar" action="<?php echo base_url();?>clientes/exportar_csv" role="form" method="post" name="form_buscar">
                        <div class="col-sm-4">
                            <table class="table table-striped table-hover table-bordered">
                                <tbody class="text-gray-700 fw-semibold">
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="nombre" value="SI">
                                            Nombre
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="apellidos" value="SI">
                                            Apellidos
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="email" value="SI">
                                            Email
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="telefono" value="SI">
                                            Teléfono
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="direccion" value="SI">
                                            Dirección
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="codigo_postal" value="SI">
                                            Código postal
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-sm-4">
                            <table class="table table-striped table-hover table-bordered">
                                <tbody class="text-gray-700 fw-semibold">
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="fecha_nacimiento_ddmmaaaa" value="SI">
                                            Fecha de nacimiento
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="fecha_creacion_ddmmaaaa" value="SI">
                                            Fecha alta
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="no_quiere_publicidad" value="SI">
                                            Quiere publicidad
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="password" value="SI">
                                            Con Password
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="activo" value="SI">
                                            Verficado
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="fecha_activacion" value="SI">
                                            Fecha activacion
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-sm-4">
                            <table class="table table-striped table-hover table-bordered">
                                <tbody class="text-gray-700 fw-semibold">
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="fecha_modificacion" value="SI">
                                            Fecha modificacion
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="ultimo_centro_visitado" value="SI">
                                            Último centro visitado
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="fecha_ultima_reserva" value="SI">
                                            Fecha ultima reserva
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="fecha_ultimo_login" value="SI">
                                            Fecha ultimo login
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="fecha_ultima_cita_abandonada" value="SI">
                                            Fecha ultima cita abandonada
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="numero_citas_abandonadas" value="SI">
                                            Número citas abandonadas
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
                <input type="submit" value="Exportar selección" class="btn btn-info text-inverse-info" />
            </form>
        </div>
    </div>
</div>
<!-- END SAMPLE FORM PORTLET-->
</div>
</div>
</div>
<!-- END CONTENT BODY -->
<script type="text/javascript">
$('.checkAll').on('click', function () {
    $(this).closest('body').find('tbody :checkbox')
      .prop('checked', this.checked)
      .closest('span').toggleClass('checked', this.checked);
  });
 $('td').on('click', function() {
    if (!$(event.target).is('input')) {
        //    $('input:checkbox', this).prop('checked', function (i, value) {
        //     return !value;
        //    });
        $(this).find('input:checkbox').prop('checked', this.checked)
        .closest('span').toggleClass('checked', this.checked);
    }
 })
</script>
