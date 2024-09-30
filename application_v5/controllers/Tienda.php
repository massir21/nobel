<?php
class Tienda extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        $this->load->library('form_validation');
        $this->load->helper(array('form', 'url'));
        $this->load->model('Tienda_model');
    }

    function index()
    {
        // ... Datos a pasar al contenido
        $data['loquesea'] = "0";

        // ... Viewer con el contenido
        $data['pagetitle'] = 'Comprobar Código de Compra';
        $data['content_view'] = $this->load->view('tienda/tienda_comprobar_codigos_view', $data, true);

        // ... Modulos del usuario
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $this->load->view($this->config->item('template_dir') . '/master', $data);
    }

    function procesar_codigo($id_cliente = null)
    {
        $codigo = $this->input->post('codigo');

        // Leemos el post id asociado al codigo de la tienda.
        $post_id = $this->Tienda_model->post_id($codigo);

        if ($post_id > 0) {
            $data['post_id'] = $post_id;
            // Comprobamos si ya se ha usado el codigo para generar un carnet
            $exite = $this->Tienda_model->existe_codigo($codigo);

            // Si el codigo de tienda no existe vinculado a ningun carnet
            // entonces seguimos con el proceso.
            if (!$exite) {
                // Leemos los itemes de la tienda asociados al post_id
                $items_tienda = $this->Tienda_model->order_item_ids($post_id);

                // Leemos las correspondencia de cada item con los servicios
                // o carnets de templos del sistema.
                $data['items_extranet'] = $this->Tienda_model->correspondencias_tienda($items_tienda);
            } else {
                $data['usado'] = 1;
            }
        } else {
            $data['no_existe'] = 1;
        }

        // ... Datos a pasar al contenido
        $data['codigo'] = $codigo;
        $data['id_cliente'] = $id_cliente;

        if ($id_cliente > 0) {
            unset($param5);
            $param5['id_cliente'] = $id_cliente;
            $data['cliente_elegido'] = $this->Clientes_model->leer_clientes($param5);
        }

        // ... Viewer con el contenido
        $data['pagetitle'] = 'Comprobar Código de Compra';
        $data['content_view'] = $this->load->view('tienda/tienda_comprobar_codigos_view', $data, true);

        // ... Modulos del usuario
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $this->load->view($this->config->item('template_dir') . '/master', $data);
    }

    function generar_carnets()
    {
        $codigo = $this->input->post('codigo');
        $id_cliente = $this->input->post('id_cliente');

        // Leemos el post id asociado al codigo de la tienda.
        $post_id = $this->Tienda_model->post_id($codigo);

        if ($post_id > 0 && $id_cliente > 0) {
            // Comprobamos si ya se ha usado el codigo para generar un carnet
            $exite = $this->Tienda_model->existe_codigo($codigo);

            // Si el codigo de tienda no existe vinculado a ningun carnet
            // entonces seguimos con el proceso.
            if (!$exite) {
                // Leemos los itemes de la tienda asociados al post_id
                $items_tienda = $this->Tienda_model->order_item_ids($post_id);

                // Leemos las correspondencia de cada item con los servicios
                // o carnets de templos del sistema.
                $items_extranet = $this->Tienda_model->correspondencias_tienda($items_tienda);

                $i = 0;
                $x = 0;
                $carnets_templos = array();
                $carnets_especial = array();

                foreach ($items_extranet as $row) {
                    if ($row['id_tipo_carnet'] > 0) {
                        $carnets_templos[$i]['id_tipo'] = $row['id_tipo_carnet'];
                        $carnets_templos[$i]['cantidad'] = $row['cantidad'];
                        $i++;
                    } else {
                        $carnets_especial[$x]['id_servicio'] = $row['id_servicio'];
                        $carnets_especial[$x]['id_pack'] = $row['id_pack'];
                        $carnets_especial[$x]['cantidad'] = $row['cantidad'];
                        $x++;
                    }
                }

                // ... Creamos carnets de templos si procede.
                if (count($carnets_templos) > 0) {
                    $carnets = $this->Tienda_model->crear_carnet_templos($carnets_templos, $id_cliente, $codigo);

                    $carnets_templos_ids = array();
                    $x = 0;

                    foreach ($carnets as $row) {
                        unset($param);
                        $param['id_carnet'] = $row['id_carnet'];
                        $leido = $this->Carnets_model->leer($param);

                        $carnets_templos_ids[$x]['id_carnet'] = $leido[0]['id_carnet'];
                        $carnets_templos_ids[$x]['codigo'] = $leido[0]['codigo'];
                        $carnets_templos_ids[$x]['cliente'] = $leido[0]['cliente'];
                        $carnets_templos_ids[$x]['tipo'] = $leido[0]['tipo'];
                    }

                    $data['carnets_templos'] = $carnets_templos_ids;
                }

                // ... Creamos carnets especial si procede.
                if (count($carnets_especial) > 0) {
                    $id_carnet = $this->Tienda_model->crear_carnet_especial($carnets_especial, $id_cliente, $codigo);

                    unset($param);
                    $param['id_carnet'] = $id_carnet;
                    $data['carnet_especial'] = $this->Carnets_model->leer($param);
                }

                $data['ok'] = 1;
            } else {
                $data['usado'] = 1;
            }
        } else {
            $data['error'] = 1;
        }

        // ... Datos a pasar al contenido
        $data['codigo'] = $codigo;

        // ... Viewer con el contenido
        $data['pagetitle'] = 'Comprobar Código de Compra';
        $data['content_view'] = $this->load->view('tienda/tienda_finalizar_carnets_view', $data, true);

        // ... Modulos del usuario
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $this->load->view($this->config->item('template_dir') . '/master', $data);
    }

    //
    function nuevo_cliente()
    {
        unset($parametros);
        $parametros['nombre'] = $this->input->post('nombre');
        $parametros['apellidos'] = $this->input->post('apellidos');
        $parametros['telefono'] = $this->input->post('telefono');
        if ($this->input->post('no_quiere_publicidad') == 1) {
            $parametros['no_quiere_publicidad'] = $this->input->post('no_quiere_publicidad');
        }
        $id_cliente = $this->Clientes_model->nuevo_cliente($parametros);

        if ($id_cliente > 0) {
            $this->procesar_codigo($id_cliente);
        } else {
            echo "Se ha producido un error inesperado al crear al cliente";
            exit;
        }
    }

    //
    // ... GESTION DE PACKS
    //

    //
    function packs()
    {
        // ... Leemos todos los packs de tienda existentes.
        $data['packs'] = $this->Tienda_model->leer_packs(0);

        // ... Viewer con el contenido
        $data['pagetitle'] = 'Gestión de Packs de Tienda Online';
        $data['actionstitle'] = ['<a href="' . base_url() . 'tienda/nuevo_pack" class="btn btn-primary text-inverse-primary">Añadir pack</a>'];
        $data['content_view'] = $this->load->view('tienda/packs_view', $data, true);

        // ... Modulos del usuario
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $this->load->view($this->config->item('template_dir') . '/master', $data);
    }

    //
    function nuevo_pack()
    {
        // ... Indicar la accion a realizar.
        $data['accion'] = "nuevo";

        // ... Leer las familias de servicios existentes    
        $data['familias_servicios'] = $this->Servicios_model->leer_familias_servicios('');

        // ... Viewer con el contenido
        $data['pagetitle'] = 'Nuevo registro';
        $data['content_view'] = $this->load->view('tienda/packs_nuevoeditar_view', $data, true);

        // ... Modulos del usuario
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $this->load->view($this->config->item('template_dir') . '/master', $data);
    }

    //
    function guardar_pack()
    {
        // ... Guardamos los datos de nuevo pack de tienda online.
        $data['estado'] = $this->Tienda_model->guardar_pack($this->input->post('nombre_pack'), $this->input->post('id_tienda'), $this->input->post('link_encuesta'), $this->input->post('servicios'));

        // ... Leemos todos los packs de tienda existentes.
        $data['packs'] = $this->Tienda_model->leer_packs(0);

        // ... Viewer con el contenido
        $data['pagetitle'] = 'Gestión de Packs de Tienda Online';
        $data['actionstitle'] = ['<a href="' . base_url() . 'tienda/nuevo_pack" class="btn btn-primary text-inverse-primary">Añadir pack</a>'];
        $data['content_view'] = $this->load->view('tienda/packs_view', $data, true);

        // ... Modulos del usuario
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $this->load->view($this->config->item('template_dir') . '/master', $data);
    }

    //
    function editar_pack($id_pack = null)
    {
        // ... Indicar la accion a realizar.
        $data['accion'] = "editar";

        // ... Leemos el pack indicado
        $data['registros'] = $this->Tienda_model->leer_packs($id_pack);
        $data['servicios_asociados'] = $this->Tienda_model->leer_servicios_pack($id_pack);

        // ... Leer las familias de servicios existentes
        $data['familias_servicios'] = $this->Servicios_model->leer_familias_servicios('');

        // ... Viewer con el contenido
        $data['pagetitle'] = 'Editar registro';
        $data['content_view'] = $this->load->view('tienda/packs_nuevoeditar_view', $data, true);

        // ... Modulos del usuario
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $this->load->view($this->config->item('template_dir') . '/master', $data);
    }

    //
    function actualizar_pack($id_pack = null)
    {
        // ... Guardamos los datos de nuevo pack de tienda online.
        $data['estado'] = $this->Tienda_model->actualizar_pack($id_pack, $this->input->post('nombre_pack'), $this->input->post('id_tienda'), $this->input->post('link_encuesta'), $this->input->post('servicios'));

        // ... Leemos todos los packs de tienda existentes.
        $data['packs'] = $this->Tienda_model->leer_packs(0);

        // ... Viewer con el contenido
        $data['pagetitle'] = 'Gestión de Packs de Tienda Online';
        $data['actionstitle'] = ['<a href="' . base_url() . 'tienda/nuevo_pack" class="btn btn-primary text-inverse-primary">Añadirpack </a>'];
        $data['content_view'] = $this->load->view('tienda/packs_view', $data, true);

        // ... Modulos del usuario
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $this->load->view($this->config->item('template_dir') . '/master', $data);
    }

    //
    function borrar_pack($id_pack = null)
    {
        // ... Borramos el id_pack indicado.
        $data['borrado'] = $this->Tienda_model->borrar_pack($id_pack);

        // ... Leemos todos los packs de tienda existentes.
        $data['packs'] = $this->Tienda_model->leer_packs(0);

        // ... Viewer con el contenido
        $data['pagetitle'] = 'Gestión de Packs de Tienda Online';
        $data['actionstitle'] = ['<a href="' . base_url() . 'tienda/nuevo_pack" class="btn btn-primary text-inverse-primary">Añadir</a>'];
        $data['content_view'] = $this->load->view('tienda/packs_view', $data, true);

        // ... Modulos del usuario
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $this->load->view($this->config->item('template_dir') . '/master', $data);
    }


    //
    function servicios_pack($id_pack = null)
    {
        $resultado = "";

        if ($id_pack > 0) {
            $servicios = $this->Tienda_model->leer_servicios_pack($id_pack);

            if ($servicios != 0) {
                foreach ($servicios as $row) {
                    $id = $row['id_servicio'];
                    $text = $row['nombre_servicio'] . " (" . $row['duracion'] . " min)";

                    $resultado .= "<option value='$id' selected>$text</option>";
                }
            }
        }

        return $resultado;
    }

    //
    function servicios_familia()
    {
        $resultado = "";

        $id_familia_servicio = $this->input->post('id_familia_servicio_tienda');

        if ($id_familia_servicio == 0 or $id_familia_servicio == "") {
            $resultado .= "<option value=''>Sin servicios</option>";
        } else {
            $resultado = "";

            if ($id_familia_servicio > 0) {
                unset($parametros);
                $parametros['id_familia_servicio'] = $id_familia_servicio;
                $datos = $this->Servicios_model->leer_servicios($parametros);

                if ($datos != 0) {
                    for ($i = 0; $i < count($datos); $i++) {
                        $id = $datos[$i]['id_servicio'];
                        $text = $datos[$i]['nombre_servicio'] . " (" . $datos[$i]['duracion'] . " min)";

                        $resultado .= "<option value='$id'>$text</option>";
                    }
                } else {
                    $resultado .= "<option value=''>Sin servicios</option>";
                }
            }

            echo $resultado;
        }

        exit;
    }

    function pedido_barcode()
    {
        $post_id = $this->input->post('post_id');

        if ($post_id > 0) {
            // Leemos el barcode asociado
            $barcode = $this->Tienda_model->post_id_barcode($post_id);

            if ($barcode != 0) {
                $data['barcode'] = $barcode;
                $data['no_existe'] = 0;
            } else {
                $data['no_existe'] = 1;
            }
        }


        $data['post_id'] = $post_id;

        // ... Viewer con el contenido
        $data['pagetitle'] = 'Comprobar Nº Pedido - Barcode';
        $data['content_view'] = $this->load->view('tienda/tienda_pedido_barcode_view', $data, true);

        // ... Modulos del usuario
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $this->load->view($this->config->item('template_dir') . '/master', $data);
    }
}
