<div class="card card-flush">
    <form id="form_carnets" action="#" role="form" method="post" name="form_carnets">
        <div class="card-header align-items-end py-5 gap-2 gap-md-5">
            <div class="card-title w-100 align-items-end">
                <div class="w-75 ms-3">
                    <label for="" class="form-label">Elige los servicios:</label>
                    <select name="id_servicio" id="servicioListado" class="form-select form-select-solid w-auto" data-control="select2" data-placeholder="Elegir ...">
                        <option value=""></option>
                        <?php if (isset($servicios)) {
                            if ($servicios != 0) {
                                foreach ($servicios as $key => $row) { ?>
                                    <option value="<?php echo $row['id_servicio']; ?>" <?php if (isset($cita[0]['id_servicio'])) { if ($row['id_servicio']==$cita[0]['id_servicio']) { echo "selected"; } } ?>>
                                        <?php echo strtoupper($row['nombre_familia']." - ".$row['nombre_servicio']." (".$row['duracion']." min) - TEMPLOS: ".$row['templos']); ?>
                                    </option>
                                <?php }
                            }
                        } ?>                    
                    </select>   
                </div>
                <div class="w-auto ms-3">
                    <label for="" class="form-label">Elige la cantidad</label>
                    <input name="cantidad" id="cantidad" class="form-control form-control-solid w-auto" type="number" placeholder="Cantidad" />
                </div>
                <div class="w-auto  ms-3">
                    <button type="button" id="agregar" class="btn btn-info text-inverse-info btn-icon"><i class="fas fa-plus"></i></button>
                </div>
            </div>
        </div>
        <div class="card-body pt-6">
            <div id="recomendacion_es" style="font-size: 18px; text-align: center; color: blue"></div>  
            <div id="recomendacion"  class="alert aalert-primary d-flex flex-column flex-sm-row p-5 mb-10"></div>
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-5 table-rounded table-striped">
                    <thead class="">
                        <tr class="text-start text-gray-600 fw-bold fs-5 text-uppercase gs-0">
                            <th>Servicios</th>                    
                            <th style="width: 5%;">Quitar</th>      
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 fw-semibold" id="elementos_servicios">             
                        <tr>                    
                            <td></td>
                            <td></td>
                            <td style="display: none;"></td>
                        </tr>                  
                    </tbody>
                    <tfoot>
                        <tr>
                            <td>
                                <h4 class="text-right">Templos Totales:</h4>
                            </td>
                            <td>
                                <input name="templos" class="form-control form-control-solid w-auto" type="number" step="0.5" value="0" style="text-align: right; width: 100px;" readonly />
                            </td>
                        </tr>
                    </tfoot>                 
                </table>
            </div>
        </div>
    </form>
