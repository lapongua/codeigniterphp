<?php
if ( ! defined('BASEPATH')) exit('No permitir el acceso directo al script');
class Usuarios extends MY_Controller {    
    protected $models = array('usuarios','cesta'); 
    protected $asides = array();
    
    function __construct() {
        parent::__construct();
        $this->output->enable_profiler(true);
        
    }
    
    public function loginform() {
            
       $this->data['titulo'] = "Acceso del Cliente";
       
       if($this->input->post('send'))
       {
           $email=  $this->input->post('username',TRUE);
           $password= $this->input->post('contrasenya',TRUE);
           
           $miusuario=$this->usuarios->getUsuarioLogin($email,sha1($password));
           if(!empty($miusuario))//si existe el usuario
           {
                $this->data['usuarios'] = $miusuario;
                $this->session->set_userdata('usuario_valido',$miusuario->nombre);
                $this->session->set_userdata('id',$miusuario->id);
                $this->session->set_userdata('rol',$miusuario->rol);
                $this->session->set_flashdata('loginsuccess', 'Sesión iniciada correctamente.');
                if($this->session->userdata('origen'))
                {
                    $redirect=$this->session->userdata('origen');
                    $this->session->unset_userdata('origen');
                    redirect($redirect, 'refresh');
                }
                else {
                   redirect('/', 'refresh'); 
                }  
           }
           else
           {
               $this->session->set_userdata('error', 'Email o Password incorrecto');
               
           }
       }
       
       //CARRITO
        $this->data['carro_session']=  $this->cesta->librosCarrito();
        $this->data['librosCarro']=  $this->session->userdata('librosCarro');
        $this->data['items']=$this->session->userdata('items');
        $this->data['total_precio']=$this->session->userdata('total_precio');    
    }
    
      function ajax_login_form()
      {

        $user = $this->input->post('user');
        $pass = $this->input->post('pass');
               
        $usuario=$this->usuarios->getUsuarioLogin($user,sha1($pass));
        
        
        
        if(!empty($usuario))//si existe el usuario
        {
                
            $this->session->set_userdata('usuario_valido',$usuario->nombre);
            $this->session->set_userdata('id',$usuario->id);
            $this->session->set_userdata('rol',$usuario->rol);
            //$this->session->set_flashdata('loginsuccess', 'Sesión iniciada correctamente.');
            
            $content=$usuario->nombre;
            
            
        }
        else {
            $content=0;
        }
        
        $this->output->enable_profiler(FALSE); // quitar el profiler
        $this->view = FALSE; //linea para desactivar las vistas
        $this->layout = FALSE; //linea para desactivar el layout
        $this->load->view('ajax_view',array('content'=>$content));
    }

    
    
    public function addform()
    {       
        $this->data['titulo'] = "Crear nueva cuenta de Cliente";
//        $paises=$this->usuarios->getPais();
//        if(!empty($paises))//si existen paises
//        {
//            $this->data['paises']=$paises;
//        }
//        $ciudades=$this->usuarios->getCiudad();
//        if(!empty($ciudades))//si existen paises
//        {
//            $this->data['ciudades']=$ciudades;
//        }
        
        if($this->input->post('submit'))
        {
            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
            $this->form_validation->set_rules('name','Nombre','trim|required|xss_clean');
            $this->form_validation->set_rules('email','Email','required|valid_email|xss_clean|callback__comprobarEmail');
            $this->form_validation->set_rules('password','Contraseña','trim|required|min_length[5]|max_length[10]|matches[repassword]|xss_clean|sha1');
            $this->form_validation->set_rules('repassword','Confirme contraseña','trim|required');
            $this->form_validation->set_rules('address','Dirección','trim|required|xss_clean');           
            $this->form_validation->set_rules('country','País','trim|required|xss_clean|callback__comprobarPais');
            $this->form_validation->set_rules('city','Ciudad','trim|required|xss_clean|callback__comprobarCiudad');
            $this->form_validation->set_rules('postcode','Código postal','trim|required|numeric|xss_clean');
            $this->form_validation->set_message('_comprobarEmail', 'El Email ya existe.');
            $this->form_validation->set_message('_comprobarPais', 'Elige un país.');
            $this->form_validation->set_message('_comprobarCiudad', 'Elige una ciudad.');

            
            if ($this->form_validation->run() === TRUE) {
                $usuario=array();
                $usuario['nombre']=  $this->input->post('name');
                $usuario['email']=  $this->input->post('email');
                $usuario['password']=  $this->input->post('password');
                $usuario['address']=  $this->input->post('address');
                $usuario['city']=  $this->input->post('city');
                $usuario['postcode']=  $this->input->post('postcode');
                $usuario['rol']='comprador';
                
                
                $id_usuario=$this->usuarios->create($usuario);
                $this->session->set_userdata('id', $id_usuario);
                $this->session->set_userdata('usuario_valido',$usuario['nombre']);
                $this->session->set_userdata('rol',$usuario['rol']);
                $this->session->set_flashdata('exito','Registro completado correctamente.');
                
                if($this->session->userdata('origen'))
                {
                    $redirect=$this->session->userdata('origen');
                    $this->session->unset_userdata('origen');
                    redirect($redirect, 'refresh');
                }
                else
                {
                    redirect('/', 'refresh');
                }
            } 
        }
        
          //CARRITO
        $this->data['carro_session']=  $this->cesta->librosCarrito();
        $this->data['librosCarro']=  $this->session->userdata('librosCarro');
        $this->data['items']=$this->session->userdata('items');
        $this->data['total_precio']=$this->session->userdata('total_precio');
    }
    
            
    function _comprobarEmail($email)
    {
        return !($this->usuarios->existeEmail($email));
    }
    
