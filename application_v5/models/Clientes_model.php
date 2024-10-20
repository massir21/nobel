<?php
class Clientes_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
   
    // -------------------------------------------------------------------
    // ... CLIENTES
    // -------------------------------------------------------------------
    function leer_clientes($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $busqueda = "";
        $busqueda_adicional = "";

        if (isset($parametros['id_cliente'])) {
            $busqueda .= " AND clientes.id_cliente = @id_cliente ";
        }

        if (isset($parametros['email'])) {
            $busqueda .= " AND clientes.email = @email ";
        }

        if (isset($parametros['id_cliente_excluir'])) {
            $busqueda .= " AND clientes.id_cliente <> @id_cliente_excluir ";
        }

        if (isset($parametros['nombre'])) {
            $busqueda .= " AND clientes.nombre = @nombre ";
        }

        if (isset($parametros['apellidos'])) {
            $busqueda .= " AND clientes.apellidos = @apellidos ";
        }

        if (isset($parametros['telefono'])) {
            $busqueda .= " AND clientes.telefono = @telefono ";
        }

        if (isset($parametros['codigo_cliente'])) {
            $busqueda .= " AND clientes.codigo_cliente = @codigo_cliente ";
        }

        if (isset($parametros['buscar'])) {
            $parametros['buscar'] = strtoupper($parametros['buscar']);
            $parametros['buscar'] = ltrim($parametros['buscar']);
            $parametros['buscar'] = rtrim($parametros['buscar']);
            $parametros['buscar'] = str_replace(" ", "%", $parametros['buscar']);

            $busqueda .= " AND (CONCAT(clientes.nombre, ' ', clientes.apellidos) like '%" . $parametros['buscar'] . "%'
            OR clientes.telefono like '%" . $parametros['buscar'] . "%'
            OR clientes.email like '%" . $parametros['buscar'] . "%') ";
        }

        // ... Añado el join para el ultimo y primer centro
        $ultimo_centro = " '' as ultimo_centro, '' as ultimo_empleado, '' as ultima_recepcionista, ";
        $join_clientes = "  LEFT JOIN usuarios AS PERSONAL ON (PERSONAL.id_usuario=clientes.id_usuario_modificacion) ";
        if (isset($parametros['ultimo_centro'])) {
            if ($parametros['ultimo_centro'] == 1) {
                $ultimo_centro = " centros.nombre_centro as ultimo_centro,
        CONCAT(UUT.nombre, ' ', UUT.apellidos) As ultimo_empleado,
        CONCAT(UUR.nombre, ' ', UUR.apellidos) As ultima_recepcionista, ";

                $join_clientes .= " LEFT JOIN (
        clientes_ultimos_centros
        LEFT JOIN centros on centros.id_centro = clientes_ultimos_centros.id_centro
        LEFT JOIN usuarios as UUT on UUT.id_usuario = clientes_ultimos_centros.id_empleado
        LEFT JOIN usuarios as UUR on UUR.id_usuario = clientes_ultimos_centros.id_usuario_modificacion
        )
        on clientes_ultimos_centros.id_cliente = clientes.id_cliente
        LEFT JOIN clientes_primer_centros ON clientes_primer_centros.id_cliente = clientes.id_cliente ";
            }
        }

        if (isset($parametros['fecha_desde_creacion']) && isset($parametros['fecha_hasta_creacion'])) {
            if ($parametros['fecha_desde_creacion'] != "" && $parametros['fecha_hasta_creacion'] != "") {
                $parametros['fecha_hasta_creacion'] = $parametros['fecha_hasta_creacion'] . " 23:59:59";
                $busqueda .= " AND clientes.fecha_creacion >= @fecha_desde_creacion and
        clientes.fecha_creacion <= @fecha_hasta_creacion ";
            }
        }

        if (isset($parametros['que_venga_condicion']) && isset($parametros['que_venga_veces']) && isset($parametros['que_venga_periodo'])) {
            if ($parametros['que_venga_condicion'] != "0" && $parametros['que_venga_veces'] > 0 && $parametros['que_venga_periodo'] != "0") {
                $tabla = "";
                $condicion = $this->condicion_busqueda($parametros['que_venga_condicion']);

                if ($parametros['que_venga_periodo'] == 0) {
                    $tabla = "clientes_visitas_12_meses";
                }
                if ($parametros['que_venga_periodo'] == "Anno") {
                    $tabla = "clientes_visitas_12_meses";
                }
                if ($parametros['que_venga_periodo'] == "Mes") {
                    $tabla = "clientes_visitas_1_mes";
                }
                if ($parametros['que_venga_periodo'] == "Semana") {
                    $tabla = "clientes_visitas_1_semana";
                }

                $busqueda .= "AND " . $tabla . ".veces " . $condicion . " @que_venga_veces ";
                $join_clientes .= " LEFT JOIN " . $tabla . " ON " . $tabla . ".id_cliente = clientes.id_cliente ";
            }
        }

        if (isset($parametros['fecha_desde_ultima_visita']) && isset($parametros['fecha_hasta_ultima_visita'])) {
            if ($parametros['fecha_desde_ultima_visita'] != "" && $parametros['fecha_hasta_ultima_visita'] != "") {
                $parametros['fecha_hasta_ultima_visita'] = $parametros['fecha_hasta_ultima_visita'] . " 23:59:59";

                $busqueda .= " AND clientes_ultimos_centros.fecha >= @fecha_desde_ultima_visita and
        clientes_ultimos_centros.fecha <= @fecha_hasta_ultima_visita ";
            }
        }

        if (isset($parametros['consumo_periodo']) && isset($parametros['consumo_condicion']) && isset($parametros['consumo_importe'])) {
            if ($parametros['consumo_periodo'] != "0" && $parametros['consumo_importe'] > 0 && $parametros['consumo_condicion'] != "0") {
                $tabla = "";
                $condicion = $this->condicion_busqueda($parametros['consumo_condicion']);

                if ($parametros['consumo_periodo'] == "Anno") {
                    $tabla = "clientes_consumo_anno";
                }
                if ($parametros['consumo_periodo'] == "Mes") {
                    $tabla = "clientes_consumo_mes";
                }
                if ($parametros['consumo_periodo'] == "Semana") {
                    $tabla = "clientes_consumo_semana";
                }
                if ($parametros['consumo_periodo'] == "Todo") {
                    $tabla = "clientes_consumo_total";
                }

                $busqueda .= "AND " . $tabla . ".facturacion_total " . $condicion . " @consumo_importe ";
                $join_clientes .= " LEFT JOIN " . $tabla . " ON " . $tabla . ".id_cliente = clientes.id_cliente ";
            }
        }

        if (isset($parametros['acudido_centro']) && isset($parametros['acudido_centro_periodo'])) {
            if ($parametros['acudido_centro'] != "0" && $parametros['acudido_centro_periodo'] != "0") {
                if ($parametros['acudido_centro_periodo'] == "Primera_visita") {
                    $busqueda .= "AND clientes_primer_centros.id_centro = @acudido_centro ";
                }

                if ($parametros['acudido_centro_periodo'] == "Ultima_visita") {
                    $busqueda .= "AND clientes_ultimos_centros.id_centro = @acudido_centro ";
                }

                if ($parametros['acudido_centro_periodo'] == "Alguna_vez") {
                    $busqueda_adicional .= " AND id_centro = @acudido_centro ";
                }
            }
        }

        if (isset($parametros['atendido_empleado']) && isset($parametros['atendido_periodo'])) {
            if ($parametros['atendido_empleado'] != "0" && $parametros['atendido_periodo'] != "0") {
                if ($parametros['atendido_periodo'] == "Primera_visita") {
                    $busqueda .= "AND clientes_primer_centros.id_empleado = @atendido_empleado ";
                }

                if ($parametros['atendido_periodo'] == "Ultima_visita") {
                    $busqueda .= "AND clientes_ultimos_centros.id_empleado = @atendido_empleado ";
                }

                if ($parametros['atendido_periodo'] == "Alguna_vez") {
                    $busqueda_adicional .= " and id_empleado = @atendido_empleado ";
                }
            }
        }

        if (isset($parametros['atendido_solo_empleado']) && isset($parametros['atendido_solo_periodo'])) {
            if ($parametros['atendido_solo_empleado'] != "0" && $parametros['atendido_solo_periodo'] != "0") {
                if ($parametros['atendido_solo_periodo'] == "Primera_visita") {
                    $busqueda .= "AND clientes_primer_centros.id_empleado = @atendido_solo_empleado
          AND (clientes.id_cliente in
            (select id_cliente from citas
              where solo_este_empleado = 1 and
              borrado = 0 and
              estado = 'Finalizado' and
              id_usuario_empleado = @atendido_solo_empleado
              )) ";
                }

                if ($parametros['atendido_solo_periodo'] == "Ultima_visita") {
                    $busqueda .= "AND clientes_ultimos_centros.id_empleado = @atendido_solo_empleado
          AND (clientes.id_cliente in
            (select id_cliente from citas
              where solo_este_empleado = 1 and
              borrado = 0 and
              estado = 'Finalizado' and
              id_usuario_empleado = @atendido_solo_empleado
              )) ";
                }

                if ($parametros['atendido_solo_periodo'] == "Alguna_vez") {
                    $busqueda_adicional .= " and id_empleado = @atendido_solo_empleado
          AND (clientes.id_cliente in
            (select id_cliente from citas
              where solo_este_empleado = 1 and
              borrado = 0 and
              estado = 'Finalizado' and
              id_usuario_empleado = @atendido_solo_empleado
          )) ";
                }
            }
        }

        if (isset($parametros['id_familia_servicio']) && isset($parametros['hecho_servicio_periodo'])) {
            if ($parametros['id_familia_servicio'] != "0" && $parametros['hecho_servicio_periodo'] != "0") {

                if ($parametros['id_servicio'] != "0") {
                    if ($parametros['hecho_servicio_periodo'] == "Primera_visita") {
                        $busqueda .= "AND clientes_primer_centros.id_servicio = @id_serivicio ";
                    } else if ($parametros['hecho_servicio_periodo'] == "Ultima_visita") {
                        $busqueda .= "AND clientes_ultimos_centros.id_servicio = @id_serivicio ";
                    }

                    //else if ($parametros['hecho_servicio_periodo']=="Alguna_vez") {
                    else {
                        $busqueda_adicional .= " and id_servicio = @id_servicio ";
                    }
                } else {
                    if ($parametros['hecho_servicio_periodo'] == "Primera_visita") {
                        $busqueda .= " AND clientes_primer_centros.id_servicio in (select id_servicio from servicios where borrado = 0 and
            servicios.id_familia_servicio in (select id_familia_servicio from servicios_familias where borrado = 0
            and id_familia_servicio = @id_familia_servicio)) ";
                    } else if ($parametros['hecho_servicio_periodo'] == "Ultima_visita") {
                        $busqueda .= "AND clientes_ultimos_centros.id_servicio in (select id_servicio from servicios where borrado = 0 and
            servicios.id_familia_servicio in (select id_familia_servicio from servicios_familias where borrado = 0
            and id_familia_servicio = @id_familia_servicio))";
                    }

                    //if ($parametros['hecho_servicio_periodo']=="Alguna_vez") {
                    else {
                        $busqueda_adicional .= " and id_servicio in
            (select id_servicio from servicios
            where borrado = 0 and id_familia_servicio = @id_familia_servicio) ";
                    }
                }
            }
        }

        if (isset($parametros['comprado_producto']) && isset($parametros['comprado_producto_periodo'])) {
            if ($parametros['comprado_producto'] != "0" && $parametros['comprado_producto_periodo'] != "0") {
                if ($parametros['comprado_producto_periodo'] == "Primera_visita") {
                    $busqueda .= "AND clientes_primer_centros.id_producto = @comprado_producto ";
                }

                if ($parametros['comprado_producto_periodo'] == "Ultima_visita") {
                    $busqueda .= "AND clientes_ultimos_centros.id_producto = @comprado_producto ";
                }

                if ($parametros['comprado_producto_periodo'] == "Alguna_vez") {
                    $busqueda_adicional .= " and id_producto = @comprado_producto ";
                }
            }
        }

        if (isset($parametros['comprado_carnet']) && isset($parametros['comprado_carnet_periodo'])) {
            if ($parametros['comprado_carnet'] != "0" && $parametros['comprado_carnet_periodo'] != "0") {
                if ($parametros['comprado_producto_periodo'] == "Primera_visita") {
                    $busqueda .= "AND clientes_primer_centros.id_carnet in (select id_carnet from carnets_templos where borrado = 0
          and id_tipo = @comprado_carnet) ";
                }

                if ($parametros['comprado_carnet_periodo'] == "Ultima_visita") {
                    $busqueda .= "AND clientes_ultimos_centros.id_carnet in (select id_carnet from carnets_templos where borrado = 0
          and id_tipo = @comprado_carnet)  ";
                }

                if ($parametros['comprado_carnet_periodo'] == "Alguna_vez") {
                    $busqueda_adicional .= " and id_carnet in (select id_carnet from carnets_templos where borrado = 0
          and id_tipo = @comprado_carnet)";
                }
            }
        }

        if (isset($parametros['que_repita_condicion']) && isset($parametros['que_repita_veces']) && isset($parametros['que_repita_empleado'])) {
            if ($parametros['que_repita_condicion'] != "0" && $parametros['que_repita_veces'] > 0 && $parametros['que_repita_empleado'] != "0") {
                $condicion = $this->condicion_busqueda($parametros['que_repita_condicion']);

                $busqueda .= " AND clientes.id_cliente in (select id_cliente from clientes_visitas_empleado
        where veces " . $condicion . " @que_repita_veces and id_empleado = @que_repita_empleado) ";
            }
        }

        if (isset($parametros['que_haya_anulado_condicion']) && isset($parametros['que_haya_anulado_veces'])) {
            if ($parametros['que_haya_anulado_condicion'] != "0" && $parametros['que_haya_anulado_veces'] > 0) {
                $tabla = "clientes_anuladas_12_mes";
                $condicion = $this->condicion_busqueda($parametros['que_haya_anulado_condicion']);

                if ($parametros['que_haya_anulado_periodo'] == "Anno") {
                    $tabla = "clientes_anuladas_12_mes";
                }
                if ($parametros['que_haya_anulado_periodo'] == "Mes") {
                    $tabla = "clientes_anuladas_1_mes";
                }
                if ($parametros['que_haya_anulado_periodo'] == "Semana") {
                    $tabla = "clientes_anuladas_1_semana";
                }

                $busqueda .= "AND " . $tabla . ".veces " . $condicion . " @que_haya_anulado_veces ";
                $join_clientes .= " LEFT JOIN " . $tabla . " ON " . $tabla . ".id_cliente = clientes.id_cliente ";
            }
        }

        if (isset($parametros['que_no_vino_condicion']) && isset($parametros['que_no_vino_veces'])) {
            if ($parametros['que_no_vino_condicion'] != "0" && $parametros['que_no_vino_veces'] > 0) {
                $tabla = "clientes_novino_12_mes";
                $condicion = $this->condicion_busqueda($parametros['que_no_vino_condicion']);

                if ($parametros['que_no_vino_periodo'] == "Anno") {
                    $tabla = "clientes_novino_12_mes";
                }
                if ($parametros['que_no_vino_periodo'] == "Mes") {
                    $tabla = "clientes_novino_1_mes";
                }
                if ($parametros['que_no_vino_periodo'] == "Semana") {
                    $tabla = "clientes_novino_1_semana";
                }

                $busqueda .= "AND " . $tabla . ".veces " . $condicion . " @que_no_vino_veces ";
                $join_clientes .= " LEFT JOIN " . $tabla . " ON " . $tabla . ".id_cliente = clientes.id_cliente ";
            }
        }

        if (isset($parametros['que_acuda_centros'])) {
            if ($parametros['que_acuda_centros'] > 0) {
                $join_clientes .= " LEFT JOIN clientes_centros_diferentes
        ON clientes_centros_diferentes.id_cliente = clientes.id_cliente ";
                $busqueda .= "AND clientes_centros_diferentes.centros_diferentes > @que_acuda_centros ";
            }
        }

        if (isset($parametros['rentabilidad_condicion']) && isset($parametros['rentabilidad'])) {
            if ($parametros['rentabilidad_condicion'] != "0" && $parametros['rentabilidad'] != "") {
                $condicion = $this->condicion_busqueda($parametros['rentabilidad_condicion']);

                $join_clientes .= " LEFT JOIN clientes_facturacion_total
        ON clientes_facturacion_total.id_cliente = clientes.id_cliente

        LEFT JOIN clientes_citas_novino_total
        ON clientes_citas_novino_total.id_cliente = clientes.id_cliente

        LEFT JOIN clientes_visitas_totales
        ON clientes_visitas_totales.id_cliente = clientes.id_cliente
        ";

                $busqueda .= "AND ((clientes_facturacion_total.facturacion- ifnull(clientes_citas_novino_total.citas,0) )
        / clientes_visitas_totales.dato) " . $condicion . " @rentabilidad ";
            }
        }

        // ... Esto es en caso de que se elijan opciones de periodo "Alguna vez".
        if ($busqueda_adicional != "") {
            $busqueda .= " AND clientes.id_cliente in (select id_cliente from dietario where borrado = 0
      and id_cliente = clientes.id_cliente and estado = 'Pagado' $busqueda_adicional) ";
        }

        // ... Para exportacion de CVS datos de especificos
        $exportacion_csv_especifico = "";
        if (isset($parametros['exportacion_csv_especifico'])) {
            $exportacion_csv_especifico = "
        ,
        CUV.nombre_centro as ultimo_centro_visitado,
        CUC.fecha_ultima_reserva,
        CA.fecha_ultimo_login,
        CP.fecha_ultima_cita_abandonada,
        CPA.numero_citas_abandonadas ";

                $join_clientes .= "
            LEFT JOIN (clientes_ultimos_centros left join centros as CUV on clientes_ultimos_centros.id_centro = CUV.id_centro)
            ON clientes_ultimos_centros.id_cliente = clientes.id_cliente

            LEFT JOIN (SELECT id_cliente,MAX(fecha_creacion) as fecha_ultima_reserva
            FROM clientes_ultimos_centros GROUP BY id_cliente) as CUC ON CUC.id_cliente = clientes.id_cliente

            LEFT JOIN (SELECT id_cliente,MAX(fecha_acceso) as fecha_ultimo_login
            FROM clientes_accesos group by id_cliente) as CA ON CA.id_cliente = clientes.id_cliente

            LEFT JOIN (SELECT id_cliente,MAX(fecha_creacion) as fecha_ultima_cita_abandonada
                        FROM citas_pedidos
                        WHERE citas_pedidos.borrado = 0 and estado = 'Abandonado'
                        group by id_cliente)
            as CP ON CP.id_cliente = clientes.id_cliente

            LEFT JOIN (SELECT id_cliente,COUNT(id_pedido) as numero_citas_abandonadas
                    FROM citas_pedidos WHERE borrado = 0 and estado = 'Abandonado' GROUP BY id_cliente)
            as CPA ON CPA.id_cliente = clientes.id_cliente
            
        
        ";
        }

        // ... Leemos los registros
        $sentencia_sql = "
            SELECT
            clientes.id_cliente,clientes.nombre,clientes.apellidos,clientes.sexo,clientes.ocupacion,concat(PERSONAL.nombre,' ',PERSONAL.apellidos) AS modificador,
            DATE_FORMAT(clientes.fecha_nacimiento,'%Y-%m-%d') as fecha_nacimiento_aaaammdd,
            DATE_FORMAT(clientes.fecha_nacimiento,'%d-%m-%Y') as fecha_nacimiento_ddmmaaaa,
            DATE_FORMAT(clientes.fecha_modificacion,'%d-%m-%Y') as fecha_modificacion_ddmmaaaa,
            TIMESTAMPDIFF(YEAR, clientes.fecha_nacimiento, CURDATE()) AS edad,clientes.nombre_tutor,clientes.dni_tutor,
            clientes.email,clientes.telefono,clientes.direccion,clientes.codigo_postal,
            clientes.id_usuario_creacion,clientes.fecha_creacion,clientes.id_usuario_modificacion,
            clientes.fecha_modificacion,clientes.borrado,clientes.id_usuario_borrado,$ultimo_centro
            clientes.fecha_borrado,clientes.codigo_cliente,clientes.no_quiere_publicidad,
            clientes.recordatorio_sms,clientes.recordatorio_email,clientes.dni,
            clientes.notas,clientes.empresa,clientes.cif_nif,clientes.direccion_facturacion,
            clientes.codigo_postal_facturacion,clientes.localidad_facturacion,
            clientes.provincia_facturacion,clientes.password,clientes.activo,clientes.notificaciones,clientes.como_conocio,
            DATE_FORMAT(clientes.fecha_creacion,'%d-%m-%Y %H:%i') as fecha_creacion_ddmmaaaa,clientes.fecha_activacion
            $exportacion_csv_especifico
            FROM
            clientes " . $join_clientes . "
            WHERE
            clientes.borrado = 0 " . $busqueda . "
            GROUP BY
            clientes.id_cliente
            ORDER BY
            nombre,apellidos
            ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    //17/04/20
    function leer_clientes_todos($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        //ORDER BY id_cliente desc
        $sentencia_sql = "SELECT id_cliente,nombre FROM clientes WHERE borrado = 0 ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    //Fin

    //21/10/20 ************ Fichas de salud leer **************
    function leer_fichas_salud($parametros)
    {
        $AqConexion_model = new AqConexion_model();
        $busqueda = " AND ficha_salud.id_cliente=@id_cliente";
        // ... Leemos los registros

        $sentencia_sql = "SELECT ficha_salud.*, concat(U1.nombre,' ',U1.apellidos) AS creador , concat(U2.nombre,' ',U2.apellidos) AS modificador FROM ficha_salud 
        LEFT JOIN usuarios AS U1 ON (U1.id_usuario=ficha_salud.id_usuario_creacion) 
        LEFT JOIN usuarios AS U2 ON (U2.id_usuario=ficha_salud.id_usuario_modificacion)
        WHERE ficha_salud.borrado = 0 " . $busqueda;
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    function ficha_detalles($parametros)
    {
        $AqConexion_model = new AqConexion_model();
        $busqueda = " AND ficha_salud.id=@id";
        // ... Leemos los registros

        $sentencia_sql = "SELECT * FROM ficha_salud 
     WHERE ficha_salud.borrado = 0 " . $busqueda;
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }
    //21/20/20 ********** Ficha Salud ********************* Ficha Salud ***************
    function nueva_ficha($parametros)
    {
        $AqConexion_model = new AqConexion_model();
        //$registros=$parametros;
        $parametros['fecha_creacion'] = date("Y-m-d H:i:s");
        $parametros['id_usuario_creacion'] = $this->session->userdata('id_usuario');
        $parametros['fecha_modificacion'] = date("Y-m-d H:i:s");
        $parametros['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
        $parametros['borrado'] = 0;

        $AqConexion_model->insert('ficha_salud', $parametros);
        return 1;
    }

    //18/06/21
    function nuevo_consentimiento($parametros)
    {
        $AqConexion_model = new AqConexion_model();
        $parametros['id_empleado'] = $this->session->userdata('id_usuario');
        $parametros['fecha'] = date("Y-m-d H:i:s");
        $parametros['id_usuario_borrado'] = 0;

        $AqConexion_model->insert('consentimientos', $parametros);
        return 1;
    }

    function ficha_actualizar($parametros)
    {
        $AqConexion_model = new AqConexion_model();
        $parametros['fecha_modificacion'] = date("Y-m-d H:i:s");
        $parametros['id_usuario_modificacion'] = $this->session->userdata('id_usuario');

        $where['id'] = $parametros['id'];

        $AqConexion_model->update('ficha_salud', $parametros, $where);
        return 1;
    }

    //Fin Ficha Cliente ************

    //Fin 21/10/20

    //25/10/23
    function leer_doctores($idUsuario = null, $idCentro = null){
        $AqConexion_model = new AqConexion_model();
        //Changes
        $sentencia_sql = "
            SELECT usuarios_perfiles.id_perfil 
            FROM `usuarios_perfiles` 
            WHERE id_usuario=$idUsuario;
            ";
        $parametros['vacio']="";
        $datos = $AqConexion_model->select($sentencia_sql,$parametros);

        if ($idCentro == null) {
            $idCentro = 1;
        }

        $sqlWhere = "WHERE usuarios_perfiles.id_perfil=6 AND usuarios.borrado=0"; //MASTER
        if ($datos) {
            $idPerfil = $datos[0]['id_perfil'];
            if ($idPerfil != "0") {
                $sqlWhere = "WHERE usuarios_perfiles.id_perfil=6 AND usuarios.borrado=0 AND usuarios.id_centro=$idCentro";
            }
        }

        $busqueda="";
        $sentencia_sql = "
            SELECT usuarios.id_usuario,usuarios.nombre,usuarios.apellidos,usuarios.n_colegiado
            FROM `usuarios_perfiles` 
            INNER join usuarios ON (usuarios.id_usuario=usuarios_perfiles.id_usuario) ";
        $sentencia_sql .= $sqlWhere;

      //  var_dump($sentencia_sql);exit();

        $parametros['vacio']="";
        $datos = $AqConexion_model->select($sentencia_sql,$parametros);

        return $datos;
    }
    //Fin 25/10/23

    function leer_clientes_duplicados($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = "SELECT nombre, apellidos, telefono, COUNT(*) repetido FROM clientes
        WHERE borrado = 0 GROUP BY nombre, apellidos, telefono HAVING COUNT(*) > 1 ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }


    function nuevo_cliente($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        if (isset($parametros['email'])) {
            $param['id_cliente'] = 0;
            $param['email'] = $parametros['email'];
            $ok = $this->existe_email($param);
        } else {
            $ok = false;
        }

        if (!$ok) {
            // ... Datos generales como cliente.
            if (isset($parametros['codigo_cliente'])) {
                $registro['codigo_cliente'] = $parametros['codigo_cliente'];
            }
            $registro['nombre'] = $parametros['nombre'];
            $registro['apellidos'] = $parametros['apellidos'];
            $registro['telefono'] = str_replace('+', '', $parametros['telefono']);
            if (isset($parametros['email'])) {
                $registro['email'] = $parametros['email'];
            }
            if (isset($parametros['direccion'])) {
                $registro['direccion'] = $parametros['direccion'];
            }
            if (isset($parametros['codigo_postal'])) {
                $registro['codigo_postal'] = $parametros['codigo_postal'];
            }
            if (isset($parametros['fecha_creacion'])) {
                $registro['fecha_creacion'] = $parametros['fecha_creacion'];
            }
            if (isset($parametros['fecha_nacimiento'])) {
                $registro['fecha_nacimiento'] = $parametros['fecha_nacimiento'];
            } else {
                $registro['fecha_creacion'] = date("Y-m-d H:i:s");
            }
            if (isset($parametros['no_quiere_publicidad'])) {
                if ($parametros['no_quiere_publicidad'] > 0) {
                    $registro['no_quiere_publicidad'] = $parametros['no_quiere_publicidad'];
                } else {
                    $registro['no_quiere_publicidad'] = 0;
                }
            } else {
                $registro['no_quiere_publicidad'] = 0;
            }
            //
            if (isset($parametros['recordatorio_sms'])) {
                if ($parametros['recordatorio_sms'] > 0) {
                    $registro['recordatorio_sms'] = $parametros['recordatorio_sms'];
                } else {
                    $registro['recordatorio_sms'] = 0;
                }
            } else {
                $registro['recordatorio_sms'] = 0;
            }
            //
            if (isset($parametros['recordatorio_email'])) {
                if ($parametros['recordatorio_email'] > 0) {
                    $registro['recordatorio_email'] = $parametros['recordatorio_email'];
                } else {
                    $registro['recordatorio_email'] = 0;
                }
            } else {
                $registro['recordatorio_email'] = 0;
            }
            //
            if (isset($parametros['notas'])) {
                $registro['notas'] = $parametros['notas'];
            }

            if (isset($parametros['notificaciones'])) {
                if ($parametros['notificaciones'] != "") {
                    $registro['notificaciones'] = $parametros['notificaciones'];
                }
            }
            if (isset($parametros['dni'])) {
                $registro['dni'] = $parametros['dni'];
            }
            if (isset($parametros['empresa'])) {
                $registro['empresa'] = $parametros['empresa'];
            }
            if (isset($parametros['cif_nif'])) {
                $registro['cif_nif'] = $parametros['cif_nif'];
            }
            if (isset($parametros['direccion_facturacion'])) {
                $registro['direccion_facturacion'] = $parametros['direccion_facturacion'];
            }
            if (isset($parametros['codigo_postal_facturacion'])) {
                $registro['codigo_postal_facturacion'] = $parametros['codigo_postal_facturacion'];
            }
            if (isset($parametros['localidad_facturacion'])) {
                $registro['localidad_facturacion'] = $parametros['localidad_facturacion'];
            }
            if (isset($parametros['provincia_facturacion'])) {
                $registro['provincia_facturacion'] = $parametros['provincia_facturacion'];
            }
            //13/11/23
            if (isset($parametros['nombre_tutor'])) {
                $registro['nombre_tutor'] = $parametros['nombre_tutor'];
            }
            if (isset($parametros['dni_tutor'])) {
                $registro['dni_tutor'] = $parametros['dni_tutor'];
            }

            if (isset($parametros['como_conocio'])) {
                $registro['como_conocio'] = $parametros['como_conocio'];
            }

            $registro['fecha_creacion'] = date("Y-m-d H:i:s");
            $registro['id_usuario_creacion'] = $this->session->userdata('id_usuario');
            $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
            $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
            $registro['borrado'] = 0;

            $AqConexion_model->insert('clientes', $registro);

            $sentenciaSQL = "select max(id_cliente) as id_cliente from clientes";
            $resultado = $AqConexion_model->select($sentenciaSQL, null);

            return $resultado[0]['id_cliente'];
        } else {
            return 0;
        }
    }

    function actualizar_cliente($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        if (isset($parametros['email'])) {
            $param['id_cliente'] = $parametros['id_cliente'];
            $param['email'] = $parametros['email'];
            $ok = $this->existe_email($param);
        } else {
            $ok = false;
        }

        if (!$ok) {
            if (isset($parametros['nombre'])) {
                // ... Datos generales como cliente.
                $registro['nombre'] = $parametros['nombre'];
                $registro['apellidos'] = $parametros['apellidos'];
                $registro['email'] = $parametros['email'];
                $registro['telefono'] = $parametros['telefono'];
                $registro['direccion'] = $parametros['direccion'];
                $registro['codigo_postal'] = $parametros['codigo_postal'];

                if (isset($parametros['fecha_nacimiento'])) {
                    $registro['fecha_nacimiento'] = $parametros['fecha_nacimiento'];
                }

                if (isset($parametros['no_quiere_publicidad'])) {
                    if ($parametros['no_quiere_publicidad'] > 0) {
                        $registro['no_quiere_publicidad'] = $parametros['no_quiere_publicidad'];
                    } else {
                        $registro['no_quiere_publicidad'] = 0;
                    }
                } else {
                    $registro['no_quiere_publicidad'] = 0;
                }

                //
                if (isset($parametros['recordatorio_sms'])) {
                    if ($parametros['recordatorio_sms'] > 0) {
                        $registro['recordatorio_sms'] = $parametros['recordatorio_sms'];
                    } else {
                        $registro['recordatorio_sms'] = 0;
                    }
                } else {
                    $registro['recordatorio_sms'] = 0;
                }
                //
                if (isset($parametros['recordatorio_email'])) {
                    if ($parametros['recordatorio_email'] > 0) {
                        $registro['recordatorio_email'] = $parametros['recordatorio_email'];
                    } else {
                        $registro['recordatorio_email'] = 0;
                    }
                } else {
                    $registro['recordatorio_email'] = 0;
                }
                //
                if (isset($parametros['activo'])) {
                    if ($parametros['activo'] > 0) {
                        $registro['activo'] = $parametros['activo'];
                    } else {
                        $registro['activo'] = 0;
                    }
                } else {
                    $registro['activo'] = 0;
                }

                if (isset($parametros['notas'])) {
                    $registro['notas'] = $parametros['notas'];
                }

                if (isset($parametros['notificaciones'])) {
                    if ($parametros['notificaciones'] != "") {
                        $registro['notificaciones'] = $parametros['notificaciones'];
                    }
                }
                if (isset($parametros['dni'])) {
                    $registro['dni'] = $parametros['dni'];
                }

                //27/05/20 Activo 0 o 1 Solo el Master
                if (isset($parametros['activo'])) {
                    if ($parametros['activo'] > 0) {
                        $registro['activo'] = $parametros['activo'];
                    } else {
                        $registro['activo'] = 0;
                    }
                } else {
                    $registro['activo'] = 0;
                }
                //Fin /27/05/20

                if(isset($parametros['empresa'])){
                    $registro['empresa'] = $parametros['empresa'];
                }
                if(isset($parametros['cif_nif'])){
                    $registro['cif_nif'] = $parametros['cif_nif'];
                }
                if(isset($parametros['direccion_facturacion'])){
                    $registro['direccion_facturacion'] = $parametros['direccion_facturacion'];
                }
                if(isset($parametros['codigo_postal_facturacion'])){
                    $registro['codigo_postal_facturacion'] = $parametros['codigo_postal_facturacion'];
                }
                if(isset($parametros['localidad_facturacion'])){
                    $registro['localidad_facturacion'] = $parametros['localidad_facturacion'];
                }
                if(isset($parametros['provincia_facturacion'])){
                    $registro['provincia_facturacion'] = $parametros['provincia_facturacion'];
                }
                //

                //19/10/20
                if(isset($parametros['ocupacion'])) {
                    $registro['ocupacion'] = $parametros['ocupacion'];
                }
                if(isset($parametros['sexo'])) {
                    $registro['sexo'] = $parametros['sexo'];
                }
                //Fin 19/10/20
                //13/11/23
                if (isset($parametros['nombre_tutor'])) {
                    $registro['nombre_tutor'] = $parametros['nombre_tutor'];
                }
                if (isset($parametros['dni_tutor'])) {
                    $registro['dni_tutor'] = $parametros['dni_tutor'];
                }

                if (isset($parametros['como_conocio'])) {
                    $registro['como_conocio'] = $parametros['como_conocio'];
                }

                $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
                $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');

                $where['id_cliente'] = $parametros['id_cliente'];
                $AqConexion_model->update('clientes', $registro, $where);

                // ... Si se modifica una nota del cliente, añadimos dichas notas
                // a todas las citas futuras existentes.
                if (isset($parametros['notas'])) {
                    if ($parametros['notas'] != "") {
                        $sentenciaSQL = "UPDATE citas SET
            observaciones = CONCAT(observaciones, ' + " . addslashes($parametros['notas']) . "')
            WHERE fecha_hora_inicio > now() AND id_cliente = " . $parametros['id_cliente'];
                        $AqConexion_model->no_select($sentenciaSQL, $parametros);

                        $registro['notas'] = $parametros['notas'];
                    }
                }
                return 1;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    function borrar_cliente($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $parametros['id_usuario_borrado'] = $this->session->userdata('id_usuario');
        $parametros['fecha_borrado'] = date("Y-m-d H:i:s");

        $sentenciaSQL = "update clientes set borrado = 1, id_usuario_borrado = @id_usuario_borrado, fecha_borrado = @fecha_borrado where id_cliente = @id_cliente";
        $AqConexion_model->no_select($sentenciaSQL, $parametros);

        return 1;
    }

    function existe_email($parametros)
    {
        $email_cliente = "";

        if ($parametros['id_cliente'] > 0) {
            $param['id_cliente'] = $parametros['id_cliente'];
            $cliente = $this->leer_clientes($param);
            $email_cliente = $cliente[0]['email'];
        }

        $param['email'] = strtolower($parametros['email']);

        if ($email_cliente != $param['email'] && $param['email'] != "") {
            unset($param);

            $param['id_cliente_excluir'] = $parametros['id_cliente'];
            $param['email'] = strtolower($parametros['email']);
            $cliente = $this->leer_clientes($param);

            if ($cliente > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function citas_totales($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = "SELECT count(id_cita) as citas FROM `citas`
        WHERE id_cliente = @id_cliente and borrado = 0";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos[0]['citas'] !== null ? $datos[0]['citas'] : 0;
    }

    function citas_finalizadas($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = "SELECT count(id_cita) as citas FROM `citas`
        WHERE id_cliente = @id_cliente and borrado = 0 and estado = 'Finalizado' ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos[0]['citas'] !== null ? $datos[0]['citas'] : 0;
    }

    function citas_no_vino($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = "SELECT count(id_cita) as citas FROM `citas`
            WHERE id_cliente = @id_cliente and borrado = 0 and estado = 'No vino'";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos[0]['citas'] !== null ? $datos[0]['citas'] : 0;
    }

    function citas_anuladas($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = "SELECT count(id_cita) as citas FROM `citas`
            WHERE id_cliente = @id_cliente and borrado = 0 and estado = 'Anulada' ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos[0]['citas'] !== null ? $datos[0]['citas'] : 0;
    }

    function importe_no_vino($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = "SELECT sum(importe_euros) as dato FROM `dietario`
            WHERE id_cliente = @id_cliente and estado = 'No Vino' and borrado = 0";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        if (!$datos) {
            return 0;
        } else {
            return $datos[0]['dato'] !== null ? $datos[0]['dato'] : 0;
        }
    }

    function importe_anuladas($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = "SELECT sum(importe_euros) as dato FROM `dietario`
            WHERE id_cliente = @id_cliente and estado = 'Anulada' and borrado = 0";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        if (!$datos) {
            return 0;
        } else {
            return $datos[0]['dato'] !== null ? $datos[0]['dato'] : 0;
        }
    }

    function visitas_totales($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = "SELECT count(distinct DATE_FORMAT(fecha_hora_concepto,'%d-%m-%Y')) as dato
            FROM `dietario` where id_cliente = @id_cliente and borrado = 0";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        if (!$datos) {
            return 0;
        } else {
            return $datos[0]['dato'] !== null ? $datos[0]['dato'] : 0;
        }
    }

    function visitas_totales_historicas($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $busqueda = "";

        if (isset($parametros['nombre']) && isset($parametros['apellidos'])) {
            $busqueda .= " AND C.nomcli = @nombre and C.ape1cli = @apellidos ";

            if (isset($parametros['telefono'])) {
                if (strlen($parametros['telefono'] > 1)) {
                    $busqueda .= " OR (tel1cli = @telefono OR tel2cli = @telefono) ";
                }
            }

            // ... Leemos los registros
            $sentencia_sql = "SELECT COUNT(DISTINCT fecfac) as dato
      FROM clientes_historial_antiguo as C
      WHERE 1=1 " . $busqueda;
            $datos = $AqConexion_model->select($sentencia_sql, $parametros);

            if (!$datos) {
                return 0;
            } else {
                return $datos[0]['dato'];
            }
        } else {
            return 0;
        }
    }

    function visitas_12_meses($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = "SELECT count(distinct DATE_FORMAT(fecha_hora_concepto,'%d-%m-%Y')) as dato
            FROM `dietario` where id_cliente = @id_cliente and borrado = 0 and
            fecha_hora_concepto >= DATE_SUB(CURDATE(),INTERVAL 12 month) and fecha_hora_concepto <= CURDATE()";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        if (!$datos) {
            return 0;
        } else {
            return $datos[0]['dato'] !== null ? $datos[0]['dato'] : 0;
        }
    }

    function visitas_1_mes($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = "SELECT count(distinct DATE_FORMAT(fecha_hora_concepto,'%d-%m-%Y')) as dato
        FROM `dietario` where id_cliente = @id_cliente and borrado = 0 and
        fecha_hora_concepto >= DATE_SUB(CURDATE(),INTERVAL 1 month) and fecha_hora_concepto <= CURDATE()";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        if (!$datos) {
            return 0;
        } else {
            return $datos[0]['dato'] !== null ? $datos[0]['dato'] : 0;
        }
    }

    function visitas_1_semana($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = "SELECT count(distinct DATE_FORMAT(fecha_hora_concepto,'%d-%m-%Y')) as dato
            FROM `dietario` where id_cliente = @id_cliente and borrado = 0 and
            fecha_hora_concepto >= DATE_SUB(CURDATE(),INTERVAL 7 day) and fecha_hora_concepto <= CURDATE()";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        if (!$datos) {
            return 0;
        } else {
            return $datos[0]['dato'] !== null ? $datos[0]['dato'] : 0;
        }
    }

    function visitas_3_meses($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = "SELECT count(distinct DATE_FORMAT(fecha_hora_concepto,'%d-%m-%Y')) as dato
            FROM `dietario` where id_cliente = @id_cliente and borrado = 0 and
            fecha_hora_concepto >= DATE_SUB(CURDATE(),INTERVAL 3 month) and fecha_hora_concepto <= CURDATE()";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        if (!$datos) {
            return 0;
        } else {
            return $datos[0]['dato'] !== null ? $datos[0]['dato'] : 0;
        }
    }

    function dias_desde_primera_visita_historicas($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $busqueda = "";

        if (isset($parametros['nombre']) && isset($parametros['apellidos'])) {
            $busqueda .= " AND C.nomcli = @nombre and C.ape1cli = @apellidos ";

            if (isset($parametros['telefono'])) {
                if (strlen($parametros['telefono'] > 1)) {
                    $busqueda .= " OR (tel1cli = @telefono OR tel2cli = @telefono) ";
                }
            }

            // ... Leemos los registros
            $sentencia_sql = "SELECT DATEDIFF('2016-12-31',STR_TO_DATE(fecfac,'%Y/%m/%d')) as dato
                FROM clientes_historial_antiguo as C
                WHERE 1=1 " . $busqueda . " order by fecfac limit 1 ";
            $datos = $AqConexion_model->select($sentencia_sql, $parametros);

            if (!$datos) {
                return 0;
            } else {
                return $datos[0]['dato'] !== null ? $datos[0]['dato'] : 0;
            }
        } else {
            return 0;
        }
    }

    function dias_desde_primera_visita($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = "SELECT DATEDIFF(CURRENT_TIMESTAMP,fecha_hora_concepto) as dato
            FROM `dietario` WHERE id_cliente = @id_cliente and estado = 'Pagado'
            order by fecha_hora_concepto limit 1 ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        if (!$datos) {
            return 0;
        } else {
            return $datos[0]['dato'] !== null ? $datos[0]['dato'] : 0;
        }
    }

    function facturacion_total($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = "SELECT sum(pagado_efectivo+pagado_tarjeta+pagado_habitacion) as facturacion_total
            FROM `dietario` WHERE id_cliente = @id_cliente and borrado = 0
            and (estado = 'Pagado' or estado = 'Devuelto') ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);
        if (!$datos) {
            return 0;
        } else {
            return $datos[0]['facturacion_total'] !== null ? $datos[0]['facturacion_total'] : 0;
        }
    }

    function facturacion_servicios_total($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = "SELECT sum(pagado_efectivo+pagado_tarjeta+pagado_habitacion) as dato
            FROM `dietario` WHERE id_cliente = @id_cliente
            and (id_servicio > 0 or id_carnet > 0)
            and borrado = 0 and (estado = 'Pagado' or estado = 'Devuelto')";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        if (!$datos) {
            return 0;
        } else {
            return $datos[0]['dato'] !== null ? $datos[0]['dato'] : 0;
        }
    }

    function facturacion_servicios_productos_recargas_total($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = "SELECT sum(pagado_efectivo+pagado_tarjeta+pagado_habitacion) as dato
            FROM `dietario` WHERE id_cliente = @id_cliente
            and (id_servicio > 0 or (id_carnet > 0 and recarga = 1) or id_producto > 0)
            and borrado = 0 and (estado = 'Pagado' or estado = 'Devuelto')";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        if (!$datos) {
            return 0;
        } else {
            return $datos[0]['dato'] !== null ? $datos[0]['dato'] : 0;
        }
    }

    function facturacion_productos_total($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = "SELECT sum(pagado_efectivo+pagado_tarjeta+pagado_habitacion) as dato
            FROM `dietario` WHERE id_cliente = @id_cliente and id_producto > 0
            and borrado = 0 and (estado = 'Pagado' or estado = 'Devuelto')";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        if (!$datos) {
            return 0;
        } else {
            return $datos[0]['dato'] !== null ? $datos[0]['dato'] : 0;
        }
    }

    function descuentos_totales_euros($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = "SELECT sum(pagado_efectivo+pagado_tarjeta+pagado_habitacion) as descuentos_total
            FROM `dietario` WHERE id_cliente = @id_cliente and borrado = 0
            and estado = 'Pagado' and (descuento_euros > 0 or descuento_porcentaje > 0) ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        if (!$datos) {
            return 0;
        } else {
            return $datos[0]['descuentos_total'] !== null ? $datos[0]['descuentos_total'] : 0;
        }
    }

    function numero_productos_comprados($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = "SELECT count(id_dietario) as dato FROM `dietario`
            WHERE id_cliente = @id_cliente and borrado = 0 and estado = 'Pagado'
            and id_producto > 0 ORDER BY `id_dietario`  DESC";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        if (!$datos) {
            return 0;
        } else {
            return $datos[0]['dato'] !== null ? $datos[0]['dato'] : 0;
        }
    }

    function empleados_favoritos($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = "SELECT id_empleado,count(id_empleado) as veces,nombre,apellidos
            FROM `dietario`
            left join (usuarios
            left join usuarios_perfiles on usuarios_perfiles.id_usuario = usuarios.id_usuario)
            on usuarios.id_usuario = id_empleado
            WHERE id_cliente = @id_cliente and estado = 'Pagado' and dietario.borrado = 0
            and usuarios_perfiles.id_perfil <> 2
            group by id_empleado
            order by count(id_empleado) desc";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    function centros_visitados($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = "SELECT dietario.id_centro,count(dietario.id_centro) as veces,
            nombre_centro
            FROM `dietario`
            left join centros on centros.id_centro = dietario.id_centro
            WHERE id_cliente = @id_cliente and dietario.estado = 'Pagado' and dietario.borrado = 0
            group by dietario.id_centro order by count(dietario.id_centro) desc";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    function servicios_realizados($parametros)
    {
        $AqConexion_model = new AqConexion_model();
        $importes='';
        if(isset($parametros['importes'])){
            $importes=",dietario.importe_euros,dietario.descuento_euros,dietario.descuento_porcentaje ";
        }
        $condDevuelto='';
        if(array_key_exists('devuelto',$parametros)){
            $condDevuelto=" AND dietario.devuelto = ".$parametros['devuelto']." ";
        }
        // ... Leemos los registros
        $sentencia_sql = "SELECT dietario.id_servicio,count(dietario.id_servicio) as veces,
            nombre_familia,nombre_servicio".$importes."
            FROM `dietario`
            left join (servicios left join servicios_familias on servicios_familias.id_familia_servicio
            = servicios.id_familia_servicio)
            on servicios.id_servicio = dietario.id_servicio
            WHERE id_cliente = @id_cliente and dietario.estado = 'Pagado' and dietario.borrado = 0 ".$condDevuelto."
            and dietario.id_servicio > 0
            group by dietario.id_servicio order by count(dietario.id_servicio) desc";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    function antelacion_anulaciones($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = "SELECT DATE_FORMAT(fecha_hora_concepto,'%Y-%m-%d %H:%i %W') as fecha_cita,
            TIMESTAMPDIFF(minute,fecha_modificacion,fecha_hora_concepto)/60 as horas,estado
            FROM dietario where id_cliente = @id_cliente and (estado = 'Anulada' or estado = 'No vino') and borrado = 0
            order by fecha_hora_concepto desc";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    function carnets_vendidos($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = "SELECT carnets_templos.id_tipo,
            count(carnets_templos.id_tipo) as numero,sum(carnets_templos.precio) as total_precio,
            carnets_templos_tipos.descripcion,carnets_templos.templos
            FROM `carnets_templos`
            left join carnets_templos_tipos on carnets_templos_tipos.id_tipo = carnets_templos.id_tipo
            WHERE id_cliente = @id_cliente and carnets_templos.borrado = 0
            group by carnets_templos.id_tipo order by count(carnets_templos.id_tipo) desc,carnets_templos.id_tipo";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    function pago_con_templos($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = "SELECT sum(templos) as dato FROM `dietario`
            where id_cliente = @id_cliente and estado = 'Pagado'
            and tipo_pago = '#templos'";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        if (!$datos) {
            return 0;
        } else {
            return $datos[0]['dato'] !== null ? $datos[0]['dato'] : 0;
        }
    }

    function porcentaje_pagado_templos($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = "select
            (SELECT count(id_dietario) FROM `dietario`
            where id_cliente = @id_cliente and estado = 'Pagado'
            and id_servicio > 0) as todos_servicios,
            (SELECT count(id_dietario) FROM `dietario`
            where id_cliente = @id_cliente and estado = 'Pagado'
            and tipo_pago = '#templos' and id_servicio > 0) as con_templos,
            (SELECT count(id_dietario) FROM `dietario`
            where id_cliente = @id_cliente and estado = 'Pagado'
            and tipo_pago != '#templos' and id_servicio > 0) as dinero";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        if ($datos[0]['todos_servicios'] > 0) {
            $r = ($datos[0]['con_templos'] * 100) / $datos[0]['todos_servicios'];
        } else {
            $r = 0;
        }

        return $r;
    }

    function historial_antiguo($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $busqueda = "";

        if (isset($parametros['nombre']) && isset($parametros['apellidos'])) {
            $busqueda .= " AND C.nomcli = @nombre and C.ape1cli = @apellidos ";

            if (isset($parametros['telefono'])) {
                if (strlen($parametros['telefono'] > 1)) {
                    $busqueda .= " OR (tel1cli = @telefono OR tel2cli = @telefono) ";
                }
            }

            // ... Leemos los registros
            $sentencia_sql = "SELECT C.ejefac,C.serfac,C.numfac,C.fecfac,C.codcli,
      C.totfac,C.totimpiva,C.totimpbas,C.totimpdto,C.linfac,C.codart,
      C.desart,C.preven,C.cant,C.subtot,C.descuento,C.taniva,C.codemp,
      C.forpag1,C.forpag2,C.impcob1,C.impcob2,C.impcam,C.codemptic,
      C.nomcli,C.ape1cli,C.dnicli,C.dircli,C.codposcli,C.pobcli,
      C.procli,C.tel1cli,C.tel2cli,C.email,centros.nombre_centro
      FROM clientes_historial_antiguo as C
      LEFT JOIN centros ON centros.id_centro = C.id_centro
      WHERE 1=1 " . $busqueda . " ORDER BY C.fecfac DESC ";
            $datos = $AqConexion_model->select($sentencia_sql, $parametros);

            return $datos;
        } else {
            return 0;
        }
    }

    function historial_antiguo_facturacion($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $busqueda = "";

        if (isset($parametros['nombre']) && isset($parametros['apellidos'])) {
            $busqueda .= " AND C.nomcli = @nombre and C.ape1cli = @apellidos ";

            if (isset($parametros['telefono'])) {
                if (strlen($parametros['telefono'] > 1)) {
                    $busqueda .= " OR (tel1cli = @telefono OR tel2cli = @telefono) ";
                }
            }

            // ... Leemos los registros
            $sentencia_sql = "SELECT IFNULL(sum(CAST(REPLACE(totfac,',','.') AS DECIMAL(10, 2))),0) as dato
      FROM clientes_historial_antiguo as C
      WHERE 1=1 " . $busqueda;
            $datos = $AqConexion_model->select($sentencia_sql, $parametros);

            if (!$datos) {
                return 0;
            } else {
                return $datos[0]['dato'];
            }
        } else {
            return 0;
        }
    }

    function fusionar_old($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Verificamos que se han marcado clientes para fusionar.
        if (isset($parametros['clientes_marcados'])) {
            // ... Comenzamos a escribir en el fichero de logs
            if ($log = fopen(RUTA_SERVIDOR . "/recursos/logs/fusiones_clientes.log", "a")) {
                fwrite($log, date("d m Y H:m:s") . " - INICIO PROCESO FUSIÓN\n");
            } else {
                echo "No se puede crear el fichero de logs";
                exit;
            }

            $param_vacio['vacio'] = "";

            $busqueda = " AND (";
            foreach ($parametros['clientes_marcados'] as $id_cliente_marcado) {
                unset($param);
                $param['id_cliente'] = $id_cliente_marcado;

                $busqueda .= " id_cliente = '" . $id_cliente_marcado . "' OR ";
            }
            $busqueda = substr($busqueda, 0, -3);
            $busqueda .= " )";

            // ... Leemos el cliente más ANTIGUO de todos los indicados.
            $sentencia_sql = "SELECT id_cliente,fecha_creacion,nombre,apellidos,telefono,
                sexo,fecha_nacimiento,password,activo,email,dni,no_quiere_publicidad
                FROM clientes
                WHERE borrado = 0 " . $busqueda . " order by fecha_creacion,id_cliente limit 1 ";
            $datos = $AqConexion_model->select($sentencia_sql, $param_vacio);

            // ... Indicamos en el log la informacion
            fwrite($log, date("d m Y H:m:s") . " - Cliente más Antiguo (" . $datos[0]['fecha_creacion'] . "): id " . $datos[0]['id_cliente'] . " - nombre: " . $datos[0]['nombre'] . " " . $datos[0]['apellidos'] . " telefono: " . $datos[0]['telefono'] . "\n");

            // ... Leemos el cliente más NUEVO de todos los indicados.
            $sentencia_sql = "SELECT id_cliente,fecha_creacion,nombre,apellidos,telefono,
                sexo,fecha_nacimiento,password,activo,email,dni,no_quiere_publicidad
                FROM clientes
                WHERE borrado = 0 " . $busqueda . " order by activo DESC,fecha_creacion DESC limit 1 ";
            $cliente_mas_nuevo = $AqConexion_model->select($sentencia_sql, $param_vacio);

            // ... Indicamos en el log la informacion
            fwrite($log, date("d m Y H:m:s") . " - Cliente más Nuevo (" . $cliente_mas_nuevo[0]['fecha_creacion'] . "): id " . $cliente_mas_nuevo[0]['id_cliente'] . " - nombre: " . $cliente_mas_nuevo[0]['nombre'] . " " . $cliente_mas_nuevo[0]['apellidos'] . " telefono: " . $cliente_mas_nuevo[0]['telefono'] . "\n");

            unset($param);
            $param['id_cliente_referencia'] = $datos[0]['id_cliente'];

            foreach ($parametros['clientes_marcados'] as $id_cliente_marcado) {
                if ($id_cliente_marcado != $param['id_cliente_referencia']) {
                    $param['id_cliente_marcado'] = $id_cliente_marcado;

                    // ... Aquí vamos asignando todo lo de los clientes a fusionar al cliente
                    // de referencia (el más antiguo)
                    $sentenciaSQL = " update carnets_templos set id_cliente = @id_cliente_referencia
                        where id_cliente = @id_cliente_marcado ";
                    $AqConexion_model->no_select($sentenciaSQL, $param);

                    $sentenciaSQL = " update carnets_templos_historial set id_cliente = @id_cliente_referencia
                        where id_cliente = @id_cliente_marcado ";
                    $AqConexion_model->no_select($sentenciaSQL, $param);

                    $sentenciaSQL = " update carnets_templos_servicios set id_cliente = @id_cliente_referencia
                        where id_cliente = @id_cliente_marcado ";
                    $AqConexion_model->no_select($sentenciaSQL, $param);

                    $sentenciaSQL = " update citas set id_cliente = @id_cliente_referencia
                        where id_cliente = @id_cliente_marcado ";
                    $AqConexion_model->no_select($sentenciaSQL, $param);

                    $sentenciaSQL = " update dietario set id_cliente = @id_cliente_referencia
                        where id_cliente = @id_cliente_marcado ";
                    $AqConexion_model->no_select($sentenciaSQL, $param);

                    $sentenciaSQL = " update clientes_firmas_lopd set id_cliente = @id_cliente_referencia
                        where id_cliente = @id_cliente_marcado ";
                    $AqConexion_model->no_select($sentenciaSQL, $param);

                    $sentenciaSQL = " update presupuestos set id_cliente = @id_cliente_referencia
                        where id_cliente = @id_cliente_marcado ";
                    $AqConexion_model->no_select($sentenciaSQL, $param);

                    $sentenciaSQL = " update presupuestos_items set id_cliente = @id_cliente_referencia
                        where id_cliente = @id_cliente_marcado ";
                    $AqConexion_model->no_select($sentenciaSQL, $param);

                    $sentenciaSQL = " update presupuestos_pagos set id_cliente = @id_cliente_referencia
                        where id_cliente = @id_cliente_marcado ";
                    $AqConexion_model->no_select($sentenciaSQL, $param);

                    // ... Aqui volcamos todas las notas de los clientes a fusionar al de referencia.
                    // de tal forma que se queda con sus propias notas, mas las de los demas.
                    $param2['id_cliente'] = $id_cliente_marcado;
                    $cliente_marcado = $this->leer_clientes($param2);

                    $param['notas_cliente_marcado'] = $cliente_marcado[0]['notas'];

                    $sentenciaSQL = " update clientes set notas = CONCAT(notas, '\n', @notas_cliente_marcado)
                        where id_cliente = @id_cliente_referencia ";
                    $AqConexion_model->no_select($sentenciaSQL, $param);

                    // ... Indicamos en el log la informacion
                    fwrite($log, date("d m Y H:m:s") . " - Copiando datos del cliente, id " . $cliente_marcado[0]['id_cliente'] . " - nombre: " . $cliente_marcado[0]['nombre'] . " " . $cliente_marcado[0]['apellidos'] . " telefono: " . $cliente_marcado[0]['telefono'] . " al cliente más antiguo.\n");

                    // ... Aqui rescribimos los datos del clientes más nuevo, al viejo,
                    // pero solo nombre, apellidos y telefono y en caso de que tenga algo.
                    //
                    // ... NOMBRE
                    if ($cliente_mas_nuevo[0]['nombre'] != "") {
                        unset($param_nuevo);
                        $param_nuevo['nombre'] = $cliente_mas_nuevo[0]['nombre'];
                        $param_nuevo['id_cliente_referencia'] = $datos[0]['id_cliente'];

                        $sentenciaSQL = " update clientes set nombre = @nombre
                            where id_cliente = @id_cliente_referencia ";
                        $AqConexion_model->no_select($sentenciaSQL, $param_nuevo);
                    }
                    // ... APELLIDOS
                    if ($cliente_mas_nuevo[0]['apellidos'] != "") {
                        unset($param_nuevo);
                        $param_nuevo['apellidos'] = $cliente_mas_nuevo[0]['apellidos'];
                        $param_nuevo['id_cliente_referencia'] = $datos[0]['id_cliente'];

                        $sentenciaSQL = " update clientes set apellidos = @apellidos
                            where id_cliente = @id_cliente_referencia ";
                        $AqConexion_model->no_select($sentenciaSQL, $param_nuevo);
                    }
                    // ... TELEFONO
                    if ($cliente_mas_nuevo[0]['telefono'] != "") {
                        unset($param_nuevo);
                        $param_nuevo['telefono'] = $cliente_mas_nuevo[0]['telefono'];
                        $param_nuevo['id_cliente_referencia'] = $datos[0]['id_cliente'];

                        $sentenciaSQL = " update clientes set telefono = @telefono
                            where id_cliente = @id_cliente_referencia ";
                        $AqConexion_model->no_select($sentenciaSQL, $param_nuevo);
                    }

                    // ... EMAIL
                    if ($cliente_mas_nuevo[0]['email'] != "") {
                        unset($param_nuevo);
                        $param_nuevo['email'] = $cliente_mas_nuevo[0]['email'];
                        $param_nuevo['id_cliente_referencia'] = $datos[0]['id_cliente'];

                        $sentenciaSQL = " update clientes set email = @email
                            where id_cliente = @id_cliente_referencia ";
                        $AqConexion_model->no_select($sentenciaSQL, $param_nuevo);
                    }

                    // ... DNI
                    if ($cliente_mas_nuevo[0]['dni'] != "") {
                        unset($param_nuevo);
                        $param_nuevo['dni'] = $cliente_mas_nuevo[0]['dni'];
                        $param_nuevo['id_cliente_referencia'] = $datos[0]['id_cliente'];

                        $sentenciaSQL = " update clientes set dni = @dni
                            where id_cliente = @id_cliente_referencia ";
                        $AqConexion_model->no_select($sentenciaSQL, $param_nuevo);
                    }

                    // ... NO QUIERE PUBLICIDAD
                    if ($cliente_mas_nuevo[0]['no_quiere_publicidad'] == 1) {
                        unset($param_nuevo);
                        $param_nuevo['no_quiere_publicidad'] = $cliente_mas_nuevo[0]['no_quiere_publicidad'];
                        $param_nuevo['id_cliente_referencia'] = $datos[0]['id_cliente'];

                        $sentenciaSQL = " update clientes set no_quiere_publicidad = @no_quiere_publicidad
                            where id_cliente = @id_cliente_referencia ";
                        $AqConexion_model->no_select($sentenciaSQL, $param_nuevo);
                    }

                    // ... SEXO
                    if ($cliente_mas_nuevo[0]['sexo'] != "") {
                        unset($param_nuevo);
                        $param_nuevo['sexo'] = $cliente_mas_nuevo[0]['sexo'];
                        $param_nuevo['id_cliente_referencia'] = $datos[0]['id_cliente'];

                        $sentenciaSQL = " update clientes set sexo = @sexo
                            where id_cliente = @id_cliente_referencia ";
                        $AqConexion_model->no_select($sentenciaSQL, $param_nuevo);
                    }

                    // ... FECHA NACIMIENTO
                    if ($cliente_mas_nuevo[0]['fecha_nacimiento'] != "") {
                        unset($param_nuevo);
                        $param_nuevo['fecha_nacimiento'] = $cliente_mas_nuevo[0]['fecha_nacimiento'];
                        $param_nuevo['id_cliente_referencia'] = $datos[0]['id_cliente'];

                        $sentenciaSQL = " update clientes set fecha_nacimiento = @fecha_nacimiento
                        where id_cliente = @id_cliente_referencia ";
                        $AqConexion_model->no_select($sentenciaSQL, $param_nuevo);
                    }

                    // ... PASSWORD
                    if ($cliente_mas_nuevo[0]['password'] != "") {
                        unset($param_nuevo);
                        $param_nuevo['password'] = $cliente_mas_nuevo[0]['password'];
                        $param_nuevo['id_cliente_referencia'] = $datos[0]['id_cliente'];

                        $sentenciaSQL = " update clientes set password = @password
                        where id_cliente = @id_cliente_referencia ";
                        $AqConexion_model->no_select($sentenciaSQL, $param_nuevo);
                    }

                    // ... ACTIVO
                    if ($cliente_mas_nuevo[0]['activo'] != "") {
                        unset($param_nuevo);
                        $param_nuevo['activo'] = $cliente_mas_nuevo[0]['activo'];
                        $param_nuevo['id_cliente_referencia'] = $datos[0]['id_cliente'];

                        $sentenciaSQL = " update clientes set activo = @activo
                        where id_cliente = @id_cliente_referencia ";
                        $AqConexion_model->no_select($sentenciaSQL, $param_nuevo);
                    }

                    //... Borramos los clientes fusionados, es decir todos menos el de referencia
                    // que es el mas antiguo
                    $this->borrar_cliente($param2);

                    fwrite($log, date("d m Y H:m:s") . " - Marcado como borrado el cliente, id " . $cliente_marcado[0]['id_cliente'] . " - nombre: " . $cliente_marcado[0]['nombre'] . " " . $cliente_marcado[0]['apellidos'] . " telefono: " . $cliente_marcado[0]['telefono'] . " una vez procesado.\n");
                }
            }

            // ... Fusionamos a los clientes del historial, leemos los datos
            // del cliente que ha quedado definitivo de la fusion
            unset($param3);
            $param3['id_cliente'] = $datos[0]['id_cliente'];
            $sentencia_sql = "SELECT id_cliente,fecha_creacion,nombre,apellidos,telefono
                FROM clientes
                WHERE id_cliente = @id_cliente ";
            $cliente_fusionado = $AqConexion_model->select($sentencia_sql, $param3);

            foreach ($parametros['clientes_marcados'] as $id_cliente_marcado) {
                unset($param2);
                $param2['id_cliente'] = $id_cliente_marcado;
                $sentencia_sql = "SELECT id_cliente,fecha_creacion,nombre,apellidos,telefono
                    FROM clientes
                    WHERE id_cliente = @id_cliente ";
                $cliente_marcado = $AqConexion_model->select($sentencia_sql, $param2);

                // ... Asignamos los datos del cliente fusionado a los clientes que se han marcado
                // para la fusion y que luego quedan borrados.
                unset($param3);
                $param3['nombre'] = $cliente_fusionado[0]['nombre'];
                $param3['apellidos'] = $cliente_fusionado[0]['apellidos'];
                $param3['telefono'] = $cliente_fusionado[0]['telefono'];
                $param3['nomantiguo'] = $cliente_marcado[0]['nombre'];
                $param3['apeantiguos'] = $cliente_marcado[0]['apellidos'];

                $sentenciaSQL = " update clientes_historial_antiguo set nomcli = @nombre,
                    ape1cli = @apellidos, tel2cli = @telefono
                    where nomcli = @nomantiguo and ape1cli = @apeantiguos ";
                $AqConexion_model->no_select($sentenciaSQL, $param3);
            }

            // ... Cerramos el fichero de logs.
            fwrite($log, date("d m Y H:m:s") . " - FIN PROCESO FUSIÓN\n");
            fclose($log);

            return 1;
        }
        // ... Sino se ha elegido nada para fusionar, entonces devuelvo 0.
        else {
            return 0;
        }
    }

    function fusionar($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Verificamos que se han marcado clientes para fusionar.
        if (isset($parametros['clientes_marcados'])) {
            // ... Comenzamos a escribir en el fichero de logs
            if ($log = fopen(RUTA_SERVIDOR . "/recursos/logs/fusiones_clientes.log", "a")) {
                fwrite($log, date("d m Y H:m:s") . " - INICIO PROCESO FUSIÓN\n");
            } else {
                echo "No se puede crear el fichero de logs";
                exit;
            }

            $param_vacio['vacio'] = "";

            $busqueda = " AND (";
            foreach ($parametros['clientes_marcados'] as $id_cliente_marcado) {
                unset($param);
                $param['id_cliente'] = $id_cliente_marcado;

                $busqueda .= " id_cliente = '" . $id_cliente_marcado . "' OR ";
            }
            $busqueda = substr($busqueda, 0, -3);
            $busqueda .= " )";

            // ... Leemos el cliente más ANTIGUO de todos los indicados.
            $sentencia_sql = "SELECT id_cliente,fecha_creacion,nombre,apellidos,telefono,
                sexo,fecha_nacimiento,password,activo,email,dni,no_quiere_publicidad
                FROM clientes
                WHERE borrado = 0 " . $busqueda . " order by fecha_creacion,id_cliente limit 1 ";
            $datos = $AqConexion_model->select($sentencia_sql, $param_vacio);

            // ... Indicamos en el log la informacion
            fwrite($log, date("d m Y H:m:s") . " - Cliente más Antiguo (" . $datos[0]['fecha_creacion'] . "): id " . $datos[0]['id_cliente'] . " - nombre: " . $datos[0]['nombre'] . " " . $datos[0]['apellidos'] . " telefono: " . $datos[0]['telefono'] . "\n");

            // ... Leemos el cliente más NUEVO de todos los indicados.
            /*$sentencia_sql = "SELECT id_cliente,fecha_creacion,nombre,apellidos,telefono,
                sexo,fecha_nacimiento,password,activo,email,dni,no_quiere_publicidad
                FROM clientes
                WHERE borrado = 0 " . $busqueda . " order by activo DESC,fecha_creacion DESC limit 1 ";*/
            $sentencia_sql = "SELECT *
                FROM clientes
                WHERE borrado = 0 " . $busqueda . " order by activo DESC,fecha_creacion DESC limit 1 ";
            $cliente_mas_nuevo = $AqConexion_model->select($sentencia_sql, $param_vacio);

            // ... Indicamos en el log la informacion
            fwrite($log, date("d m Y H:m:s") . " - Cliente más Nuevo (" . $cliente_mas_nuevo[0]['fecha_creacion'] . "): id " . $cliente_mas_nuevo[0]['id_cliente'] . " - nombre: " . $cliente_mas_nuevo[0]['nombre'] . " " . $cliente_mas_nuevo[0]['apellidos'] . " telefono: " . $cliente_mas_nuevo[0]['telefono'] . "\n");

            unset($param);
            $param['id_cliente_referencia'] = $datos[0]['id_cliente'];
            $id_cliente_referencia = $datos[0]['id_cliente'];

            // se buscan todas las tablas de la base de datos que tengan la columna id_cleinte excepto la cde clientes
            $database_name = $this->db->database;
            $sql = "SELECT table_name
            FROM information_schema.tables
            WHERE table_schema = '$database_name'
            AND table_type = 'BASE TABLE'
            AND table_name IN (
                SELECT table_name
                FROM information_schema.columns
                WHERE table_schema = '$database_name'
                AND column_name = 'id_cliente'
                AND table_name != 'clientes'
            )";
            
            $tables = $this->db->query($sql)->result();
            $tablas = [];
            foreach ($tables as $key => $value) {
                $tablas[] = $value->table_name;
            }
            
            foreach ($parametros['clientes_marcados'] as $id_cliente_marcado) {
                if ($id_cliente_marcado != $param['id_cliente_referencia']) {
                    $param['id_cliente_marcado'] = $id_cliente_marcado;

                    $param2['id_cliente'] = $id_cliente_marcado;
                    $cliente_marcado = $this->leer_clientes($param2);

                    if($cliente_marcado[0]['notas'] != ''){
                        $param['notas_cliente_marcado'] = $cliente_marcado[0]['notas'];
                        $sentenciaSQL = " update clientes set notas = CONCAT(notas, '\n', @notas_cliente_marcado)
                            where id_cliente = @id_cliente_referencia ";
                        $AqConexion_model->no_select($sentenciaSQL, $param);
                    }

                    // ... Indicamos en el log la informacion
                    fwrite($log, date("d m Y H:m:s") . " - Copiando datos del cliente, id " . $cliente_marcado[0]['id_cliente'] . " - nombre: " . $cliente_marcado[0]['nombre'] . " " . $cliente_marcado[0]['apellidos'] . " telefono: " . $cliente_marcado[0]['telefono'] . " al cliente más antiguo.\n");

                    $columnas_clientes = array("nombre","apellidos","sexo","fecha_nacimiento","email","password","telefono","direccion","codigo_postal", "dni","no_quiere_publicidad","recordatorio_sms","recordatorio_email","empresa","cif_nif","direccion_facturacion","codigo_postal_facturacion","localidad_facturacion","provincia_facturacion","notificaciones","google_contacts","ocupacion","nombre_tutor","dni_tutor","como_conocio", "activo");

                    $datos_actualizar_cliente = [];
                    foreach ($columnas_clientes as $columnas_clientes_val) {
                        if ($cliente_mas_nuevo[0][$columnas_clientes_val] != "") {
                            $datos_actualizar_cliente[$columnas_clientes_val] = $cliente_mas_nuevo[0][$columnas_clientes_val];
                        }
                    }

                    $this->db->where('id_cliente', $id_cliente_referencia);
                    $this->db->update('clientes', $datos_actualizar_cliente);
                    
                    foreach ($tablas as $key => $tabla) {
                        $updatedata["id_cliente"] = $id_cliente_referencia;
                        $this->db->where('id_cliente', $id_cliente_marcado);
                        $this->db->update($tabla, $updatedata);
                    }
                    $this->borrar_cliente($param2);
                    fwrite($log, date("d m Y H:m:s") . " - Marcado como borrado el cliente, id " . $cliente_marcado[0]['id_cliente'] . " - nombre: " . $cliente_marcado[0]['nombre'] . " " . $cliente_marcado[0]['apellidos'] . " telefono: " . $cliente_marcado[0]['telefono'] . " una vez procesado.\n");
                }
            }

            // ... Fusionamos a los clientes del historial, leemos los datos
            // del cliente que ha quedado definitivo de la fusion
            unset($param3);
            $param3['id_cliente'] = $datos[0]['id_cliente'];
            $sentencia_sql = "SELECT id_cliente,fecha_creacion,nombre,apellidos,telefono
                FROM clientes
                WHERE id_cliente = @id_cliente ";
            $cliente_fusionado = $AqConexion_model->select($sentencia_sql, $param3);

            foreach ($parametros['clientes_marcados'] as $id_cliente_marcado) {
                unset($param2);
                $param2['id_cliente'] = $id_cliente_marcado;
                $sentencia_sql = "SELECT id_cliente,fecha_creacion,nombre,apellidos,telefono
                    FROM clientes
                    WHERE id_cliente = @id_cliente ";
                $cliente_marcado = $AqConexion_model->select($sentencia_sql, $param2);

                // ... Asignamos los datos del cliente fusionado a los clientes que se han marcado
                // para la fusion y que luego quedan borrados.
                unset($param3);
                $param3['nombre'] = $cliente_fusionado[0]['nombre'];
                $param3['apellidos'] = $cliente_fusionado[0]['apellidos'];
                $param3['telefono'] = $cliente_fusionado[0]['telefono'];
                $param3['nomantiguo'] = $cliente_marcado[0]['nombre'];
                $param3['apeantiguos'] = $cliente_marcado[0]['apellidos'];

                $sentenciaSQL = " update clientes_historial_antiguo set nomcli = @nombre,
                    ape1cli = @apellidos, tel2cli = @telefono
                    where nomcli = @nomantiguo and ape1cli = @apeantiguos ";
                $AqConexion_model->no_select($sentenciaSQL, $param3);
            }

            // ... Cerramos el fichero de logs.
            fwrite($log, date("d m Y H:m:s") . " - FIN PROCESO FUSIÓN\n");
            fclose($log);

            return 1;
        }
        // ... Sino se ha elegido nada para fusionar, entonces devuelvo 0.
        else {
            return 0;
        }
    }

    function arreglar_fusiones($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Verificamos que se han marcado clientes para fusionar.
        if (isset($parametros['clientes_marcados'])) {
            // ... Comenzamos a escribir en el fichero de logs
            if ($log = fopen(RUTA_SERVIDOR . "/recursos/logs/fusiones_clientes_arreglos.log", "a")) {
                fwrite($log, date("d m Y H:m:s") . " - INICIO PROCESO FUSIÓN\n");
            } else {
                echo "No se puede crear el fichero de logs";
                exit;
            }

            $param_vacio['vacio'] = "";

            $busqueda = " AND (";
            foreach ($parametros['clientes_marcados'] as $id_cliente_marcado) {
                unset($param);
                $param['id_cliente'] = $id_cliente_marcado;

                $busqueda .= " id_cliente = '" . $id_cliente_marcado . "' OR ";
            }
            $busqueda = substr($busqueda, 0, -3);
            $busqueda .= " )";

            // ... Leemos el cliente más ANTIGUO de todos los indicados.
            $sentencia_sql = "SELECT id_cliente,fecha_creacion,nombre,apellidos,telefono,
                sexo,fecha_nacimiento,password,activo,email,dni,no_quiere_publicidad
                FROM clientes
                WHERE 1=1 " . $busqueda . " order by fecha_creacion,id_cliente limit 1 ";
            $datos = $AqConexion_model->select($sentencia_sql, $param_vacio);

            // ... Indicamos en el log la informacion
            fwrite($log, date("d m Y H:m:s") . " - Cliente más Antiguo (" . $datos[0]['fecha_creacion'] . "): id " . $datos[0]['id_cliente'] . " - nombre: " . $datos[0]['nombre'] . " " . $datos[0]['apellidos'] . " telefono: " . $datos[0]['telefono'] . "\n");

            // ... Leemos el cliente más NUEVO de todos los indicados.
            /*$sentencia_sql = "SELECT id_cliente,fecha_creacion,nombre,apellidos,telefono,
                sexo,fecha_nacimiento,password,activo,email,dni,no_quiere_publicidad
                FROM clientes
                WHERE borrado = 0 " . $busqueda . " order by activo DESC,fecha_creacion DESC limit 1 ";*/
            $sentencia_sql = "SELECT *
                FROM clientes
                WHERE 1=1 " . $busqueda . " order by activo DESC,fecha_creacion DESC limit 1 ";
            $cliente_mas_nuevo = $AqConexion_model->select($sentencia_sql, $param_vacio);

            // ... Indicamos en el log la informacion
            fwrite($log, date("d m Y H:m:s") . " - Cliente más Nuevo (" . $cliente_mas_nuevo[0]['fecha_creacion'] . "): id " . $cliente_mas_nuevo[0]['id_cliente'] . " - nombre: " . $cliente_mas_nuevo[0]['nombre'] . " " . $cliente_mas_nuevo[0]['apellidos'] . " telefono: " . $cliente_mas_nuevo[0]['telefono'] . "\n");

            unset($param);
            $param['id_cliente_referencia'] = $datos[0]['id_cliente'];
            $id_cliente_referencia = $datos[0]['id_cliente'];

            // se buscan todas las tablas de la base de datos que tengan la columna id_cleinte excepto la cde clientes
            $database_name = $this->db->database;
            $sql = "SELECT table_name
            FROM information_schema.tables
            WHERE table_schema = '$database_name'
            AND table_type = 'BASE TABLE'
            AND table_name IN (
                SELECT table_name
                FROM information_schema.columns
                WHERE table_schema = '$database_name'
                AND column_name = 'id_cliente'
                AND table_name != 'clientes'
            )";
            
            $tables = $this->db->query($sql)->result();
            $tablas = [];
            foreach ($tables as $key => $value) {
                $tablas[] = $value->table_name;
            }
            
            foreach ($parametros['clientes_marcados'] as $id_cliente_marcado) {
                if ($id_cliente_marcado != $param['id_cliente_referencia']) {
                    $param['id_cliente_marcado'] = $id_cliente_marcado;

                    $param2['id_cliente'] = $id_cliente_marcado;
                    $cliente_marcado = $this->leer_clientes($param2);

                    if($cliente_marcado[0]['notas'] != ''){
                        $param['notas_cliente_marcado'] = $cliente_marcado[0]['notas'];
                        $sentenciaSQL = " update clientes set notas = CONCAT(notas, '\n', @notas_cliente_marcado)
                            where id_cliente = @id_cliente_referencia ";
                        $AqConexion_model->no_select($sentenciaSQL, $param);
                    }

                    // ... Indicamos en el log la informacion
                    fwrite($log, date("d m Y H:m:s") . " - Copiando datos del cliente, id " . $cliente_marcado[0]['id_cliente'] . " - nombre: " . $cliente_marcado[0]['nombre'] . " " . $cliente_marcado[0]['apellidos'] . " telefono: " . $cliente_marcado[0]['telefono'] . " al cliente más antiguo.\n");

                    $columnas_clientes = array("nombre","apellidos","sexo","fecha_nacimiento","email","password","telefono","direccion","codigo_postal", "dni","no_quiere_publicidad","recordatorio_sms","recordatorio_email","empresa","cif_nif","direccion_facturacion","codigo_postal_facturacion","localidad_facturacion","provincia_facturacion","notificaciones","google_contacts","ocupacion","nombre_tutor","dni_tutor","como_conocio", "activo");

                    $datos_actualizar_cliente = [];
                    foreach ($columnas_clientes as $columnas_clientes_val) {
                        if ($cliente_mas_nuevo[0][$columnas_clientes_val] != "") {
                            $datos_actualizar_cliente[$columnas_clientes_val] = $cliente_mas_nuevo[0][$columnas_clientes_val];
                        }
                    }

                    $this->db->where('id_cliente', $id_cliente_referencia);
                    $this->db->update('clientes', $datos_actualizar_cliente);
                    
                    foreach ($tablas as $key => $tabla) {
                        $updatedata["id_cliente"] = $id_cliente_referencia;
                        $this->db->where('id_cliente', $id_cliente_marcado);
                        $this->db->update($tabla, $updatedata);
                    }
                    $this->borrar_cliente($param2);
                    fwrite($log, date("d m Y H:m:s") . " - Marcado como borrado el cliente, id " . $cliente_marcado[0]['id_cliente'] . " - nombre: " . $cliente_marcado[0]['nombre'] . " " . $cliente_marcado[0]['apellidos'] . " telefono: " . $cliente_marcado[0]['telefono'] . " una vez procesado.\n");
                }
            }

            // ... Fusionamos a los clientes del historial, leemos los datos
            // del cliente que ha quedado definitivo de la fusion
            unset($param3);
            $param3['id_cliente'] = $datos[0]['id_cliente'];
            $sentencia_sql = "SELECT id_cliente,fecha_creacion,nombre,apellidos,telefono
                FROM clientes
                WHERE id_cliente = @id_cliente ";
            $cliente_fusionado = $AqConexion_model->select($sentencia_sql, $param3);

            foreach ($parametros['clientes_marcados'] as $id_cliente_marcado) {
                unset($param2);
                $param2['id_cliente'] = $id_cliente_marcado;
                $sentencia_sql = "SELECT id_cliente,fecha_creacion,nombre,apellidos,telefono
                    FROM clientes
                    WHERE id_cliente = @id_cliente ";
                $cliente_marcado = $AqConexion_model->select($sentencia_sql, $param2);

                // ... Asignamos los datos del cliente fusionado a los clientes que se han marcado
                // para la fusion y que luego quedan borrados.
                unset($param3);
                $param3['nombre'] = $cliente_fusionado[0]['nombre'];
                $param3['apellidos'] = $cliente_fusionado[0]['apellidos'];
                $param3['telefono'] = $cliente_fusionado[0]['telefono'];
                $param3['nomantiguo'] = $cliente_marcado[0]['nombre'];
                $param3['apeantiguos'] = $cliente_marcado[0]['apellidos'];

                $sentenciaSQL = " update clientes_historial_antiguo set nomcli = @nombre,
                    ape1cli = @apellidos, tel2cli = @telefono
                    where nomcli = @nomantiguo and ape1cli = @apeantiguos ";
                $AqConexion_model->no_select($sentenciaSQL, $param3);
            }

            // ... Cerramos el fichero de logs.
            fwrite($log, date("d m Y H:m:s") . " - FIN PROCESO FUSIÓN\n");
            fclose($log);

            return 1;
        }
        // ... Sino se ha elegido nada para fusionar, entonces devuelvo 0.
        else {
            return 0;
        }
    }

    // -------------------------------------------------------------------
    // ... IMPORTACION
    // -------------------------------------------------------------------
    function importar()
    {
        if (($gestor = fopen(RUTA_SERVIDOR . "/recursos/clientes_princesa.csv", "r")) !== FALSE) {
            $i = 0;
            $paso = 0;

            while (($datos = fgetcsv($gestor, 1000, ";")) !== FALSE) {
                if ($i > 0) {
                    unset($param);
                    $param['nombre'] = $datos[1];
                    $param['apellidos'] = $datos[2];
                    $param['telefono'] = $datos[4];
                    $cliente = $this->Clientes_model->leer_clientes_importar($param);

                    $id_cliente = 0;
                    if (isset($cliente[0]['id_cliente'])) {
                        $id_cliente = $cliente[0]['id_cliente'];
                    }

                    if ($id_cliente == 0) {
                        echo $id_cliente . " " . $param['nombre'] . " " . $param['apellidos'] . " " . $param['telefono'] . "<br>";

                        $registro['codigo_cliente'] = $datos[0] . "-PRI";
                        $registro['nombre'] = $datos[1];
                        $registro['apellidos'] = $datos[2];
                        $registro['email'] = $datos[3];
                        $registro['telefono'] = $datos[4];
                        $registro['direccion'] = $datos[5];
                        $registro['codigo_postal'] = $datos[6];
                        $registro['fecha_creacion'] = $datos[8] . " 00:00:00";
                        $registro['id_usuario_creacion'] = 1;
                        $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
                        $registro['id_usuario_modificacion'] = 1;
                        $registro['borrado'] = 0;

                        $paso++;
                        $this->nuevo_cliente($registro);
                    }
                }
                $i++;
            }

            fclose($gestor);
        }

        echo $paso;

        return 1;
    }

    function leer_clientes_importar($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $busqueda = "";

        if (isset($parametros['nombre'])) {
            $busqueda .= " AND clientes.nombre = '" . $parametros['nombre'] . "' ";
        }

        if (isset($parametros['apellidos'])) {
            $busqueda .= " AND clientes.apellidos = '" . $parametros['apellidos'] . "' ";
        }

        if (isset($parametros['telefono'])) {
            $busqueda .= " AND clientes.telefono = '" . $parametros['telefono'] . "' ";
        }

        // ... Leemos los registros
        unset($parametros);
        $parametros['vacio'] = 0;
        $sentencia_sql = "SELECT id_cliente FROM clientes
    WHERE clientes.borrado = 0 " . $busqueda;
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    function clientes_json($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $busqueda = "";

        /*if (isset($parametros['q'])) {
            $busqueda.=" AND CONCAT(TRIM(clientes.nombre), ' ', TRIM(clientes.apellidos), ' ', TRIM(clientes.telefono)) like '%".$parametros['q']."%' ";
            }*/

        if (isset($parametros['q'])) {
            //Alfonso 2-7-2024
            //buscar por nombre sin importar el orden
            $parametros['q'] = trim($parametros['q']);
            $palabras = explode(" ", $parametros['q']);
            $condiciones = array();
            foreach ($palabras as $palabra) {
            $condiciones[] = "CONCAT(TRIM(clientes.nombre), ' ', TRIM(clientes.apellidos), ' ', TRIM(clientes.telefono)) LIKE '%" . $palabra . "%'";
            }
            $busqueda .= " AND (" . implode(" AND ", $condiciones) . ")";
        }

        // ... Leemos los registros
        $sentencia_sql = "SELECT clientes.id_cliente as id,
            CONCAT(clientes.nombre, ' ', clientes.apellidos, ' (', clientes.telefono, ')') as name,
            CONCAT(clientes.nombre, ' ', clientes.apellidos, ' (', clientes.telefono, ')') as text
            FROM clientes
            WHERE clientes.borrado = 0 " . $busqueda . " ORDER BY nombre,apellidos  "; //ORDER BY  clientes.id_cliente
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);
        //$datos2=str_replace("+","",$datos);
        return $datos;
    }

    function condicion_busqueda($que_condicion)
    {

        $condicion = " = "; // ... por defecto.

        if ($que_condicion == "Mas") {
            $condicion = " > ";
        }
        if ($que_condicion == "Igual") {
            $condicion = " = ";
        }
        if ($que_condicion == "Menos") {
            $condicion = " < ";
        }
        if ($que_condicion == "Menos") {
            $condicion = " < ";
        }
        if ($que_condicion == "Mayor_igual") {
            $condicion = " >= ";
        }
        if ($que_condicion == "Menos_igual") {
            $condicion = " <= ";
        }

        return $condicion;
    }

    function clientes_ultimos_centros_genera($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Guardo la hora de comienzo del proceso.
        if (($gestor = fopen(RUTA_SERVIDOR . "/recursos/logs/clientes_ultimos_centros.log", "a")) !== FALSE) {
            fwrite($gestor, "Comienza: " . date("Y-m-d H:i:s") . "\n");
            fclose($gestor);
        }

        // ... Leemos los clientes distintos que hayan tenido actividad en el dietario
        // desde hace 1 dia.
        $sentencia_sql = "SELECT DISTINCT id_cliente as id_cliente FROM dietario
        where borrado = 0 and estado = 'Pagado' and fecha_pagado > NOW() - INTERVAL 1 DAY ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        // ... EjectutaMos cada sentenciaSQL de cada insert de cliente.
        if ($datos > 0) {
            foreach ($datos as $row) {
                # ... Borramos el anterior registro del cliente y luego hacemos insert de nuevo.
                $sentencia_sql = "DELETE FROM clientes_ultimos_centros WHERE id_cliente = '" . $row['id_cliente'] . "'";
                $ok = $AqConexion_model->no_select_sinpreparar($sentencia_sql);

                # ... Hacemos el insert.
                $linea = "INSERT INTO clientes_ultimos_centros (fecha, id_cliente, id_centro, id_empleado,id_servicio, id_producto, id_carnet, id_usuario_modificacion, fecha_creacion) select fecha_hora_concepto,id_cliente,id_centro,id_empleado,id_servicio,id_producto,id_carnet,id_usuario_modificacion,now() from dietario where borrado = 0 and dietario.estado = 'Pagado' and id_cliente = '" . $row['id_cliente'] . "' order by dietario.fecha_hora_concepto desc limit 1;";
                $r = $AqConexion_model->no_select_sinpreparar($linea);
            }
        }

        // ... Guardo la hora en la que finaliza el proceso.
        if (($gestor = fopen(RUTA_SERVIDOR . "/recursos/logs/clientes_ultimos_centros.log", "a")) !== FALSE) {
            fwrite($gestor, "Finaliza: " . date("Y-m-d H:i:s") . "\n");
            fclose($gestor);
        }
    }

    //
    // NOTAS CITAS
    //
    function notas_citas($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $busqueda = "";

        if (isset($parametros['id_nota_cita'])) {
            $busqueda .= " AND CN.id_nota_cita = @id_nota_cita ";
        }

        if (isset($parametros['id_cliente'])) {
            $busqueda .= " AND CN.id_cliente = @id_cliente ";
        }

        if (isset($parametros['estado'])) {
            $busqueda .= " AND CN.estado = @estado ";
        }

        // ... Leemos los registros
        $sentencia_sql = "SELECT CN.id_nota_cita,CN.id_cliente,
            CN.estado,CN.nota,CN.fecha_creacion,CN.id_usuario_creacion,
            CN.fecha_modificacion,CN.id_usuario_modificacion,CN.fecha_finalizacion,
            CN.id_usuario_finalizacion,CN.borrado,
            CN.fecha_borrado,CN.id_usuario_borrado,
            CONCAT(U1.nombre, ' ', U1.apellidos) As usuario_creacion,
            CONCAT(U2.nombre, ' ', U2.apellidos) As usuario_modificacion,
            CONCAT(U3.nombre, ' ', U3.apellidos) As usuario_finalizacion,
            CONCAT(clientes.nombre, ' ', clientes.apellidos) As cliente,
            DATE_FORMAT(CN.fecha_creacion,'%Y-%m-%d %H:%i') as fecha_creacion_aaaammdd,
            DATE_FORMAT(CN.fecha_creacion,'%d-%m-%Y %H:%i') as fecha_creacion_ddmmaaaa,
            DATE_FORMAT(CN.fecha_modificacion,'%Y-%m-%d %H:%i') as fecha_modificacion_aaaammdd,
            DATE_FORMAT(CN.fecha_modificacion,'%d-%m-%Y %H:%i') as fecha_modificacion_ddmmaaaa,
            DATE_FORMAT(CN.fecha_finalizacion,'%Y-%m-%d %H:%i') as fecha_finalizacion_aaaammdd,
            DATE_FORMAT(CN.fecha_finalizacion,'%d-%m-%Y %H:%i') as fecha_finalizacion_ddmmaaaa
            FROM clientes_notas_citas AS CN
            LEFT JOIN usuarios AS U1 ON U1.id_usuario = CN.id_usuario_creacion
            LEFT JOIN usuarios AS U2 ON U2.id_usuario = CN.id_usuario_modificacion
            LEFT JOIN usuarios AS U3 ON U3.id_usuario = CN.id_usuario_finalizacion
            LEFT JOIN clientes ON clientes.id_cliente = CN.id_cliente
        WHERE CN.borrado = 0 " . $busqueda . " ORDER BY CN.fecha_creacion desc ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    function crear_notas_citas($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Datos generales como cliente.
        if (isset($parametros['id_cliente'])) {
            $registro['id_cliente'] = $parametros['id_cliente'];
        }
        if (isset($parametros['estado'])) {
            $registro['estado'] = $parametros['estado'];

            if ($registro['estado'] == "Finalizada") {
                $registro['fecha_finalizacion'] = date("Y-m-d H:i:s");
                $registro['id_usuario_finalizacion'] = $this->session->userdata('id_usuario');
            }
        }
        if (isset($parametros['nota'])) {
            $registro['nota'] = $parametros['nota'];
        }
        //
        $registro['fecha_creacion'] = date("Y-m-d H:i:s");
        $registro['id_usuario_creacion'] = $this->session->userdata('id_usuario');
        $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
        $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
        $registro['borrado'] = 0;

        $AqConexion_model->insert('clientes_notas_citas', $registro);

        return 1;
    }

    function actualizar_notas_citas($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Datos generales como cliente.
        if (isset($parametros['estado'])) {
            $registro['estado'] = $parametros['estado'];

            if ($registro['estado'] == "Finalizada") {
                $registro['fecha_finalizacion'] = date("Y-m-d H:i:s");
                $registro['id_usuario_finalizacion'] = $this->session->userdata('id_usuario');
            } else {
                $registro['fecha_finalizacion'] = null;
                $registro['id_usuario_finalizacion'] = null;
            }
        }
        if (isset($parametros['nota'])) {
            $registro['nota'] = $parametros['nota'];
        }
        //
        $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
        $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');

        $where['id_nota_cita'] = $parametros['id_nota_cita'];

        $AqConexion_model->update('clientes_notas_citas', $registro, $where);

        return 1;
    }

    //12/10/20
    public function actualiza_facturacion($parametros)
    {
        $AqConexion_model = new AqConexion_model();
        //$registro['id_cliente']=$id_cliente;
        $registro['empresa'] = $parametros['empresa'];
        $registro['cif_nif'] = $parametros['cif_nif'];
        $registro['direccion_facturacion'] = $parametros['direccion_facturacion'];
        $registro['codigo_postal_facturacion'] = $parametros['codigo_postal_facturacion'];
        $registro['localidad_facturacion'] = $parametros['localidad_facturacion'];
        $registro['provincia_facturacion'] = $parametros['provincia_facturacion'];
        $where['id_cliente'] = $parametros['id_cliente'];
        $AqConexion_model->update('clientes', $registro, $where);

        return 1;
    }

    function finalizar_notas_citas($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $registro['estado'] = "Finalizada";
        $registro['fecha_finalizacion'] = date("Y-m-d H:i:s");
        $registro['id_usuario_finalizacion'] = $this->session->userdata('id_usuario');

        $where['id_nota_cita'] = $parametros['id_nota_cita'];

        $AqConexion_model->update('clientes_notas_citas', $registro, $where);

        return 1;
    }

    function borrar_notas_citas($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Datos generales
        $registro['borrado'] = 1;
        $registro['fecha_borrado'] = date("Y-m-d H:i:s");
        $registro['id_usuario_borrado'] = $this->session->userdata('id_usuario');

        $where['id_nota_cita'] = $parametros['id_nota_cita'];

        $AqConexion_model->update('clientes_notas_citas', $registro, $where);

        return 1;
    }

    function borrar_consentimiento($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Datos generales
        $registro['borrado'] = 1;
        $registro['fecha_borrado'] = date("Y-m-d H:i:s");
        $registro['id_usuario_borrado'] = $this->session->userdata('id_usuario');

        $where['id'] = $parametros['id'];

        $AqConexion_model->update('consentimientos', $registro, $where);

        return 1;
    }


    //
    // NOTAS COBRAR
    //
    function notas_cobrar($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $busqueda = "";

        if (isset($parametros['id_nota_cobrar'])) {
            $busqueda .= " AND CN.id_nota_cobrar = @id_nota_cobrar ";
        }

        if (isset($parametros['carnet'])) {
            if ($parametros['carnet'] != "") {
                $param['codigo'] = $parametros['carnet'];
                $carnet = $this->Carnets_model->leer($param);

                if ($carnet != 0) {
                    if ($carnet[0]['id_carnet'] > 0) {
                        $busqueda .= " AND CN.id_carnet = " . $carnet[0]['id_carnet'];
                    }
                }
                // ... Sino se encuentra el carnet entonces decimos que igual a -1,
                // para que no muestre nada.
                else {
                    $busqueda .= " AND CN.id_carnet = '-1'";
                }
            } else {
                $busqueda .= " AND CN.id_carnet = '-1'";
            }
        }

        if (isset($parametros['id_cliente'])) {
            if ($parametros['id_cliente'] > 0) {
                $busqueda .= " AND CN.id_cliente = @id_cliente ";
            }
        }

        if (isset($parametros['estado'])) {
            $busqueda .= " AND CN.estado = @estado ";
        }

        // ... Leemos los registros
        $sentencia_sql = "SELECT CN.id_nota_cobrar,CN.id_cliente,
            CN.estado,CN.nota,CN.fecha_creacion,CN.id_usuario_creacion,
            CN.fecha_modificacion,CN.id_usuario_modificacion,CN.fecha_finalizacion,
            CN.id_usuario_finalizacion,CN.borrado,
            CN.fecha_borrado,CN.id_usuario_borrado,
            CONCAT(U1.nombre, ' ', U1.apellidos) As usuario_creacion,
            CONCAT(U2.nombre, ' ', U2.apellidos) As usuario_modificacion,
            CONCAT(U3.nombre, ' ', U3.apellidos) As usuario_finalizacion,
            CONCAT(clientes.nombre, ' ', clientes.apellidos) As cliente,
            DATE_FORMAT(CN.fecha_creacion,'%Y-%m-%d %H:%i') as fecha_creacion_aaaammdd,
            DATE_FORMAT(CN.fecha_creacion,'%d-%m-%Y %H:%i') as fecha_creacion_ddmmaaaa,
            DATE_FORMAT(CN.fecha_modificacion,'%Y-%m-%d %H:%i') as fecha_modificacion_aaaammdd,
            DATE_FORMAT(CN.fecha_modificacion,'%d-%m-%Y %H:%i') as fecha_modificacion_ddmmaaaa,
            DATE_FORMAT(CN.fecha_finalizacion,'%Y-%m-%d %H:%i') as fecha_finalizacion_aaaammdd,
            DATE_FORMAT(CN.fecha_finalizacion,'%d-%m-%Y %H:%i') as fecha_finalizacion_ddmmaaaa,
            CN.firma_img,CN.id_carnet,carnets_templos.codigo as carnet
            FROM clientes_notas_cobrar AS CN
            LEFT JOIN usuarios AS U1 ON U1.id_usuario = CN.id_usuario_creacion
            LEFT JOIN usuarios AS U2 ON U2.id_usuario = CN.id_usuario_modificacion
            LEFT JOIN usuarios AS U3 ON U3.id_usuario = CN.id_usuario_finalizacion
            LEFT JOIN clientes ON clientes.id_cliente = CN.id_cliente
            LEFT JOIN carnets_templos ON carnets_templos.id_carnet = CN.id_carnet
            WHERE CN.borrado = 0 " . $busqueda . " ORDER BY CN.fecha_creacion desc ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    function crear_notas_cobrar($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Datos generales como cliente.
        if (isset($parametros['id_cliente'])) {
            $registro['id_cliente'] = $parametros['id_cliente'];
        }
        if (isset($parametros['estado'])) {
            $registro['estado'] = $parametros['estado'];

            if ($registro['estado'] == "Finalizada") {
                $registro['fecha_finalizacion'] = date("Y-m-d H:i:s");
                $registro['id_usuario_finalizacion'] = $this->session->userdata('id_usuario');
            }
        }
        if (isset($parametros['nota'])) {
            $registro['nota'] = $parametros['nota'];
        }
        if (isset($parametros['firma_img'])) {
            $registro['firma_img'] = $parametros['firma_img'];
        }
        if (isset($parametros['id_carnet'])) {
            $registro['id_carnet'] = $parametros['id_carnet'];
        } else {
            $registro['id_carnet'] = 0;
        }
        //
        $registro['fecha_creacion'] = date("Y-m-d H:i:s");
        $registro['id_usuario_creacion'] = $this->session->userdata('id_usuario');
        $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
        $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
        $registro['borrado'] = 0;

        $AqConexion_model->insert('clientes_notas_cobrar', $registro);

        return 1;
    }

    function actualizar_notas_cobrar($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Datos generales como cliente.
        if (isset($parametros['estado'])) {
            $registro['estado'] = $parametros['estado'];

            if ($registro['estado'] == "Finalizada") {
                $registro['fecha_finalizacion'] = date("Y-m-d H:i:s");
                $registro['id_usuario_finalizacion'] = $this->session->userdata('id_usuario');
            } else {
                $registro['fecha_finalizacion'] = null;
                $registro['id_usuario_finalizacion'] = null;
            }
        }
        if (isset($parametros['nota'])) {
            $registro['nota'] = $parametros['nota'];
        }
        if (isset($parametros['id_carnet'])) {
            $registro['id_carnet'] = $parametros['id_carnet'];
        } else {
            $registro['id_carnet'] = 0;
        }
        //
        $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
        $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');

        $where['id_nota_cobrar'] = $parametros['id_nota_cobrar'];

        $AqConexion_model->update('clientes_notas_cobrar', $registro, $where);

        return 1;
    }

    function finalizar_notas_cobrar($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $registro['estado'] = "Finalizada";
        $registro['fecha_finalizacion'] = date("Y-m-d H:i:s");
        $registro['id_usuario_finalizacion'] = $this->session->userdata('id_usuario');

        $where['id_nota_cobrar'] = $parametros['id_nota_cobrar'];

        $AqConexion_model->update('clientes_notas_cobrar', $registro, $where);

        return 1;
    }

    function borrar_notas_cobrar($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Datos generales
        $registro['borrado'] = 1;
        $registro['fecha_borrado'] = date("Y-m-d H:i:s");
        $registro['id_usuario_borrado'] = $this->session->userdata('id_usuario');

        $where['id_nota_cobrar'] = $parametros['id_nota_cobrar'];

        $AqConexion_model->update('clientes_notas_cobrar', $registro, $where);

        return 1;
    }

    /*
  * Enviar mail para que el cliente opine.
  *
  * @id_cliente integer
  *
  * Return 1 o 0.
  *
  */
    function EmailOpinionCliente($id_cliente)
    {
        // ... Leemos los datos del cliente.
        unset($param);
        $param['id_cliente'] = $id_cliente;
        $cliente = $this->leer_clientes($param);

        if ($cliente[0]['email'] != "" && $cliente[0]['activo'] == 1) {
            // ... Preparamos el email
            $to = $cliente[0]['email'];
            $from = "info@templodelmasaje.com";
            $asunto = "Dejanos tu opinión - Templo del Masaje";

            $data['cliente'] = $cliente;
            $mensaje = $this->load->view('emails/email_opinion_cliente_view', $data, true);

            if ($cliente[0]['activo'] == 1) {
                $this->Utiles_model->enviar_email($to, $from, $asunto, $mensaje);
            }
        }

        return 1;
    }

    function leer_firma_lopd($id_cliente)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = "
            SELECT
            C.id,C.id_cliente,C.firma,C.dni,
            C.gestion_historial,C.recibir_informacion,
            CONCAT(clientes.nombre, ' ', clientes.apellidos) as cliente
            FROM
            clientes_firmas_lopd AS C
            LEFT JOIN clientes ON clientes.id_cliente = C.id_cliente
            WHERE
            C.id_cliente = @id_cliente
            ORDER BY C.fecha_creacion DESC limit 1
            ";

        $parametros['id_cliente'] = $id_cliente;
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    //Para el Pre
    function leer_firma_consentimiento($id_cliente)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = "
            SELECT
            C.id,C.id_cliente,C.firma,C.dni,
            C.gestion_historial,C.recibir_informacion,
            CONCAT(clientes.nombre, ' ', clientes.apellidos) as cliente,
            TIMESTAMPDIFF(YEAR, clientes.fecha_nacimiento, CURDATE()) AS edad,
            clientes.nombre_tutor,clientes.dni_tutor,
            clientes.dni,clientes.direccion,clientes.telefono,clientes.codigo_postal
            FROM
            clientes
            LEFT JOIN clientes_firmas_lopd AS C ON clientes.id_cliente = C.id_cliente
            WHERE
            clientes.borrado=0 AND clientes.id_cliente = @id_cliente";

        $parametros['id_cliente'] = $id_cliente;
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    //Para el POST (Firmada))
    function leer_consentimiento_firmado($aleatorio)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = "
            SELECT
            C.id,C.id_cliente,C.firma,C.borrado,C.descripcionTratamiento,clientes.dni,
            CONCAT(clientes.nombre, ' ', clientes.apellidos) as cliente,
            TIMESTAMPDIFF(YEAR, clientes.fecha_nacimiento, CURDATE()) AS edad,
            clientes.nombre_tutor,clientes.dni_tutor,
            clientes.dni,clientes.direccion,clientes.telefono,clientes.codigo_postal
            FROM
            consentimientos AS C
            LEFT JOIN clientes ON clientes.id_cliente = C.id_cliente
            WHERE
            C.borrado=0 AND C.aleatorio = " . $aleatorio;

        $datos = $AqConexion_model->select($sentencia_sql, null);

        return $datos;
    }

    function leer_lista_consentimientos($id_cliente)
    {

        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = "
            SELECT
            C.id,C.id_cliente,C.firma,C.aleatorio,C.tratamiento,C.descripcionTratamiento,C.fecha,C.borrado,
            DATE_FORMAT(C.fecha,'%d-%m-%Y') as fecha_ddmmaaaa, usuarios.nombre, usuarios.apellidos
            FROM
            consentimientos AS C
            LEFT JOIN usuarios on usuarios.id_usuario=C.id_doctor
            WHERE
            C.borrado=0 AND C.id_cliente = " . $id_cliente;

                $datos = $AqConexion_model->select($sentencia_sql, null);
                //print_r($this->db->last_query());
                //exit();
        return $datos;
    }

    //31/07/21 SubFicha
    function crear_subficha($parametros)
    {
        $AqConexion_model = new AqConexion_model();


        $registro['id_cliente'] = $parametros['id_cliente'];
        $registro['tratamiento'] = $parametros['selectFichas'];
        $registro['contenido'] = $parametros['nota_ficha'];
        $registro['id_centro'] = 1;
        $registro['fecha'] = date("Y-m-d H:i:s");
        $registro['id_empleado'] = $this->session->userdata('id_usuario');
        $AqConexion_model->insert('ficha_subficha', $registro);
        return 1;
    }

    function leer_subficha($id_cliente)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = "
            SELECT
            C.id,C.id_cliente,C.tratamiento,C.fecha,C.borrado,C.contenido,
            DATE_FORMAT(C.fecha,'%d-%m-%Y') as fecha_ddmmaaaa, concat(U1.nombre,' ',U1.apellidos) AS empleado
            FROM
            ficha_subficha AS C
            LEFT JOIN usuarios AS U1 ON (U1.id_usuario=C.id_empleado)
            WHERE
            C.borrado=0 AND C.id_cliente = " . $id_cliente;

        $datos = $AqConexion_model->select($sentencia_sql, null);

        return $datos;
    }

    function leer_subficha_id($id)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = "
            SELECT
            C.id,C.id_cliente,C.tratamiento,C.fecha,C.borrado,C.contenido,
            DATE_FORMAT(C.fecha,'%d-%m-%Y') as fecha_ddmmaaaa, concat(U1.nombre,' ',U1.apellidos) AS empleado,
            CONCAT(clientes.nombre, ' ', clientes.apellidos) as cliente
            FROM
            ficha_subficha AS C
            LEFT JOIN usuarios AS U1 ON (U1.id_usuario=C.id_empleado)
            LEFT JOIN clientes ON (clientes.id_cliente = C.id_cliente)
            WHERE
            C.borrado=0 AND C.id = " . $id;

        $datos = $AqConexion_model->select($sentencia_sql, null);

        return $datos;
    }

    function modificar_subficha($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $registro['tratamiento'] = $parametros['selectFichas'];
        $registro['contenido'] = $parametros['nota_ficha'];
        $registro['fecha'] = date("Y-m-d H:i:s");
        $registro['id_empleado'] = $this->session->userdata('id_usuario');
        $where['id'] = $parametros['id'];
        $AqConexion_model->update('ficha_subficha', $registro, $where);
        return 1;
    }

    function crear_firma_lopd($id_cliente, $dni, $firma, $gestion_historial, $recibir_informacion)
    {
        $existe = $this->existe_firma_lopd($id_cliente);

        if (!$existe) {
            $AqConexion_model = new AqConexion_model();

            $registro['id_cliente'] = $id_cliente;
            $registro['dni'] = $dni;
            $registro['firma'] = $firma;
            $registro['gestion_historial'] = 1;
            $registro['recibir_informacion'] = $recibir_informacion;
            //
            $registro['fecha_creacion'] = date("Y-m-d H:i:s");
            $registro['id_usuario_creacion'] = $this->session->userdata('id_usuario');

            $AqConexion_model->insert('clientes_firmas_lopd', $registro);

            return 1;
        } else {
            return 0;
        }
    }

    function existe_firma_lopd($id_cliente)
    {
        $AqConexion_model = new AqConexion_model();

        $sentencia_sql = "SELECT id FROM clientes_firmas_lopd WHERE id_cliente = @id_cliente";

        $parametros['id_cliente'] = $id_cliente;
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        if ($datos != 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function actualizar_lopd_cliente($id_cliente, $dni, $recibir_informacion)
    {
        $AqConexion_model = new AqConexion_model();

        $no_quiere_publicidad = 1;
        if ($recibir_informacion == 1) {
            $no_quiere_publicidad = 0;
        }

        $registro['dni'] = $dni;
        $registro['no_quiere_publicidad'] = $no_quiere_publicidad;
        //
        $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
        $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');

        $where['id_cliente'] = $id_cliente;
        $AqConexion_model->update('clientes', $registro, $where);

        return 1;
    }

    function pago_a_cuenta($id_dietario, $id_cliente, $importe, $tipo_pago, $motivo)
    {
        $AqConexion_model = new AqConexion_model();

        $registro['id_dietario'] = $id_dietario;
        $registro['id_cliente'] = $id_cliente;
        $registro['importe'] = $importe;
        $registro['tipo_pago'] = $tipo_pago;
        $registro['motivo'] = $motivo;
        //
        $registro['fecha_creacion'] = date("Y-m-d H:i:s");
        $registro['id_usuario_creacion'] = $this->session->userdata('id_usuario');

        $AqConexion_model->insert('clientes_saldos', $registro);

        $sentenciaSQL = "select max(id) as id from clientes_saldos";
        $resultado = $AqConexion_model->select($sentenciaSQL, null);

        return $resultado[0]['id'];
    }

    function leer_pago_a_cuenta($id_cliente)
    {
        $AqConexion_model = new AqConexion_model();

        $sentencia_sql = "SELECT
            CS.id,CS.id_dietario,CS.id_cliente,CS.importe,CS.tipo_pago,CS.motivo,
            CS.fecha_creacion,CS.id_usuario_creacion,dietario.estado,dietario.id_presupuesto,
            DATE_FORMAT(CS.fecha_creacion,'%d-%m-%Y - %H:%i') as fecha_creacion_ddmmaaaa,
            DATE_FORMAT(CS.fecha_creacion,'%Y-%m-%d - %H:%i') as fecha_creacion_aaaammdd,
            CONCAT(usuarios.nombre, ' ', usuarios.apellidos) As empleado,
            DATE_FORMAT(dietario.fecha_hora_concepto,'%d-%m-%Y - %H:%i') as fecha_hora_concepto
            FROM clientes_saldos as CS
            LEFT JOIN dietario ON dietario.id_dietario = CS.id_dietario
            LEFT JOIN usuarios ON usuarios.id_usuario = dietario.id_empleado
            WHERE
            CS.id_cliente = @id_cliente
            ORDER BY CS.fecha_creacion DESC";

        $parametros['id_cliente'] = $id_cliente;
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    function saldo($id_cliente)
    {
        $AqConexion_model = new AqConexion_model();

        $sentencia_sql = "SELECT SUM(CS.importe) as saldo
            FROM clientes_saldos as CS LEFT JOIN dietario ON dietario.id_dietario = CS.id_dietario WHERE CS.id_cliente = @id_cliente AND (dietario.estado = 'Pagado' OR dietario.estado = 'Devuelto')";

        $parametros['id_cliente'] = $id_cliente;
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos[0]['saldo'] !== null ? $datos[0]['saldo'] : 0;
    }

    function exportar_clientes($parametros)
    {
        //$AqConexion_model = new AqConexion_model();
        //var_dump($parametros['campos']);exit;
        $busqueda = "";
        $busqueda_adicional = "";
        $join_clientes = "";
        $sentencia_sql = "
        SELECT
       clientes.id_cliente,";

        $clientes_campos = [
            'nombre', 'apellidos', 'email', 'telefono', 'direccion', 'codigo_postal', 'fecha_activacion', 'fecha_modificacion'
        ];


        foreach ($parametros['campos'] as $key => $value) {
            if (in_array($key, $clientes_campos)) {
                $as = ucfirst($key);
                $as = str_replace('_', ' ', $as);
                $sentencia_sql .= " clientes." . $key . " as '" . $as . "',";
            } elseif ($key == 'fecha_creacion_ddmmaaaa') {
                $as = "Fecha de creación";
                $sentencia_sql .= " DATE_FORMAT(clientes.fecha_creacion,'%d-%m-%Y %H:%i') as '" . $as . "',";
            } elseif ($key == 'fecha_nacimiento_ddmmaaaa') {
                $as = "Fecha de nacimiento";
                $sentencia_sql .= " DATE_FORMAT(clientes.fecha_nacimiento,'%d-%m-%Y') as '" . $as . "',";
            } elseif ($key == 'no_quiere_publicidad') {
                $sentencia_sql .= " CASE WHEN clientes.no_quiere_publicidad = 1 THEN 'NO QUIERE' ELSE 'SI QUIERE' END as Publicidad,";
            } elseif ($key == 'password') {
                $sentencia_sql .= " IF(clientes.password IS NOT NULL, 'SI', 'NO') AS Password,";
            } elseif ($key == 'activo') {
                $sentencia_sql .= " CASE WHEN clientes.activo = 1 THEN 'SI' ELSE 'NO' END as Activo,";
            } elseif ($key == 'ultimo_centro_visitado') {
                $as = "Último centro visitado";
                $sentencia_sql .= " CUV.nombre_centro as '" . $as . "',";
                $join_clientes .= " LEFT JOIN (clientes_ultimos_centros left join centros as CUV on clientes_ultimos_centros.id_centro = CUV.id_centro)
            ON clientes_ultimos_centros.id_cliente = clientes.id_cliente";
            } elseif ($key == 'fecha_ultima_reserva') {
                $as = "Fecha última reserva";
                $sentencia_sql .= " CUC.fecha_ultima_reserva as '" . $as . "',";
                $join_clientes .= " LEFT JOIN (SELECT id_cliente,MAX(fecha_creacion) as fecha_ultima_reserva
            FROM clientes_ultimos_centros GROUP BY id_cliente) as CUC ON CUC.id_cliente = clientes.id_cliente";
            } elseif ($key == 'fecha_ultimo_login') {
                $as = "Último acceso";
                $sentencia_sql .= " CA.fecha_ultimo_login as '" . $as . "',";
                $join_clientes .= " LEFT JOIN (SELECT id_cliente,MAX(fecha_acceso) as fecha_ultimo_login
            FROM clientes_accesos group by id_cliente) as CA ON CA.id_cliente = clientes.id_cliente";
            } elseif ($key == 'fecha_ultima_cita_abandonada') {
                $as = "Fecha última cita abandonada";
                $sentencia_sql .= " CP.fecha_ultima_cita_abandonada as '" . $as . "',";
                $join_clientes .= " LEFT JOIN (SELECT id_cliente,MAX(fecha_creacion) as fecha_ultima_cita_abandonada
                        FROM citas_pedidos
                        WHERE citas_pedidos.borrado = 0 and estado = 'Abandonado'
                        group by id_cliente)
            as CP ON CP.id_cliente = clientes.id_cliente";
            } elseif ($key == 'numero_citas_abandonadas') {
                $as = "Número de citas abandonadas";
                $sentencia_sql .= " CPA.numero_citas_abandonadas as '" . $as . "',";
                $join_clientes .= " LEFT JOIN (SELECT id_cliente,COUNT(id_pedido) as numero_citas_abandonadas
                     FROM citas_pedidos WHERE borrado = 0 and estado = 'Abandonado' GROUP BY id_cliente)
             as CPA ON CPA.id_cliente = clientes.id_cliente";
            }
        }



        // ... Para exportacion de CVS datos de especificos
        $exportacion_csv_especifico = "";

        /*$exportacion_csv_especifico="
            CUV.nombre_centro as ultimo_centro_visitado,
            CUC.fecha_ultima_reserva,
            CA.fecha_ultimo_login,
            CP.fecha_ultima_cita_abandonada,
            CPA.numero_citas_abandonadas ";*/

                /*$join_clientes.="
                LEFT JOIN (clientes_ultimos_centros left join centros as CUV on clientes_ultimos_centros.id_centro = CUV.id_centro)
                ON clientes_ultimos_centros.id_cliente = clientes.id_cliente

                LEFT JOIN (SELECT id_cliente,MAX(fecha_creacion) as fecha_ultima_reserva
                FROM clientes_ultimos_centros GROUP BY id_cliente) as CUC ON CUC.id_cliente = clientes.id_cliente

                LEFT JOIN (SELECT id_cliente,MAX(fecha_acceso) as fecha_ultimo_login
                FROM clientes_accesos group by id_cliente) as CA ON CA.id_cliente = clientes.id_cliente

                LEFT JOIN (SELECT id_cliente,MAX(fecha_creacion) as fecha_ultima_cita_abandonada
                            FROM citas_pedidos
                            WHERE citas_pedidos.borrado = 0 and estado = 'Abandonado'
                            group by id_cliente)
                as CP ON CP.id_cliente = clientes.id_cliente

                LEFT JOIN (SELECT id_cliente,COUNT(id_pedido) as numero_citas_abandonadas
                        FROM citas_pedidos WHERE borrado = 0 and estado = 'Abandonado' GROUP BY id_cliente)
                as CPA ON CPA.id_cliente = clientes.id_cliente
            ";*/
        $sentencia_sql = trim($sentencia_sql, ',');
        $sentencia_sql .= " FROM
         clientes " . $join_clientes . "
            WHERE
            clientes.borrado = 0 " . $busqueda . "
            GROUP BY
            clientes.id_cliente
            ORDER BY
            nombre,apellidos
            ";

        // ... Leemos los registros
        /*$sentencia_sql="
                SELECT
            clientes.id_cliente,
            clientes.nombre,
            clientes.apellidos,
            DATE_FORMAT(clientes.fecha_creacion,'%d-%m-%Y %H:%i') as fecha_creacion_ddmmaaaa,

            DATE_FORMAT(clientes.fecha_nacimiento,'%Y-%m-%d') as fecha_nacimiento_aaaammdd,
            DATE_FORMAT(clientes.fecha_nacimiento,'%d-%m-%Y') as fecha_nacimiento_ddmmaaaa,
            clientes.email,
            clientes.telefono,
            clientes.direccion,
            clientes.codigo_postal,
            CASE WHEN clientes.no_quiere_publicidad = 1 THEN 'NO QUIERE' ELSE 'SI QUIERE' END as no_quiere_publicidad,
            IF(clientes.password IS NOT NULL, 'SI', 'NO') AS password,
            CASE WHEN clientes.activo = 1 THEN 'SI' ELSE 'NO' END as activo,
            clientes.fecha_activacion,
            clientes.fecha_modificacion,
            $exportacion_csv_especifico
            FROM
            clientes ".$join_clientes."
            WHERE
            clientes.borrado = 0 ".$busqueda."
            GROUP BY
            clientes.id_cliente
            ORDER BY
            nombre,apellidos
            ";*/
        $datos = $this->db->query($sentencia_sql);
        return $datos;
    }

    function documentos_cliente($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $busqueda = "";
        $busqueda_adicional = "";

        if (isset($parametros['id_cliente'])) {
            $busqueda .= " AND clientes_documentos.id_cliente = @id_cliente ";
        }

        if (isset($parametros['tipo'])) {
            $busqueda .= " AND clientes_documentos.tipo = @tipo ";
        }
        if (isset($parametros['id_presupuesto'])) {
            $busqueda .= " AND clientes_documentos.id_presupuesto = @id_presupuesto ";
        }

        if (isset($parametros['fecha_estudio'])) {
            $busqueda .= " AND clientes_documentos.fecha_estudio LIKE @fecha_estudio ";
        }
        // ... Leemos los registros
        $sentencia_sql = "
            SELECT
            clientes_documentos.* 
            FROM
            clientes_documentos
            WHERE
            clientes_documentos.borrado = 0 " . $busqueda . "
            GROUP BY
            clientes_documentos.id_documento
            ORDER BY
            fecha_estudio,documento
            ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);
        return $datos;
    }


    function nuevo_documento($parametros)
    {
        $AqConexion_model = new AqConexion_model();
        if(isset($parametros['id_presupuesto'])){
            $registro['id_presupuesto'] = $parametros['id_presupuesto'];
        }
        $registro['documento'] = $parametros['documento'];
        $registro['id_cliente'] = $parametros['id_cliente'];
        $registro['fecha_estudio'] = $parametros['fecha_estudio'];
        $registro['tipo'] = $parametros['tipo'];
        $registro['observaciones'] = (isset($parametros['observaciones'])) ? $parametros['observaciones'] : '';
        $registro['fecha_creacion'] = date("Y-m-d H:i:s");
        $registro['id_usuario_creacion'] = $this->session->userdata('id_usuario');
        $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
        $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
        $registro['borrado'] = 0;
        $AqConexion_model->insert('clientes_documentos', $registro);
        if ($this->db->affected_rows() > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    function actualizar_documento($parametros)
    {
        $AqConexion_model = new AqConexion_model();
        if(isset($parametros['id_presupuesto'])){
            $registro['id_presupuesto'] = $parametros['id_presupuesto'];
        }
        $registro['fecha_estudio'] = $parametros['fecha_estudio'];
        $registro['tipo'] = $parametros['tipo'];
        $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
        $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
        if (isset($parametros['borrado'])) {
            $registro['borrado'] = $parametros['borrado'];
            if ($registro['borrado'] == 1) {
                $registro['fecha_borrado'] = date("Y-m-d H:i:s");
                $registro['id_usuario_borrado'] = $this->session->userdata('id_usuario');
            }
        } else {
            $registro['borrado'] = 0;
        }
        $where['id_documento'] = $parametros['id_documento'];
        $AqConexion_model->update('clientes_documentos', $registro, $where);
        if ($this->db->affected_rows() > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    function leer_evolutivo_cliente($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $busqueda = "";
        $busqueda_adicional = "";

        if (isset($parametros['id_cliente'])) {
            $busqueda .= " AND clientes_evolutivo.id_cliente = @id_cliente ";
        }

        if (isset($parametros['tipo'])) {
            $busqueda .= " AND clientes_evolutivo.tipo = @tipo ";
        }

        if (isset($parametros['fecha_nota'])) {
            $busqueda .= " AND clientes_evolutivo.fecha_nota LIKE @fecha_nota ";
        }

        if (isset($parametros['id_usuario_creador'])) {
            $busqueda .= " AND clientes_evolutivo.id_usuario_creador = @id_usuario_creador ";
        }
        // ... Leemos los registros
        // CHAINS 20242302 Añadimos el doctor
        $sentencia_sql = " SELECT 
            clientes_evolutivo.*,
            CONCAT(usuarios.nombre, ' ', usuarios.apellidos) AS empleado,
            DATE_FORMAT(clientes_evolutivo.fecha_nota,'%d-%m-%Y %H:%i') as fecha_nota_formateada,
            CONCAT(us2.nombre, ' ' , us2.apellidos) AS doctor
            FROM clientes_evolutivo
            LEFT JOIN usuarios ON usuarios.id_usuario = clientes_evolutivo.id_usuario_creador
            LEFT JOIN usuarios us2 ON us2.id_usuario = clientes_evolutivo.id_usuario_doctor
            WHERE clientes_evolutivo.borrado = 0 " . $busqueda . " GROUP BY
            clientes_evolutivo.id
            ORDER BY
            fecha_nota desc,id_cliente
            ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);
        return $datos;
    }


    function nuevo_evolutivo($parametros)
    {
        $AqConexion_model = new AqConexion_model();
        $registro['id_cliente'] = $parametros['id_cliente'];
        $registro['fecha_nota'] = date("Y-m-d H:i:s");
        $registro['nota'] = $parametros['nota'];
        $registro['tipo'] = (isset($parametros['tipo'])) ? $parametros['tipo'] : '';
        $registro['id_usuario_creador'] = $this->session->userdata('id_usuario');
        // CHAINS 2024-02-23
        $registro['id_usuario_doctor'] = isset($parametros['id_usuario_doctor']) ? $parametros['id_usuario_doctor'] : $this->session->userdata('id_usuario');
        // CHAINS 2024-02-23
        $registro['fecha_creacion'] = date("Y-m-d H:i:s");
        $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
        $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
        $registro['borrado'] = 0;
        if(isset($parametros['fecha_nota'])){
            $registro['fecha_nota']=$parametros['fecha_nota'];
        }
        $AqConexion_model->insert('clientes_evolutivo', $registro);
        if ($this->db->affected_rows() > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    function editar_evolutivo($parametros)
    {
        $AqConexion_model = new AqConexion_model();
       
       
        $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
        $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
        if(isset($parametros['id_usuario_doctor'])) $registro['id_usuario_doctor']=$parametros['id_usuario_doctor'];
        if (isset($parametros['borrado'])) {
            $registro['borrado'] = $parametros['borrado'];
            if ($registro['borrado'] == 1) {
                $registro['fecha_borrado'] = date("Y-m-d H:i:s");
                $registro['id_usuario_borrado'] = $this->session->userdata('id_usuario');
            }
        } else {
            $registro['nota'] = $parametros['nota'];
            $registro['tipo'] = (isset($parametros['tipo'])) ? $parametros['tipo'] : '';
            $registro['borrado'] = 0;
        }
        if(isset($parametros['fecha_nota'])){
            $registro['fecha_nota']=$parametros['fecha_nota'];
        }
        $where['id'] = $parametros['id'];
        $AqConexion_model->update('clientes_evolutivo', $registro, $where);
        if ($this->db->affected_rows() > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    /** NOTAS INTERNAS */
    function leer_notas_internas_cliente($parametros) {
        $AqConexion_model = new AqConexion_model();
        $busqueda = "";
        $busqueda_adicional = "";
        if (isset($parametros['id_cliente'])) {
            $busqueda .= " AND clientes_notas_internas.id_cliente = @id_cliente ";
        }

        if (isset($parametros['tipo'])) {
            $busqueda .= " AND clientes_notas_internas.tipo = @tipo ";
        }

        if (isset($parametros['fecha_nota'])) {
            $busqueda .= " AND clientes_notas_internas.fecha_nota LIKE @fecha_nota ";
        }

        if (isset($parametros['id_usuario_creador'])) {
            $busqueda .= " AND clientes_notas_internas.id_usuario_creador = @id_usuario_creador ";
        }
        // ... Leemos los registros
        // CHAINS 20242302 Añadimos el doctor
        $sentencia_sql = " SELECT 
            clientes_notas_internas.*,
            CONCAT(usuarios.nombre, ' ', usuarios.apellidos) AS empleado,
            DATE_FORMAT(clientes_notas_internas.fecha_nota,'%d-%m-%Y %H:%i') as fecha_nota_formateada
            FROM clientes_notas_internas
            LEFT JOIN usuarios ON usuarios.id_usuario = clientes_notas_internas.id_usuario_creador
            WHERE clientes_notas_internas.borrado = 0 " . $busqueda . " GROUP BY
            clientes_notas_internas.id_nota_interna
            ORDER BY
            fecha_nota desc,id_cliente
            ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);
        return $datos;
    }

    function nueva_nota_interna($parametros)
    {
        $AqConexion_model = new AqConexion_model();
        $registro['id_cliente'] = $parametros['id_cliente'];
        $registro['fecha_nota'] = isset($parametros['fecha_nota_interna']) ?$parametros['fecha_nota_interna'] : date("Y-m-d H:i:s");
        $registro['nota'] = $parametros['nota_interna_content'];
        $registro['tipo'] = (isset($parametros['tipo'])) ? $parametros['tipo'] : '';
        $registro['id_usuario_creador'] = $this->session->userdata('id_usuario');
        $registro['fecha_creacion'] = date("Y-m-d H:i:s");
        $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
        $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
        $registro['borrado'] = 0;
        $AqConexion_model->insert('clientes_notas_internas', $registro);
        if ($this->db->affected_rows() > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    function editar_nota_interna($parametros)
    {
        $AqConexion_model = new AqConexion_model();
        $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
        $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
        if (isset($parametros['borrado'])) {
            $registro['borrado'] = $parametros['borrado'];
            if ($registro['borrado'] == 1) {
                $registro['fecha_borrado'] = date("Y-m-d H:i:s");
                $registro['id_usuario_borrado'] = $this->session->userdata('id_usuario');
            }
        } else {
            $registro['nota'] = $parametros['nota_interna_content'];
            $registro['tipo'] = (isset($parametros['tipo'])) ? $parametros['tipo'] : '';
            $registro['borrado'] = 0;
        }
        if(isset($parametros['fecha_nota_interna'])){
            $registro['fecha_nota']=$parametros['fecha_nota_interna'];
        }
        $where['id_nota_interna'] = $parametros['id_nota_interna'];
        $AqConexion_model->update('clientes_notas_internas', $registro, $where);
        if ($this->db->affected_rows() > 0) {
            return 1;
        } else {
            return 0;
        }
    }



    //(Alfonso)functiones de facturacion a clientes *****************/
    function get_clientes($nombre){
        return $this->db->where_like("CONCAL(nombre,' ',apellidos)", "%".$nombre."%")->get('clientes');
    }
    //(Alfonso)traer todos los servicios de todos presupuestos de cliente x en el centro y
    function servicios_presupuesto($id_cliente){
        $presupuestos = $this->db->select("*,'' as servicios")
                                    ->where('id_cliente',$id_cliente)
                                    ->where('estado','Aceptado')
                                    ->get('presupuestos');
        if($presupuestos->num_rows()>0){
            $presupuestos = $presupuestos->result();
        }else{
            echo "<h1 style='text-align:center'> No se encontraron presupuestos </h1>";exit;
        }
        foreach($presupuestos as $p){
            $p->servicios = $this->db->join('servicios','id_servicio=id_item')
                                        ->join('servicios_familias','servicios_familias.id_familia_servicio=servicios.id_familia_servicio')
                                        ->where('id_presupuesto',$p->id_presupuesto)
                                        ->get('presupuestos_items')->result();
        }
        return $presupuestos;
    }
    //(Alfonso)traer todos los servicios pagados de cliente x en el centro y
    function servicios_pagados($id_cliente){
        $servicios = $this->db->join('servicios','dietario.id_servicio=servicios.id_servicio')
                                        ->join('servicios_familias','servicios_familias.id_familia_servicio=servicios.id_familia_servicio')
                                        ->where('id_cliente',$id_cliente)
                                        ->where('estado','Pagado')
                                        ->where_in('tipo_pago',['#saldo_cuenta','#Presupuesto'])
                                        ->get('dietario');
        if($servicios->num_rows()>0){
            $servicios = $servicios->result();
        }else{
            echo "<h1 style='text-align:center'> No se encontraron servicios pagados </h1>";exit;
        }

        return $servicios;
    }
    //(Alfonso)calculo de saldo total del cliente
    function saldo_clientes($id_cliente){
        $abonos = $this->db->select('sum(importe_euros) as total')->where('id_cliente',$id_cliente)->where('pago_a_cuenta','1')->get('dietario')->row()->total;
        $facturado = $this->db->select('sum(importe) as total')->where('id_cliente',$id_cliente)->get('facturas')->row()->total;
        return $abonos - $facturado;
    }

    // CHAINS 20240208 - saldo de varios clientes
    function saldo_variosClientes($ids){
        if(!is_array($ids)){
            $ids=[$ids];
        }
        $sqlAbonos="SELECT id_cliente,sum(importe_euros) as abonos FROM `dietario` where pago_a_cuenta=1 AND id_cliente IN (".implode(",",$ids).") GROUP BY id_cliente";
        $sqlFacturas="SELECT id_cliente,sum(importe) as facturado FROM facturas WHERE id_cliente IN (".implode(",",$ids).") GROUP BY id_cliente";

        $AqConexion_model = new AqConexion_model();
        $abonos=$AqConexion_model->select($sqlAbonos, []);
        $facturas=$AqConexion_model->select($sqlFacturas,[]);
        $totales=[];
        if($abonos > 0) {
            foreach ($abonos as $abono) {
                if(!isset($totales[$abono['id_cliente']])) $totales[$abono['id_cliente']]=0;
                $totales[$abono['id_cliente']]+=$abono['abonos'];
            }
        }
        if($facturas > 0) {
            foreach ($facturas as $factura) {
                if(!isset($totales[$factura['id_cliente']])) $totales[$factura['id_cliente']]=0;
                $totales[$factura['id_cliente']]-=$factura['facturado'];
            }
        }

        return $totales;

    }
    // FIN CHAINS - saldo de variosic clientes

    function productos_comprados($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $importes='';
        if(isset($parametros['importes'])){
            $importes=",dietario.importe_euros,dietario.descuento_euros,dietario.descuento_porcentaje ";
        }
        // ... Leemos los registros
        $sentencia_sql = "SELECT dietario.id_producto,count(dietario.id_producto) as veces,
            nombre_familia,nombre_producto".$importes."
            FROM `dietario`
            left join (productos left join productos_familias on productos_familias.id_familia_producto
            = productos_familias.id_familia_producto)
            on productos.id_producto = dietario.id_producto
            WHERE id_cliente = @id_cliente and dietario.estado = 'Pagado' and dietario.borrado = 0
            and dietario.id_producto > 0
            group by dietario.id_producto order by count(dietario.id_producto) desc";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }
    function crearSaldoFicticio($data){
        $AqConexion_model = new AqConexion_model();
        $AqConexion_model->insert('clientes_saldos', $data);
        $str = $this->db->insert_id();
        return $str;
    }
    function getTotalPagado($id_cliente){
        $pagado="";
        $parametro['id_cliente']=$id_cliente;
        $AqConexion_model = new AqConexion_model();
        $sql="SELECT sum(importe) as importe FROM clientes_saldos WHERE (tipo_pago='#efectivo' OR tipo_pago='#financiado' OR tipo_pago='#paypal' OR tipo_pago='#tarjeta' OR tipo_pago='#tpv2' OR tipo_pago='#transferencia') AND id_cliente=@id_cliente;";
        $datos=$AqConexion_model->select($sql, $parametro);
        if(!empty($datos)){
            foreach ($datos as $key => $value) {
                $pagado= $value['importe'];
            }
        }
        return $pagado;        
    }
}
