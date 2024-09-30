<?php

?>

<script type="text/javascript">
    jQuery(document).ready(function(){
        $(document).on('click', '[data-add-justificante]', function() {
            var id_dietario = $(this).attr('data-add-justificante');
            var id_presupuesto = $(this).attr('data-presupuesto-justificante');
            const modalContent = `
                <div class="modal modal-lg fade" id="modalFormularioPago" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index:99999;">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Justificante de operación de pago</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="justificante-pago">
									<div class="mb-3">
										<label for="comisionfinanciacion" class="form-label">Gastos de transacción</label>
										<input type="number" class="form-control" id="comisionfinanciacion" name="comisionfinanciacion" required>
										<span class="text-muted" id="comisionfinanciacion"></span>
									</div>

                                    <div class="mb-3" >
                                            <label for="presupuestos" class="form-label">Presupuestos</label>
                                            <div class="row" id="modaldiv-presupuestos">
                                            </div>
                                    </div>


									<div class="mb-3">
										<label for="formFile" class="form-label">Justificante de pago</label>
										<input class="form-control" type="file" id="fileToUpload" name="fileToUpload">
										<span class="text-muted" id="comisionfinanciacion"></span>
									</div>
                                    <button type="button" class="btn btn-primary" id="enviar_justificante">Enviar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            $("body").append(modalContent);


            $("#modalFormularioPago").on('shown.bs.modal', function () {
                jQuery("#modaldiv-presupuestos").empty();
                $.get('<?php echo base_url(); ?>Presupuestos/get_jsonpresupuestos', {
                        id_cliente: 0,
                        id_dietario: id_dietario,
                        id_presupuesto: id_presupuesto,
                        estado: 'Aceptado'
                    },
                    function(data, status){
                        console.log(data);
                        if(typeof data.presupuestos != "undefined"){
                            for(var i=0;i<data.presupuestos.length;i++){
                                var xchecked='';
                                if(id_presupuesto==data.presupuestos[i].id_presupuesto) xchecked=' checked="checked" disabled ';
                                var c='<div class="col-md-6">'+
                                    '<div class="form-check form-check-solid form-switch form-check-custom fv-row"> '+
                                    '<input class="form-check-input w-30px h-15px" type="checkbox" id="gastosfinanciacion_'+
                                    data.presupuestos[i].id_presupuesto+'" '+
                                    ' name="presupfinanciacion['+data.presupuestos[i].id_presupuesto+']" '+
                                    ' value="1" '+
                                    xchecked+
                                    '> '+
                                    ' <label class="form-check-label" for="solo_este_empleado">Presupuesto '+data.presupuestos[i].nro_presupuesto+
                                    ' ('+data.presupuestos[i].total+'€)</label> '+
                                    '</div>';
                                if(id_presupuesto==data.presupuestos[i].id_presupuesto){
                                    jQuery('<input type="hidden" name="presupfinanciacion['+data.presupuestos[i].id_presupuesto+']"  value="1" />').appendTo(jQuery("#modaldiv-presupuestos"));
                                }
                                jQuery(c).appendTo(jQuery("#modaldiv-presupuestos"));
                            }
                        }
                        else{
                            jQuery('<div class="mb-3"><p>No hay presupuestos disponibles</p></div>').appendTo(jQuery("#modaldiv-presupuestos"));
                        }
                    },'json');
            });


            $("#modalFormularioPago").modal('show');
            $('.modal-backdrop.fade.show').attr('style', 'z-index:9999;')

            $("#enviar_justificante").click(function(event) {
                console.log(id_dietario, id_presupuesto);
                if ($('#comisionfinanciacion').val() <= 0) {
                    Swal.fire({
                        title: 'Gastos de operación de pago',
                        html: `¿Seguro que la operación no tiene gastos asociados? El campo gastos esta vacío y se guardará con valor 0`,
                        showCancelButton: true,
                        confirmButtonText: 'Si, enviar sin gastos',
                        showLoaderOnConfirm: true,
                        onBeforeOpen: () => {
                            $(".swal2-file").change(function() {
                                var reader = new FileReader();
                                reader.readAsDataURL(this.files[0]);
                            });
                        },

                    }).then((result) => {
                        if (result.value) {
                            enviarjustificante(id_dietario, id_presupuesto)
                        }
                    })
                } else {
                    enviarjustificante(id_dietario, id_presupuesto)
                }
            });

            function enviarjustificante(id_dietario, id_presupuesto) {
                console.log(id_dietario, id_presupuesto);
                var formData = new FormData(document.getElementById('justificante-pago'));
                formData.append("id_dietario", id_dietario);
                formData.append("id_presupuesto", id_presupuesto);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'post',
                    url: '<?php echo base_url() ?>Presupuestos/cargarJustificante',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(resp) {
                        console.log(resp);
                        if (resp == true) {
                            Swal.fire({
                                title: 'Cargado',
                                html: 'El archivo ha sido cargado',
                                type: 'success',
                                willClose: function() {
                                    $("#modalFormularioPago").modal('hide');
                                    window.location.reload();
                                },
                            });
                        } else {
                            Swal.fire({
                                title: 'Error',
                                type: 'error',
                                willClose: function() {
                                    //window.location.reload();
                                },
                            });
                        }

                    },
                    error: function() {
                        Swal.fire({
                            type: 'error',
                            title: 'Oops...',
                            text: 'Ha ocurrido un error'
                        })
                    }
                })
            }

            $("#modalFormularioPago").on("hidden.bs.modal", function() {
                $('.modal-backdrop.fade.show').removeAttr('style')
                $(this).remove();
            });
        });

    });
</script>
