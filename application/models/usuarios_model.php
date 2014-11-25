<?php
if ( ! defined('BASEPATH')) exit('No permitir el acceso directo al script');

class Usuarios_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    
    /*
     * Comprueba si el usuario existe en la bda.
     * DEVUELVE: 
     * Si existe: un objeto con id, nombre y rol.
     * No existe: lanza una excepción.
     */
    function getUsuarioLogin($email,$contrasenya)
    {
        //AND rol='comprador'
        $sql="SELECT id, nombre, rol ".
                "FROM usuarios ".
                "WHERE email= ? ".
                "AND contrasenya= ?";
        
        $usuario = $this->db->query($sql,array($email,$contrasenya))->row();

        if(empty($usuario))
        {
            return null;
        }

        return $usuario;
    }
    
    /*
     * Devuelve los datos de un usuario, el id y nombre del país y de la ciudad.
     */
    function read($id)
    {
        $sql = "SELECT  *" .
                "FROM usuarios U " .
                "WHERE U.id = ?";
        
        $usuario=$this->db->query($sql,array($id))->row();

        if(empty($usuario))
        {
            return null;
        }

        $arrayciudades=$this->getCiudad($usuario->fk_ciudades);
        $usuario->ciudad=$arrayciudades[0]->nombre;
        $usuario->pais = $this->getPais($arrayciudades[0]->fk_paises);           
        
       // print_r($arrayciudades[0]);
        
        return $usuario;
    }
    
    /*
     * Devuelve todos los paises en el caso de que no se especifique un id de país
     * Sino devuelve el pais específico
     */
    function getPais($id='')
    {
        if(empty($id))
        {
            $sql="SELECT * FROM paises ORDER BY nombre ASC";
            $query = $this->db->query($sql);
        }
        else {
           $sql="SELECT * FROM paises WHERE id=?";
           $query = $this->db->query($sql,array($id));
        }
        
        $paises = array();
        if ($query->num_rows() > 0)
        {
            foreach ($query->result() as $pais) {
                 $paises[] = $pais;
            }
        }
        $query->free_result();
        return $paises;
    }
    
    /*
     * Devuelve todos las ciudades en el caso de que no se especifique un id de ciudad
     * Sino devuelve la ciudad específica
     */
    function getCiudad($id='')
    {
        if(empty($id))
        {
            $sql="SELECT id, nombre, fk_paises ".
                 "FROM ciudades ORDER BY id ASC";
            $query = $this->db->query($sql);
        }
        else {
           $sql="SELECT nombre, fk_paises FROM ciudades WHERE id= ?";
           $query = $this->db->query($sql,array($id));
        }  
        
        $ciudades = array();
        if ($query->num_rows() > 0)
        {
            foreach ($query->result() as $ciudad) {
                 $ciudades[] = $ciudad;
            }
        }
        $query->free_result();
        return $ciudades;
    }
    
    /*delueve las ciudades de un pais, sino devuelve todas*/
    function getCiudadesPorPais($id)
    {
        if(empty($id))
        {
            $sql="SELECT id, nombre ".
                 "FROM ciudades ORDER BY id ASC";
            $query = $this->db->query($sql);
        }
        else {
           $sql="SELECT id,nombre FROM ciudades WHERE fk_paises= ?".
                 " ORDER BY nombre ASC";
           $query = $this->db->query($sql,array($id));
        }
        
        $ciudades = array();
        if ($query->num_rows() > 0)
        {
            foreach ($query->result() as $ciudad) {
                 $ciudades[] = $ciudad;
            }
        }
        $query->free_result();
        return $ciudades;
        
        
        
    }
    
    /* SQL q inserta un suario en la tabla de usuarios */
    function create($usuario)
    {
  
        //Insertar usuario
         $sql=
            "INSERT INTO usuarios (nombre, direccion, cp, email, rol, contrasenya, fk_ciudades)".
            " VALUES(?,?,?,?,?,?,?)";
         
         $this->db->query($sql, array($usuario['nombre'], $usuario['address'], $usuario['postcode'], $usuario['email'],$usuario['rol'], $usuario['password'],$usuario['city'])); 
         
         //El número de ID de cuando se realizo una inserción a la base de datos.
         return $this->db->insert_id();
    }
    
    /*
     * Actualiza los datos de un usuario de la base de datos
     */
    function update($usuario)
    {
        $sql="UPDATE usuarios SET nombre=?, direccion=?, cp=?, email=?, rol=?, contrasenya=?, fk_ciudades=?".
             " WHERE id=?";
        
        $this->db->query($sql, array($usuario['nombre'], $usuario['address'], $usuario['postcode'], $usuario['email'],$usuario['rol'], $usuario['password'],$usuario['city'],$usuario['id'])); 
        
    }
    
   /*
     * Devuelve TRUE o FALSE en el caso de que exista o no el email en la tabla de usuarios
     */
    function existeEmail($email)
    {
        $sql="SELECT email FROM usuarios WHERE email= ?";
        $query = $this->db->query($sql,array($email));
        if ($query->num_rows() > 0)
        {
            return TRUE;
        }
        else 
        {
            return FALSE;
        }
    }
    
    function coincidePassword($id,$password)
    {
        $sql="SELECT contrasenya FROM usuarios WHERE id=? AND contrasenya=?";
        $query=  $this->db->query($sql,array($id,$password));
        if ($query->num_rows() > 0)
        {
            return TRUE;
        }
        else 
        {
            return FALSE;
        }
        
    }
    
    /* Devuelve el email del usuario que ha hecho el pedido para enviar email de confirmación */
    function getEmail($id)
    {
        $sql="SELECT email FROM usuarios WHERE id = ?";
        
        $email=$this->db->query($sql,array($id))->row();

        if(empty($email))
        {
            return null;
        }
        
        return $email->email;
    }
    
    function generateXML()
    {
        $sql = "SELECT direccion,nombre,rol,email,fk_ciudades FROM usuarios";
	
        $Rset = $this->db->query($sql);

        $retorno = "";
        $retorno .= "<?xml version='1.0' encoding='utf-8'?><usuarios>";

        if ($Rset->num_rows() > 0)
        {
            foreach ($Rset->result() as $usuario) {
                $direccion = $usuario->direccion;
                $nombre = $usuario->nombre;
                $rol = $usuario->rol;
                $email = $usuario->email;
                $fk_ciudes=$usuario->fk_ciudades;
                
                 //Devolvemos la ciudad del usuario
                $consulta="SELECT nombre, fk_paises FROM ciudades WHERE id= ".$fk_ciudes;       
                $arrayciudades = $this->db->query($consulta);
                foreach ($arrayciudades->result() as $ciudad) {
                    $ciud=$ciudad->nombre;
                    $idpais=$ciudad->fk_paises;
                }
               
            
            
                //Devolvemos el país del usuario
                $con="SELECT nombre FROM paises WHERE id= ".$idpais;
                $arraypaises = $this->db->query($con);
                foreach ($arraypaises->result() as $pa) {
                    $pais=$pa->nombre;
                }

                $retorno .= "<usuario direccion='".$direccion.",".$ciud.",".$pais."' nombre='".$nombre."' rol='".$rol."' email='".$email."'/>";
        }
            //$retorno=utf8_decode($retorno);

             $Rset->free_result();
             $arrayciudades->free_result();
             $arraypaises->free_result();
        }
         
       $retorno .= "</usuarios>";
       return $retorno;
    }
    
    
    
}

