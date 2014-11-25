<?php
if ( ! defined('BASEPATH')) exit('No permitir el acceso directo al script');
class Comentarios extends MY_Controller {    
    protected $models = array('comentarios'); 
    protected $asides = array();
    
    function __construct() {
        parent::__construct();
        $this->output->enable_profiler(true);
        
        if(!$this->session->userdata('usuario_valido'))
        {
            //Nos guardamos la url para luego redireccionar aquí
            $origen=current_url();
            $this->session->set_userdata('origen',$origen);
            
            $this->session->set_flashdata('RequiredLog', 'Para realizar un comentario necesitas estar registrado.');
            redirect('usuarios/loginform','refresh');
        }
    }
   
}