    /*devuelve TRUE o FALSE si se ha seleccionado o no un pais*/
    function _comprobarPais($country)
    {
        if($country!=0){ return TRUE;}
        else{ return FALSE;}            
    }
    
    /*devuelve TRUE o FALSE si se ha seleccionado o no una ciudad*/
    function _comprobarCiudad($city)
    {
        if($city!=0){ return TRUE;}
        else{ return FALSE;}            
    }
    
    function _comprobarPass($oldpassword)
    {
        
         return ($this->usuarios->coincidePassword($this->session->userdata('id'),$oldpassword));          
    }
    

    public function ver()
    {
        if($this->session->userdata('usuario_valido'))
        {
            $this->data['titulo'] = "Mi perfil";
            $this->data['usuario']=$this->usuarios->read($this->session->userdata('id'));
            //CARRITO
            $this->data['carro_session']=  $this->cesta->librosCarrito();
            $this->data['librosCarro']=  $this->session->userdata('librosCarro');
            $this->data['items']=$this->session->userdata('items');
            $this->data['total_precio']=$this->session->userdata('total_precio');   
            
            $paises=$this->usuarios->getPais();
            if(!empty($paises))//si existen paises
            {
                $this->data['paises']=$paises;
            }
            $ciudades=$this->usuarios->getCiudad();
            if(!empty($ciudades))//si existen paises
            {
                $this->data['ciudades']=$ciudades;
            }
            $emailanterior=  $this->data['usuario']->email;
            
            if($this->input->post('update-pass'))
            {   
                
                $validarEmail='';
                if($this->input->post('email')!=$emailanterior)
                {
                    $validarEmail='|callback__comprobarEmail';
                }
                
                $this->load->library('form_validation');
                $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
                $this->form_validation->set_rules('name','Nombre','trim|required|xss_clean');
                $this->form_validation->set_rules('email','Email','required|valid_email|xss_clean'.$validarEmail);                
                $this->form_validation->set_rules('address','Dirección','trim|required|xss_clean');           
                $this->form_validation->set_rules('country','País','trim|required|xss_clean|callback__comprobarPais');
                $this->form_validation->set_rules('city','Ciudad','trim|required|xss_clean|callback__comprobarCiudad');
                $this->form_validation->set_rules('postcode','Código postal','trim|required|numeric|xss_clean');
                $this->form_validation->set_rules('oldpassword','Contraseña anterior','trim|required|min_length[5]|max_length[10]|xss_clean|sha1|callback__comprobarPass');
                $this->form_validation->set_rules('password','Contraseña','trim|required|min_length[5]|max_length[10]|matches[repassword]|xss_clean|sha1');
                $this->form_validation->set_rules('repassword','Confirme contraseña','trim|required');
                $this->form_validation->set_message('_comprobarEmail', 'El Email ya existe.');
                $this->form_validation->set_message('_comprobarPais', 'Elige un país.');
                $this->form_validation->set_message('_comprobarCiudad', 'Elige una ciudad.');
                $this->form_validation->set_message('_comprobarPass', 'La Contraseña no coincide con la anterior.');
               
               if($this->form_validation->run() === TRUE) {
                    $usuario=array();
                    $usuario['nombre']=  $this->input->post('name');
                    $usuario['email']=  $this->input->post('email');
                    $usuario['password']=$this->input->post('password');
                    $usuario['address']=  $this->input->post('address');
                    $usuario['city']=  $this->input->post('city');
                    $usuario['postcode']=  $this->input->post('postcode');
                    $usuario['rol']=$this->session->userdata('rol');
                    $usuario['id']=$this->session->userdata('id');
                    $this->usuarios->update($usuario);
                    $this->session->set_userdata('usuario_valido',$usuario['nombre']);
                    $this->session->set_flashdata('updateCorrect','Usuario actualizado correctamente.');                   
                }
                
            }
                      
        }
        else
        {
            show_error('No tiene permiso para acceder a esta p&aacute;gina');
        }
    }
    
