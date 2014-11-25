<?php

if (!defined('BASEPATH'))
    exit('No permitir el acceso directo al script');

class Libros extends MY_Controller {

    protected $models = array('libros', 'cesta', 'comentarios');
    protected $asides = array();

    function __construct() {
        parent::__construct();
        $this->output->enable_profiler(true);
    }

    public function index() {

        $this->data['titulo'] = "READ.ME tu librelia online";
        $this->data['cabecera'] = "Últimas novedades";
        $this->data['libros'] = $this->libros->getLibros();
        $this->asides = array('sidebar' => 'layouts/_sidebar');

        //Cargamos los datos del carrito para la cabecera
        $this->data['carro_session'] = $this->cesta->librosCarrito();
        $this->data['librosCarro'] = $this->session->userdata('librosCarro');
        $this->data['items'] = $this->session->userdata('items');
        $this->data['total_precio'] = $this->session->userdata('total_precio');


//        if($this->session->userdata('contador'))
//        {
//            $contador=$this->session->userdata('contador');
//            $contador++;
//            $this->session->set_userdata('contador',$contador);
//        }
//        else //sino existe la variable de session session_id la inicializamos a 1.
//        {
//            $this->session->set_userdata('contador',1);
//        }       
//        
//        $variables['contador_visitas']=$this->session->userdata('contador');
    }

    function rss() {
        $this->output->enable_profiler(FALSE);
        $this->layout = FALSE;
        $this->load->helper('text');
        $this->data['titulo'] = "Últimos libros - LIBRERIA READ.ME";
        $this->data['feed_url'] = base_url() . 'libros/ver/';
        $this->data['description'] = "descripción del sitio...";
        $this->data['posts'] = $this->libros->getLibros(10);
    }

    public function ver($id) {
        if ($this->uri->segment(3) === FALSE) {
            show_error('No has especificado ningún id de libro');
        }
        //variables per a facebook
        $mivariable = $this->libros->readVariable($id, "titulo");
        $this->data['titulo'] = $mivariable->titulo;
        $this->data['cabecera'] = "Ficha libro";
        $this->data['fotolibro'] = $this->libros->getFotos($id);
        $this->data['descriptionF'] = $this->libros->readVariable($id, "descripcion");

        $this->asides = array('breadcrumb' => 'layouts/_breadcrumb');


        $id = (int) $this->uri->segment(3);
        $milibro = $this->libros->read($id);
        if (!empty($milibro)) {
            $this->data['libro'] = $milibro;
        } else {
            show_error('El libro introducido no existe');
        }

        $this->data['libros'] = $this->libros->getLibros();

        //Cargamos los datos del carrito para la cabecera
        $this->data['carro_session'] = $this->cesta->librosCarrito();
        $this->data['librosCarro'] = $this->session->userdata('librosCarro');
        $this->data['items'] = $this->session->userdata('items');
        $this->data['total_precio'] = $this->session->userdata('total_precio');
    }

    public function buscar() {
        $this->data['titulo'] = "Listado de Libros - LIBRERIA READ.ME";
        $this->data['cabecera'] = "Listado Libros";
        $this->asides = array('sidebar' => 'layouts/_sidebar', 'breadcrumb' => 'layouts/_breadcrumb');
        //$this->asides = array('sidebar' => 'layouts/_sidebar');

        $cadena = '';
        //Comprobamos si venimos por el buscar o desde la la pagina de listar libros
        if (strtolower($this->input->server('REQUEST_METHOD')) === 'post') {

            if ($this->input->post('searchweb', TRUE)) {
                $cadena = trim($this->input->post('searchweb', TRUE));
            } else {
                $cadena = '';
            }
            $error = "La cadena <strong>" . $cadena . "</strong> no corresponde con ningún libro.";
        } else {
            $error = "No hay libros dados de alta";
        }


        $libros = $this->libros->buscar($cadena);

        if (!empty($libros)) {
            $this->data['libros'] = $libros;
        } else {
            $this->data['libros'] = NULL;
            $this->data['error'] = $error;

            //show_error('No hemos encontrado ningún libro con esa coincidencia');
        }

        //Cargamos los datos del carrito para la cabecera
        $this->data['carro_session'] = $this->cesta->librosCarrito();
        $this->data['librosCarro'] = $this->session->userdata('librosCarro');
        $this->data['items'] = $this->session->userdata('items');
        $this->data['total_precio'] = $this->session->userdata('total_precio');
    }

