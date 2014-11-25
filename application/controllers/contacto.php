<?php
if ( ! defined('BASEPATH')) exit('No permitir el acceso directo al script');
class Contacto extends MY_Controller {    
    protected $models = array('contacto'); 
    protected $asides = array();
    
    function __construct() {
        parent::__construct();
       
    }
    
    function send_email_ajax()
    {
        $datos['nombre']=$this->input->post('nombre');
        $datos['correo']=$this->input->post('correo');
        $datos['comentarios']=$this->input->post('comentarios');
       
        
        //load the library parser
        $this->load->library('parser');

        $htmlMessage =  $this->parser->parse('layouts/email_contacto', $datos, true);

        $config['protocol'] = 'sendmail';
        $config['mailpath'] = '/usr/sbin/sendmail';
        $config['charset'] = 'UTF-8';
        $config['wordwrap'] = TRUE;
        $config['mailtype'] = 'html';
        
        $this->load->library('email');
                
        $this->email->initialize($config);

        $this->email->from($datos['nombre'], $datos['correo']);
        $this->email->to('lapongua@gmail.com');//el que hace el pedido
       // $this->email->cc('lapongua@gmail.com');
       // $this->email->bcc('lapongua@gmail.com');//administrador

        $this->email->subject('Formulario de contacto desde READ.ME tu libreria online');
        $this->email->message($htmlMessage);

        $content=$this->email->send();
        
        
        $this->output->enable_profiler(FALSE); // quitar el profiler
        $this->view = FALSE; //linea para desactivar las vistas
        $this->layout = FALSE; //linea para desactivar el layout
        $this->load->view('ajax_view',array('content'=>$content));
    }
}