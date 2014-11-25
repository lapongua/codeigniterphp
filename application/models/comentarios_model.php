<?php

if (!defined('BASEPATH'))
    exit('No permitir el acceso directo al script');

class Comentarios_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

   function create($comentario)
   {        
        $data = array(
           'autor' => $comentario['autor'],
           'descripcion' => $comentario['descripcion'],
           'validado' => $comentario['validado'],
           'fk_libros' => $comentario['fk_libros']
        );

        $this->db->insert('comentarios', $data);
        return ($this->db->affected_rows() != 1) ? false : true;
   }
}