    /*
      [PASO 2]
      Completar el metodo buscar_titulo_ajax de tal forma que se rescate la cadena de texto a buscar, se utilice el modelo para hacer la consulta de base de datos con esa cadena (que libros la contienen). Una vez con la informacion de vuelta del modelo, crear un string json para devolverlo al cliente (llegara como parametro en el funcion de callback de AJAX)
     */

    public function buscar_titulo_ajax() {
        $titulo = $this->input->post('titulo'); //[COMPLETAR] recogemos por post el titulo $this->input->post...;
        $min = $this->input->post('min');
        $max = $this->input->post('max');
        $libros = $this->libros->buscar($titulo, $max, $min); //llamamos al modelo para buscar los libros con el titulo buscado (debemos ir al modelo y completar la funcion).

        $content = json_encode($libros); //[COMPLETAR]  utilizando json_encode debemos de serializar los libros que nos ha devulto el modelo y ponerlo en la variable content.
        $this->output->enable_profiler(FALSE); // quitar el profiler
        $this->view = FALSE; //linea para desactivar las vistas
        $this->layout = FALSE; //linea para desactivar el layout
        $this->load->view('ajax_view', array('content' => $content)); // por último utilizamos la vista de AJAX para pasar los objetos serializados con JSON
    }

    public function filtrar_precio_ajax() {
        $min = $this->input->post('min');
        $max = $this->input->post('max');
        $titulo = $this->input->post('titulo');
        $opts = $this->input->post('filterOpts');

        //print_r($opts);

        $libros = $this->libros->buscar($titulo, $max, $min, $opts); //llamamos al modelo para buscar los libros con el titulo buscado (debemos ir al modelo y completar la funcion).

        $content = json_encode($libros); //[COMPLETAR]  utilizando json_encode debemos de serializar los libros que nos ha devulto el modelo y ponerlo en la variable content.
        $this->output->enable_profiler(FALSE); // quitar el profiler
        $this->view = FALSE; //linea para desactivar las vistas
        $this->layout = FALSE; //linea para desactivar el layout
        $this->load->view('ajax_view', array('content' => $content));
    }

    public function autocomplete_ajax() {
        $term = $this->input->post('term');
        $libros = $this->libros->buscar_titulo($term);

        $content = json_encode($libros); //[COMPLETAR]  utilizando json_encode debemos de serializar los libros que nos ha devulto el modelo y ponerlo en la variable content.
        $this->output->enable_profiler(FALSE); // quitar el profiler
        $this->view = FALSE; //linea para desactivar las vistas
        $this->layout = FALSE; //linea para desactivar el layout
        $this->load->view('ajax_view', array('content' => $content));
    }

    public function cambiar_divisa_ajax() {

        $cantidad = $this->input->post('cantidad');
        $this->load->library("nusoap_lib");
        //date_default_timezone_set("Europe/Madrid");

        $this->nusoap_client = new nusoap_client("http://www.webservicex.com/CurrencyConvertor.asmx?wsdl", TRUE);

        if ($this->nusoap_client->fault) {
            $text = 'Error: ' . $this->nusoap_client->fault;
        } else {
            if ($this->nusoap_client->getError()) {
                $text = 'Error: ' . $this->nusoap_client->getError();
            } else {


                $par = array("FromCurrency" => $this->input->post('desde'), "ToCurrency" => $this->input->post('hasta'));

                $respuesta = $this->nusoap_client->call(
                        'ConversionRate', array($par), "http://www.webserviceX.NET/"
                );

                $content['cantidad'] = $cantidad * $respuesta["ConversionRateResult"];
                $content['moneda'] = $this->input->post('hasta');
            }
        }


        $this->output->enable_profiler(FALSE); // quitar el profiler
        $this->view = FALSE; //linea para desactivar las vistas
        $this->layout = FALSE; //linea para desactivar el layout
        $this->load->view('ajax_view', array('content' => json_encode($content)));
    }
    
    public function insertarPuntuacion_ajax()
    {
        $puntuacion = $this->input->post('puntuacion');
        $idlibro=$this->input->post('idlibro');
       
        $content=$this->libros->update_rating($puntuacion,$idlibro);

     
        $this->output->enable_profiler(FALSE); // quitar el profiler
        $this->view = FALSE; //linea para desactivar las vistas
        $this->layout = FALSE; //linea para desactivar el layout
        $this->load->view('ajax_view', array('content' => json_encode($content)));
    }
    
