<?php

if (!defined('BASEPATH'))
    exit('No permitir el acceso directo al script');

class Pedidos extends MY_Controller {

    protected $models = array('pedidos', 'cesta', 'usuarios');
    protected $asides = array();

    function __construct() {
        parent::__construct();
        $this->output->enable_profiler(true);

        if (!$this->session->userdata('usuario_valido')) {
            //Nos guardamos la url para luego redireccionar aquí
            $origen = current_url();
            $this->session->set_userdata('origen', $origen);

            $this->session->set_flashdata('RequiredLog', 'Para realizar un pedido necesitas estar registrado.');
            redirect('usuarios/loginform', 'refresh');
        }
    }

    function index() {
        redirect('cesta/ver', 'refresh');
    }

    /* mostrar los datos del pedido junto con los del usuario en modo lectura */

    function ver() {

        $this->data['titulo'] = "Ver pedidos";
        //CARRITO
        $this->data['carro_session'] = $this->cesta->librosCarrito();
        $this->data['librosCarro'] = $this->session->userdata('librosCarro');
        $this->data['items'] = $this->session->userdata('items');
        $this->data['total_precio'] = $this->session->userdata('total_precio');

        //DATOS DE USUARIO
        $this->data['usuario'] = $this->usuarios->read($this->session->userdata('id'));
    }

    /* comprueba que existe el carro en la sesión y posteriormente llama al método create */

    function confirmar() {

        //CARRITO
        $this->data['carro_session'] = $this->cesta->librosCarrito();
        $this->data['librosCarro'] = $this->session->userdata('librosCarro');
        $this->data['items'] = $this->session->userdata('items');
        $this->data['total_precio'] = number_format($this->session->userdata('total_precio'), 2);

        if ($this->input->post('submitOrder')) {
            $this->data['titulo'] = "Pedido realizado correctamente.";
            if ($this->pedidos->create() != "") {
                $carro_session = $this->data['carro_session'];
                $librosCarro = $this->data['librosCarro'];
                $total_precio = $this->data['total_precio'];

                //Eliminamos las variables de sesión porque todo ha ido correctamente
                $this->session->unset_userdata('carro');
                $this->session->unset_userdata('items');
                $this->session->unset_userdata('total_precio');
                $this->session->unset_userdata('librosCarro');

                $this->data['usuario'] = $this->usuarios->read($this->session->userdata('id'));

                foreach ($this->data['usuario'] as $id => $value) {
                    $data[$id] = $value;
                }

//                $carro_session=  $this->data['carro_session'];
//                $librosCarro=  $this->data['librosCarro'];


                if (count($carro_session) > 0) {
                    foreach ($carro_session as $id => $cantidad) {
                        $libro = $librosCarro[$id];
                        $data['libro'][$id] = array('titulo' => $libro->titulo, 'precio' => number_format($libro->precio, 2),
                            'cantidad' => $cantidad, 'total_fila' => number_format($cantidad * $libro->precio, 2));
                    }
                }

                //$data['total_precio']=number_format($this->session->userdata('total_precio'),2);
                $data['total_precio'] = $total_precio;

                //load the library parser
                $this->load->library('parser');

                $htmlMessage = $this->parser->parse('layouts/email', $data, true);
                //Enviamos un correo electrónico al administrador y al usuario del pedido
                $this->send_email($data['email'], $htmlMessage);
            }
        } else if ($this->input->post('pagarConPaypal')) {
            $this->data['titulo'] = "Te vamos a redirigir a Paypal.";
            $ultimopedio = $this->pedidos->create();
            if ($ultimopedio != NULL) {
                $this->session->set_userdata('paypal_order', $ultimopedio);
            }

            redirect('pedidos/enviarPaypal', 'refresh');
            // show_error("PAYPAL");
        } else if ($this->input->post('receiver_email')) {

            if ($this->input->post('payment_status') === "Completed") {
                $this->data['titulo'] = "Pago realizado correctamente con PAYPAL.";
                $carro_session = $this->data['carro_session'];
                $librosCarro = $this->data['librosCarro'];
                $total_precio = $this->data['total_precio'];

                //Eliminamos las variables de sesión porque todo ha ido correctamente
                $this->session->unset_userdata('carro');
                $this->session->unset_userdata('items');
                $this->session->unset_userdata('total_precio');
                $this->session->unset_userdata('librosCarro');

                $this->data['usuario'] = $this->usuarios->read($this->session->userdata('id'));

                foreach ($this->data['usuario'] as $id => $value) {
                    $data[$id] = $value;
                }

                if (count($carro_session) > 0) {
                    foreach ($carro_session as $id => $cantidad) {
                        $libro = $librosCarro[$id];
                        $data['libro'][$id] = array('titulo' => $libro->titulo, 'precio' => number_format($libro->precio, 2),
                            'cantidad' => $cantidad, 'total_fila' => number_format($cantidad * $libro->precio, 2));
                    }
                }
                $data['total_precio'] = $total_precio;

                //load the library parser
                $this->load->library('parser');

                $htmlMessage = $this->parser->parse('layouts/email', $data, true);
                //Enviamos un correo electrónico al administrador y al usuario del pedido
                $this->send_email($data['email'], $htmlMessage);
            } else {
                //Pago no confirmado
            }
        } else {
            show_error("No puedes acceder a esta p&aacute;gina sino accedes mediante el formulario de pedidos. ");
        }
    }

