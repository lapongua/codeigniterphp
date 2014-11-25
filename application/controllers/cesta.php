<?php
if ( ! defined('BASEPATH')) exit('No permitir el acceso directo al script');
class Cesta extends MY_Controller {    
    protected $models = array('cesta'); 
    protected $asides = array();
    
    function __construct() {
        parent::__construct();
        $this->output->enable_profiler(true);
        
    }
    
    function ver()
    {
        $this->data['titulo'] = "Cesta de la compra";
        
        //CARRITO
        $this->data['carro_session']=  $this->cesta->librosCarrito();
        $this->data['librosCarro']=  $this->session->userdata('librosCarro');
        $this->data['items']=$this->session->userdata('items');
        $this->data['total_precio']=$this->session->userdata('total_precio');
        
    }
    
    function add()
    {
        //cesta/add/3
        if($this->uri->segment(3)===FALSE)
        {
            show_error('No has especificado ningún id de libro correcto.');
        }
        $id=(int)  $this->uri->segment(3);
       // echo $id;
        $this->cesta->add($id);
        redirect('cesta/ver','refresh');
        
    }
    
    function add_libro_ajax()
    {
        $id = $this->input->post('id'); 
        $this->cesta->add($id);
        
        $totalitems=$this->session->userdata('items');
        $this->session->set_userdata('items',$totalitems+1);       
        $this->session->set_userdata('carro',$this->cesta->librosCarrito());//[28]->2,[3]->1
        
        $libros_carro=array(
            "libroscesta"=>$this->cesta->getLibrosCarro(),//para el nombre
            "carro_session"=>$this->session->userdata('carro'),//idlibro=>cantidad
            "items_cesta"=>$this->session->userdata('items')
        );
        
        $content= json_encode($libros_carro);
        
        $this->data['items']=$this->session->userdata('items');
        
        $this->output->enable_profiler(FALSE); // quitar el profiler
        $this->view = FALSE; //linea para desactivar las vistas
        $this->layout = FALSE; //linea para desactivar el layout
        $this->load->view('ajax_view',array('content'=>$content)); 
    }
    
    function update()
    {
        if($this->input->post('update')!==FALSE)
        {
            $this->cesta->update();
        }
        redirect('cesta/ver','refresh');
    }
    
    public function eliminar_libro_ajax()
    {
        $id = $this->input->post('id'); 
        $this->cesta->delete_libro($id);
        
        //Comprovem quins llibres hi ha en el carrito
        $this->data['carro_session']=  $this->cesta->librosCarrito();
        
        if(count($this->data['carro_session'])>0)
        {
        $libros_carro = array(
            "libroscesta"=>$this->cesta->getLibrosCarro(),//para el nombre
            "carro_session"=>$this->session->userdata('carro'),//idlibro=>cantidad
            "quedan_cesta"=>$this->session->userdata('items')
         ); //matriz de arrays de libros con datos
        }
        else
        {
            $libros_carro=array();
        }
 
        
        $content= json_encode($libros_carro);
        
        $totalitems=$this->session->userdata('items');	
        
        if($content=="[]")
        {
            $this->session->set_userdata('items',0);
            $this->session->set_userdata('total_precio',0);
           // $this->session->set_userdata('librosCarro',array());           
        }
        else
        {           
            $this->session->set_userdata('items',$totalitems);
        }
              
        $this->data['librosCarro']=  $this->session->userdata('librosCarro');
        $this->data['items']=$this->session->userdata('items');
        $this->data['total_precio']=$this->session->userdata('total_precio');
        
        $this->output->enable_profiler(FALSE); // quitar el profiler
        $this->view = FALSE; //linea para desactivar las vistas
        $this->layout = FALSE; //linea para desactivar el layout
        $this->load->view('ajax_view',array('content'=>$content)); // por último utilizamos la vista de AJAX para pasar los objetos serializados con JSON
    }
}