        public function cargarPuntuacion_ajax()
        {
            $idlibro=$this->input->post('idlibro');
            $content=$this->libros->devuelvePuntuacion($idlibro);


            $this->output->enable_profiler(FALSE); // quitar el profiler
            $this->view = FALSE; //linea para desactivar las vistas
            $this->layout = FALSE; //linea para desactivar el layout
            $this->load->view('ajax_view', array('content' => json_encode($content)));
        }

    public function comentar($id) {
        if ($this->session->userdata('usuario_valido')) {
            if ($this->input->post('CommentSubmit')) {
                $this->load->library('form_validation');
                $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
                $this->form_validation->set_rules('text-comment', 'Comentarios', 'trim|required|xss_clean');
//                
                if ($this->form_validation->run() === TRUE) {

                    $comment = $this->input->post('text-comment', TRUE);
                    $idlibro = (int) $this->uri->segment(3);

                    $comentario = array('autor' => $this->session->userdata('usuario_valido'), 'descripcion' => $comment, 'validado' => 0, 'fk_libros' => $idlibro);

                    $insertada = $this->comentarios->create($comentario);
                    if ($insertada) {
                        $this->session->set_flashdata('comentario_insertado', 'Comentario insertado correctamente. Será publicado cuando el adminsitrador lo valide.');
                        redirect('libros/ver/' . $idlibro, 'refresh');
                    }
                } else {
                    $this->session->set_flashdata('RequiredComment', 'El campo de mensaje es obligatorio para enviar el comentario.');
                    redirect('libros/ver/' . $id, 'refresh');
                }
            }
        } else { // mostrar un enlace a la página de resgistro o login
            //Nos guardamos la url para luego redireccionar aquí
            $this->session->set_userdata('origen', current_url());
        }
    }

    public function mistweets_ajax() {
        // incluir la libreria Matt Harris' OAuth library
        //require 'lib/tmhOAuth.php';
        $this->load->library('TmhOauth/TmhOauth_lib');

        // importar nuestras claves
        //require 'keys/personal_keys.php';
        // crear el objeto OAuth 
        $tmhOAuth = new tmhOAuth(array(
            'consumer_key' => 'JaVvnukoQ3O6I0MC3liH6Ia1m',
            'consumer_secret' => '68j0zQdrRkYGusg1FkuZshSBvTvnUfhQWgqp8KjMHa8ZW2famr',
            'user_token' => '2447513424-fEDBI3fgByJlvNfCXWG6mYmKYmn3wfY7n5zqYEW',
            'user_secret' => 'LyZE0Kg1ojTJAged2uPWB0PfCYRCDWAwaPEf5rLYj8Ig1',
            'curl_ssl_verifypeer' => false
        ));


        // autentificarnos
        $code = $tmhOAuth->request(
                'GET', $tmhOAuth->url('1.1/account/verify_credentials'), array(
            'include_entities' => false,
            'skip_status' => true,
                )
        );

        // comprobar que todo es correcto
        if ($code <> 200) {
            die("verify_credentials connection failure");
        }

        // recoger la información del usuario
        $userInfoObj = json_decode($tmhOAuth->response['response']);
        $twitterName = $userInfoObj->screen_name;
        $fullName = $userInfoObj->name;
        $twitterAvatarUrl = $userInfoObj->profile_image_url;
        $feedTitle = $twitterName . ' Twitter ' . $twitterName . 'Timeline';

        // hacer una llamada a la API para ver los ultimos 50 tweets
        $code = $tmhOAuth->request(
                'GET', $tmhOAuth->url('1.1/statuses/user_timeline'), array(
            'include_entities' => true,
            'count' => 50,
                )
        );

        // comprobar que todo es correcto
        if ($code <> 200) {
            die("user_timeline connection failure");
        }

        // rescatar la respuesta con los tweets
        // $homeTimelineObj = json_decode($tmhOAuth->response['response']);

        $result = array(
            'twitter_name' => $twitterName,
            'full_name' => $fullName,
            'avatar' => $twitterAvatarUrl,
            'tweets' => json_decode($tmhOAuth->response['response'])
                   
            
        );


//        //recorrerlos para mostrarlos en pantalla
//        $html = "";
//        foreach($homeTimelineObj as $obj)
//                $html .= "<br>".$obj->text;

        $this->output->enable_profiler(false);
        $this->view = false;
        $this->layout = false;
        $this->load->view('ajax_view', array('content' => json_encode($result)));
    }
    
    

}
