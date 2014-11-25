<?php
if (!defined('BASEPATH'))
    exit('No permitir el acceso directo al script');

class Pedidos_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    
    /* insertar pedido y devuelve el número de pedido*/
    function create()
    {
        try
        {

            $total=  $this->session->userdata('total_precio');
            $fk_usuarios=$this->session->userdata('id');
            $date = date('Y-m-d');
            $pagado=0;

            $carro_sesion=$this->session->userdata('carro');

            $this->db->trans_begin();

            $sql="INSERT INTO pedidos(total, fecha, fk_usuarios, pagado) VALUES (?, ?, ?, ?)";
            $this->db->query($sql, array($total, $date, $fk_usuarios, $pagado)); 

            //Nos guardamos el ultimo id insertado (autoincremento)
            $fk_pedidos=$this->db->insert_id();


            foreach($carro_sesion as $id=>$cantidad)
            {
                $precio=  $this->Libros_model->getPrecio($id);           
                $data = array(
                   'fk_libros' => $id,
                   'fk_pedidos' => $fk_pedidos,
                   'cantidad' => $cantidad,
                   'precio' =>$precio
                );

                $this->db->insert('libros_pedidos', $data); 
            }//enforeach;


            if($this->db->trans_status() === TRUE){
                $this->db->trans_commit();
               // return true;
                return $fk_pedidos;
            }


        } catch (Exception $e) {
                $this->db->trans_rollback();
                throw $e;
            }
    } 
    
    /* una vez procesado el pago en paypal acutalizamos la tabla de pedidos a pagado =1*/
    function PagarPaypal($id)
    {
        
       $data = array(
               'pagado' => 1
            );

        $this->db->where('id', $id);
        $this->db->update('pedidos', $data); 
    }

}