    public function enviarPaypal() {
        $this->data['total_precio'] = number_format($this->session->userdata('total_precio'), 2);
        $this->data['cliente'] = $this->session->userdata('id');
        $this->data['title'] = 'Enviar a PayPal';
        $this->data['paypal_order'] = $this->session->userdata('paypal_order');
    }

    public function procesarPagoPaypal() {
        $fh = fopen("log.txt", "a");
        fwrite($fh, "Inicio pago PAYPAL[" . date("d-m-Y H:i:s") . "]\n");
        fclose($fh);

        // CODIGO DE PAYPAL PARA COMPROBAR LA AUTENTICIDAD ----------------------------------------
// read the post from PayPal system and add 'cmd'
        $req = 'cmd=_notify-validate';

        foreach ($_POST as $key => $value) {
            $value = urlencode(stripslashes($value));
            $req .= "&$key=$value";
        }

// post back to PayPal system to validate

        $header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
        $header .= "Host: www.sanbox.paypal.com\r\n";
        $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";


        $fh = fopen("log.txt", "a");
        fwrite($fh, "Inicio pago 3 PAYPAL[" . date("d-m-Y H:i:s") . "]\n");
        fclose($fh);


        $fp = fsockopen('ssl://sandbox.paypal.com', 443, $errno, $errstr, 30);

// -----------------------------------------------------------------------------------------
// recogemos los datos da paypal
        $item_name = $_POST['item_name'];
        $item_number = $_POST['item_number'];
        $payment_status = $_POST['payment_status'];
        $payment_amount = $_POST['mc_gross'];
        $payment_currency = $_POST['mc_currency'];
        $txn_id = $_POST['txn_id'];
        $receiver_email = $_POST['receiver_email'];
        $payer_email = $_POST['payer_email'];
        $custom = $_POST['custom'];


        /*
         * Comprobamos si el pago se ha efectuado y si no ha habido error
         * Para hacer un seguimiento de la acciones guardamos las incidencias en un fichero de texto log.txt
         */

        if (!$fp) {

// Ha ocurrido un error

            $fh = fopen("log.txt", "a");
            fwrite($fh, "[ERROR][PAYPAL][" . date("d-m-Y H:i:s") . "]\n");
            fclose($fh);
        } else {


            fputs($fp, $header . $req);
            while (!feof($fp)) {
                $res = trim(fgets($fp, 1024));
                if (strcmp($res, "VERIFIED") == 0) {

// Se ha verificado la compra
                    // si el pago es correcto, ponemos en nuestra base de datos el pedido como pagado.
                    if (strcmp(strtoupper($payment_status), "COMPLETED") == 0) {


                        // Ponemos el pedido de la base de datos como pagado
//                            
//                        $sql = "update tienda_pedido set pagado = 1 where id = " . $item_number;
//                        mysql_query($sql);


                        $this->pedidos->PagarPaypal($item_number);
                       
                        $fh = fopen("log.txt", "a");
                        fwrite($fh, "[OK][PAYPAL][" . date("d-m-Y H:i:s") . "] numVenta: " . $item_number . " - idCliente: " . $custom . " - total: " . $payment_amount . " \n");
                        fclose($fh);

                        // Podemos aprovechar aqui para enviar un mail al usuario
                    } else {
                        // si el pago no esta completado, lo añadimos al log y no hacemos nada

                        $fh = fopen("log.txt", "a");
                        fwrite($fh, "[OK][ERROR][" . date("d-m-Y H:i:s") . "] numVenta: " . $item_number . " - idCliente: " . $custom . " - payStatus: " . $payment_status . " \n");
                        fclose($fh);
                    }
                } else if (strcmp($res, "INVALID") == 0) {

// Si es invalido tambien lo grabamos como error
                    $fh = fopen("log.txt", "a");
                    fwrite($fh, "[OK][ERROR][" . date("d-m-Y H:i:s") . "] numVenta: " . $item_number . " - idCliente: " . $custom . " - payStatus: " . $payment_status . " \n");
                    fclose($fh);
                }
            }
            fclose($fp);
        }
    }

    public function send_email($to, $htmlMessage) {
        //load the library parser
        //$this->load->library('parser');

        $config['protocol'] = 'sendmail';
        $config['mailpath'] = '/usr/sbin/sendmail';
        $config['charset'] = 'UTF-8';
        $config['wordwrap'] = TRUE;
        $config['mailtype'] = 'html';

        $this->load->library('email');

        $this->email->initialize($config);

        $this->email->from('ventas@readme.com', 'Ventas- Read.me');
        $this->email->to($to); //el que hace el pedido
        // $this->email->cc('lapongua@gmail.com');
        $this->email->bcc('lapongua@gmail.com'); //administrador

        $this->email->subject('Nuevo pedido en READ.ME tu libreria online');
        $this->email->message($htmlMessage);

        $this->email->send();
    }

}