    function logout() {
        if ($this->session->userdata('usuario_valido')) {
            $this->session->unset_userdata('usuario_valido');
            $this->session->unset_userdata('id');
            $this->session->unset_userdata('rol');
            $this->session->set_flashdata('logout', 'Sesión cerrada correctamente.');
            if($this->session->userdata('origen')){$this->session->unset_userdata('origen');}
           // if($this->session->userdata('loginsuccess')){$this->session->unset_userdata('loginsuccess');}
            redirect('/', 'refresh');
        }        
    }
    
	
    //Callback comprobacion de cuenta de correo usuario
    function ajax_comprobar_email()
    {
	 
        $email = $this->input->post('email');

        if($this->_comprobarEmail($email))
        {
            $content = 1;//no existe el email
        }
        else
        {
            $content = 0;//existe email
        }

        $this->output->enable_profiler(FALSE); // quitar el profiler
        $this->view = FALSE; //linea para desactivar las vistas
        $this->layout = FALSE; //linea para desactivar el layout
        $this->load->view('ajax_view',array('content'=>$content));
		
    }
    
    //Callback para recoger todos los paises
    function ajax_get_paises()
    {
        $pais = $this->input->post('pais');
        
        //$paises=$this->usuarios->getPais();
        $content="";
        if($pais==0)//si existen paises
        {
            $paises=$this->usuarios->getPais();
            
            $content.='<option value="0">- Selecciona un país -</option>';
            ?>

            
            <?php       
            
            foreach ($paises as $pais)
            {
                
                $selected="";
                if($pais->id==set_value('country'))
                {

                    $selected="selected";
                }
                
                $content.="<option value=".$pais->id." ".$selected.">".$pais->nombre."</option>";              
            }
   
        }
            
        
        $this->output->enable_profiler(FALSE); // quitar el profiler
        $this->view = FALSE; //linea para desactivar las vistas
        $this->layout = FALSE; //linea para desactivar el layout
        $this->load->view('ajax_view',array('content'=>$content));
    }
    
    //Callback para recoger todas las ciudades del pais seleccionado
    function ajax_get_ciudades()
    {
        $pais = $this->input->post('pais');
        $content="";
        
        if($pais>0)//si existen paises
        {
            $ciudades=$this->usuarios->getCiudadesPorPais($pais);
            
            $content.='<option value="0">- Selecciona un país -</option>';
            ?>

            
            <?php       
                        //print_r($paises);
            foreach ($ciudades as $ciudad)
            {
               // print_r($pais);
                $content.="<option value=".$ciudad->id.">".$ciudad->nombre."</option>";              
            }
   
        }
        
        $this->output->enable_profiler(FALSE); // quitar el profiler
        $this->view = FALSE; //linea para desactivar las vistas
        $this->layout = FALSE; //linea para desactivar el layout
        $this->load->view('ajax_view',array('content'=>$content));
    }
    
  
    /*
     * Mapa de usuarios: Localización de los usuarios registrados en READ.ME
     */
    
    public function mapa()
    {
        $this->data['titulo'] = "Mapa de usuarios registrados en tu librería online READ.Me";
        //CARRITO
        $this->data['carro_session']=  $this->cesta->librosCarrito();
        $this->data['librosCarro']=  $this->session->userdata('librosCarro');
        $this->data['items']=$this->session->userdata('items');
        $this->data['total_precio']=$this->session->userdata('total_precio');  
    }
    
    public function mapa_ajax()
    {
        $content=$this->usuarios->generateXML();
        
        $this->output->enable_profiler(FALSE); // quitar el profiler
        $this->view = FALSE; //linea para desactivar las vistas
        $this->layout = FALSE; //linea para desactivar el layout
        $this->load->view('ajax_view',array('content'=>$content));
    }
      
    
}