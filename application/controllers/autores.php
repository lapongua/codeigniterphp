<?php
if ( ! defined('BASEPATH')) exit('No permitir el acceso directo al script');
class Autores extends MY_Controller {    
    protected $models = array('autores','cesta'); 
    protected $asides = array();
    
    function __construct() {
        parent::__construct();
        $this->output->enable_profiler(true);
        
    }
    
    function ver()
    {
       
        $this->data['titulo'] = "Listado de autores";
        
        //CARRITO
        $this->data['carro_session']=  $this->cesta->librosCarrito();
        $this->data['librosCarro']=  $this->session->userdata('librosCarro');
        $this->data['items']=$this->session->userdata('items');
        $this->data['total_precio']=$this->session->userdata('total_precio');
              
    }
       
}