</div>
<script>
    /* ------------------------------------------------------------------------ */
    /* Control de servicios que se añaden 
    /* ------------------------------------------------------------------------ */
    var tablaElementos = document.getElementById('elementos_servicios');
    var txtServicio = document.getElementById('servicioListado');
    var txtCantidad = document.getElementById('cantidad');    
    var btnAgregar = document.getElementById('agregar');    
    var datos = [];
    function btnBorrar_Click(event) {
        document.form_carnets.templos.value=parseFloat(document.form_carnets.templos.value)-parseFloat(this.elemento.templos);
        tablaElementos.removeChild(tablaElementos.childNodes[this.elemento.item]);
        datos.splice(this.elemento.item,1);
        // Cada vez que borro vuelvo a regener la tabla para que
        // los indices se recalculen.
        ElementosTabla();
        Recomendacion();        
    }
    function btnAgregar_Click(event) {
        if (document.form_carnets.id_servicio.value=="") {
            alert("DEBES DE INDICAR UN SERVICIO ANTES DE AÑADIR");
            return false;
        }
        if (txtCantidad.value <= 0) {
            alert("DEBES DE INDICAR UNA CANTIDAD MAYOR DE 0");
            return false;
        }
        var servicio = txtServicio[txtServicio.selectedIndex].innerHTML;
        console.log(servicio);
        var servicio_id = txtServicio.value;
        var res = servicio.split("TEMPLOS: ");
        console.log(res);
        var templosServicio = parseFloat(res[1]);
        console.log(templosServicio);
        var cantidad = txtCantidad.value;
        // ... Tantas veces como la cantidad
        if (txtCantidad.value>0) {            
            for (var v=0; v<txtCantidad.value; v++) {
                // JSON
                var item = {
                    servicio: servicio.trim(),
                    servicio_id: servicio_id.trim(),
                    templos: templosServicio,
                    item: 0
                };    
                datos.push(item);
                document.form_carnets.templos.value=parseFloat(document.form_carnets.templos.value)+templosServicio;
            }            
            ElementosTabla();            
        }
        Recomendacion();
        return true;
    };
    function ElementosTabla(enviar) {
        tablaElementos.innerHTML = '';                
        for (var i = 0; i < datos.length; i++) {
            var elemento = datos[i];
            var tr = document.createElement('tr');
            var td1 = document.createElement('td');
            var td2 = document.createElement('td');
            var td3 = document.createElement('td');
            td3.style.display = 'none';
            td2.style="text-align: center;";
            tr.appendChild(td1);
            tr.appendChild(td2);
            tr.appendChild(td3);
            td1.textContent = elemento.servicio;
            elemento.item = i;
            tablaElementos.appendChild(tr);
            var nuevoBoton = document.createElement('button');
            nuevoBoton.type = 'button'; 
            var iconbutton =  document.createElement('i');        
            nuevoBoton.classList.add("btn", "btn-sm", "btn-icon", "btn-danger");
            iconbutton.classList.add("fa-solid", "fa-trash");
            nuevoBoton.appendChild(iconbutton);
            nuevoBoton.addEventListener('click',  btnBorrar_Click);
            nuevoBoton.elemento = elemento;
            td2.appendChild(nuevoBoton);
            var input = document.createElement('input');
            input.setAttribute("type", "hidden");            
            input.value=elemento.servicio_id;
            input.name="servicios_carnet[]";
            td3.appendChild(input);
            if (enviar==1) {                
                document.getElementById('form_carnets').appendChild(input);
            }
        }        
    }
    function Recomendacion() {
        // 12 templos - no existe 
        // 20 templos - 105
        // 30 templos - 151
        // 40 templos - 195
        // 60 templos - 285
        // 80 templos - 368
        // 100 templos - 448        
        var t = document.form_carnets.templos.value;
        var txt = document.getElementById("recomendacion");
        var precio = 0;
        var templos = 20;
        /*if (t<20) { precio = 99; templos=20; }
        if (t>=20 && t<30) { precio = 99; templos=20; }
        if (t>=30 && t<40) { precio = 145; templos=30; }
        if (t>=40 && t<60) { precio = 187; templos=40; }
        if (t>=60 && t<80) { precio = 274; templos=60; }
        if (t>=80 && t<100) { precio = 352; templos=80; }
        if (t>=100) { precio = 423.5; templos=100; }
        var diferencia = t - templos;
        if (diferencia<0) { diferencia=0; }
        var calculo = precio + (diferencia*5.5);
        if (calculo < 99) {
            txt.innerHTML = "Te recomendamos un carnet de 20 templos. ";
            templos=20;
        }
        if (calculo >= 99 && calculo < 145) {
            txt.innerHTML = "Te recomendamos un carnet de 20 templos. ";
            templos=20;
        }
        if (calculo >= 145 && calculo < 187) {
            txt.innerHTML = "Te recomendamos un carnet de 30 templos. ";
            templos=30;
        }
        if (calculo >= 187 && calculo < 274) {
            txt.innerHTML = "Te recomendamos un carnet de 40 templos. ";
            templos=40;
        }
        if (calculo >= 274 && calculo < 352) {
            txt.innerHTML = "Te recomendamos un carnet de 60 templos. ";
            templos=60;
        }
        if (calculo >= 352 && calculo < 423.5) {
            txt.innerHTML = "Te recomendamos un carnet de 80 templos. ";
            templos=80;
        }
        if (calculo >= 423.5) {
            txt.innerHTML = "Te recomendamos un carnet de 100 templos. ";
            templos=100;
        }
        diferencia = t - templos;
        if (diferencia<0) { diferencia=0; }
        if (diferencia> 0) {
            txt.innerHTML += "<p>Y compra "+diferencia+" templos a 5,5 euros cada uno.</p>";
        }
        if (t==0) {
            txt.innerHTML = "";
        }*/
        var total;
        var c_20 = 120;
        var c_30 = 170;
        var c_40 = 220;
        var c_60 = 321;
        var c_80 = 414;
        var c_100 = 504;
        var total_next = 10000;
        // 12 templos - 55 
        // 20 templos - 99
        // 30 templos - 145
        // 40 templos - 187
        // 60 templos - 274
        // 80 templos - 352
        // 100 templos - 423.5  
        if (t<20) { 
            total = t*6.3;
            // opcion1 = "<li>Un carnet de "+t+ " templos a 5,5 euros cada uno por "+total+" €</li>";
            opcion1 = "<li>Un carnet de 20 templos por "+c_20+" €</li>";
        }else if(t==20){ 
            opcion1 = "<li>Un carnet de 20 templos por "+c_20+" €</li>";
        }else if(t<30){
            t = t-20;
            total = (t*6.3) + c_20
            total_next = c_30
            opcion1 = "<li>Un carnet de 20 templos mas "+t+ " templos a 6,3 euros cada uno por "+total+" €</li>";
            opcion2 = "<li>Un carnet de 30 templos por "+total_next+" €</li>";
        }else if(t==30){
            opcion1 = "<li>Un carnet de 30 templos por "+c_30+" €</li>";
        }else if(t<40){
            t = t-30;
            total = (t*6.3) + c_30
            total_next = c_40
            opcion1 = "<li>Un carnet de 30 templos mas "+t+ " templos a 6,3 euros cada uno por "+total+" €</li>";
            opcion2 = "<li>Un carnet de 40 templos por "+total_next+" €</li>";
        }else if(t==40){
            opcion1 = "<li>Un carnet de 40 templos por "+c_40+" €</li>";
        }else if(t<60){
            t = t-40;
            total = (t*6.3) + c_40
            total_next = c_60
            opcion1 = "<li>Un carnet de 40 templos mas "+t+ " templos a 6,3 euros cada uno por "+total+" €</li>";
            opcion2 = "<li>Un carnet de 60 templos por "+total_next+" €</li>";
        }else if(t==60){
            opcion1 = "<li>Un carnet de 60 templos por "+c_60+" €</li>";
        }else if(t<80){
            t = t-60;
            total = (t*6.3) + c_60
            total_next = c_80
            opcion1 = "<li>Un carnet de 60 templos mas "+t+ " templos a 6,3 euros cada uno por "+total+" €</li>";
            opcion2 = "<li>Un carnet de 80 templos por "+total_next+" €</li>";
        }else if(t==80){
            opcion1 = "<li>Un carnet de 80 templos por "+c_80+" €</li>";
        }else if(t<100){
            t = t-80;
            total = (t*6.3) + c_80
            total_next = c_100
            opcion1 = "<li>Un carnet de 80 templos mas "+t+ " templos a 6,3 euros cada uno por "+total+" €</li>";
            opcion2 = "<li>Un carnet de 100 templos por "+total_next+" €</li>";
        }else if(t==100){
            opcion1 = "<li>Un carnet de 100 templos por "+c_100+" €</li>";
        }else{
            t = t-100;
            total = (t*6.3) + c_100
            opcion1 = "<li>Un carnet de 100 templos mas "+t+ " templos a 6,3 euros cada uno por "+total+" €</li>";
            opcion2 = "";
        }
        if(total >= total_next){
            mensaje_final = "<p>Opciones: <ul>"+opcion1+opcion2+"</ul></p>";
        }else{
            mensaje_final = "<p>Opciones: <ul>"+opcion1+"</ul></p>";
        }
        txt.innerHTML = mensaje_final;
        $('#recomendacion').show('slow');
    }
    btnAgregar.addEventListener('click', btnAgregar_Click);
</script>