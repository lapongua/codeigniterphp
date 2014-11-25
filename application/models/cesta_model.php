<?php

if (!defined('BASEPATH'))
    exit('No permitir el acceso directo al script');

class Cesta_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /*
     * Añade un libro a la cesta. La cesta consiste en un array indexado el cual los índices
     * serán los identificadores de los libros y su valor será la cantidad de los mismos.
     * $carro_session=array('3'=>1,'4'=>2);
     */

    function add($id) {
        $carro_session = array();
        if ($this->session->userdata('carro')) {
            $carro_session = $this->session->userdata('carro');
        } else {
            $this->session->set_userdata('items', 0);
            $this->session->set_userdata('total_precio', 0);
        }

        if (isset($carro_session[$id])) {
            $carro_session[$id] ++; //incrementamos la cantidad de libros
        } else {
            $carro_session[$id] = 1;
        }

        $this->session->set_userdata('carro', $carro_session);
        $carro_session = $this->session->userdata('carro');
    }

    /*
     * Método que actualiza la cesta modificando la cantidad o eliminando libros de la misma si el usuario
     * introudce un 0 en la cantidad. 
     */

    function update() {
        if ($this->session->userdata('carro')) {
            $carro_session = $this->session->userdata('carro');
            $this->load->library('form_validation');

            foreach ($carro_session as $id => $cantidad) {
                if ($this->form_validation->integer($this->input->post($id))) {
                    if ($this->input->post($id) == 0) {
                        //se elimina el índice del array
                        unset($carro_session[$id]);
                        
                    } else {
                        //Comprobamos que la cantidad es mayor que 0
                        if (intval($this->input->post($id)) > 0) {
                            $carro_session[$id] = $this->input->post($id);
                        }
                    }
                }
            }
        }

        $this->session->set_userdata('carro', $carro_session);
    }

    /*
     * Método que recorre el carro con los identificadores de los libros que hemos introducido
     * en la cesta y los guarda en un array para mostrarlos en una vista.
     * Además actualiza el número de items y el precio total del pedido.
     */

    function getLibrosCarro() {
        $librosCarro = array();
        if (!$this->session->userdata('carro')) {
            throw new InvalidArgumentException('La cesta está vacía.');
        }

        $carro_session = $this->session->userdata('carro');
        //cargamos el modelo libros_model para obtener los datos de cada libro

        $this->load->model('Libros_model');

        $items = 0;
        $total_precio = 0;

        foreach ($carro_session as $id => $cantidad) {
            //actualizamos el número de items de la cesta
            $items = $items + $cantidad;
            $libro = $this->Libros_model->read($id);

            if (!is_null($libro)) {
                //Cada índice tiene los datos del libro
                $librosCarro[$id] = $libro;
                //calculamos el precio total de la cesta
               // $precio_libro=$libro->precio;
                $total_precio= $total_precio+($cantidad * (float) $libro->precio);
            }
        }
        $this->session->set_userdata('total_precio', $total_precio);
        $this->session->set_userdata('items', $items);
        //Devolvemos un array asociativo donde cada índice 
        //contiene los datos de un libro

        return $librosCarro;
    }
    
    //nos devuelve una array con los id de los libros que hay en el carrito.
    function librosCarrito()
    {
        //Cargamos los datos del carrito para la cabecera
        $carro_session=array();
        $items=0;
        $total_precio='0.00';
        
        if($this->session->userdata('carro'))
        {
            $carro_session=$this->session->userdata('carro');//array de libos idlibro->cantidad
            
            if(count($carro_session)>0)
            {
                $this->session->set_userdata('librosCarro', $this->getLibrosCarro());
                $items=  $this->session->userdata('items');
                $total_precio=  $this->session->userdata('total_precio');
            }
            $this->session->set_userdata('items',$items);
            $this->session->set_userdata('total_precio',$total_precio);
        }
        
        return $carro_session;
    }
    
    /* borra un libro a partir de su id*/
    function delete_libro($id_libro) {
    if ($this->session->userdata('carro')) {
        $carro_session = $this->session->userdata('carro');
      //  $this->load->library('form_validation');

        foreach ($carro_session as $id => $cantidad) {
            
            if ($id == $id_libro) {
                //se elimina el índice del array
                unset($carro_session[$id]);
            }            
        }
    }

    $this->session->set_userdata('carro', $carro_session);
    $this->session->set_userdata('librosCarro',$this->librosCarrito());
}

}
