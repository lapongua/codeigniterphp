<?php

if (!defined('BASEPATH'))
    exit('No permitir el acceso directo al script');

class Libros_model extends CI_Model {

//reemplazamos el constructor y llamamos al del padre
    function __construct() {
        parent::__construct();
    }

    //Devuelve un array de libros
    public function getLibros($num = '') {
        if ($num > 0) {
            $sql = "SELECT L.id, L.titulo, L.precio, L.descripcion, L.editorial " .
                    "FROM libros L " .
                    "ORDER BY L.id DESC LIMIT " . $num;
        } else {
            $sql = "SELECT L.id, L.titulo, L.precio " .
                    "FROM libros L " .
                    "ORDER BY L.id DESC";
        }


        $query = $this->db->query($sql);
        $libros = array();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $libro) {

                $sqlimg = "SELECT path_foto FROM fotos WHERE orden=1 AND fk_libros=" . $libro->id;
                $query = $this->db->query($sqlimg);
                if ($query->num_rows() > 0) {
                    $libro->portada = $this->db->query($sqlimg)->row()->path_foto;
                } else {
                    $libro->portada = 'uploads/images/libros/thumb/nodisponible.jpg';
                }

                $libro->autores = $this->getAutores($libro->id);
                $libros[] = $libro;
            }
        }
        $query->free_result();
        return $libros;
    }

    //Devuelve la variable que le pasamos de 1 libro
    public function readVariable($id, $variable) {
        $sql = "SELECT " . $variable . " " .
                "FROM libros " .
                "WHERE id = ?";

        $libro = $this->db->query($sql, array($id))->row();

        if (empty($libro)) {
            return null;
        }

        return $libro;
    }

    //Devuelve los datos de 1 libro
    public function read($id) {
        $sql = "SELECT L.id, L.isbn, L.titulo, L.precio, L.descripcion, L.editorial,L.resumen " .
                "FROM libros L " .
                "WHERE L.id = ?";

        $libro = $this->db->query($sql, array($id))->row();

        if (empty($libro)) {
            return null;
        }

        $libro->autores = $this->getAutores($id);
        $libro->comentarios = $this->getComentarios($id);
        $libro->fotos = $this->getFotos($id);
//           print_r("<pre>");
//           print_r($libro);
//            print_r("</pre>");
        return $libro;
    }

    public function getComentarios($id) {
        $sql = "SELECT id, autor, descripcion " .
                "FROM comentarios " .
                "WHERE validado=1 AND fk_libros=" . $id;

        $query = $this->db->query($sql);
        $comentarios = null;
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $comentario) {
                $comentarios[] = $comentario;
            }
        }
        $query->free_result();
        return $comentarios;
    }

    public function getAutores($id) {
        $sql = "SELECT a.nombre, a.biografia " .
                "FROM autores a, libros_autores la " .
                "WHERE a.id=la.fk_autores and la.fk_libros=" . $id;

        $query = $this->db->query($sql);
        $autor = null;
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $autor) {
                $autores[] = $autor;
            }
        }
        $query->free_result();
        return $autores;
    }

    /*
     * Devolvemos un array de fotos especificas de un libro ordenadas ASC
     */

    public function getFotos($id) {
        $sql = "SELECT path_foto " .
                "FROM fotos " .
                "WHERE fk_libros= ? " .
                "ORDER BY orden ASC";

        $query = $this->db->query($sql, array($id));
        $foto = null;
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $foto) {
                $fotos[] = $foto;
            }
        }
        $query->free_result();
        return $fotos;
    }

    /* buscador de titulo del libro */

    public function buscar_titulo($cadena) {

        if (!empty($cadena)) {
            $sql = "Select l.titulo from libros l where l.titulo like '%" . $this->db->escape_like_str($cadena) . "%'";
            $query = $this->db->query($sql);
            $libros = array();
            if ($query->num_rows() > 0) {
                foreach ($query->result() as $libro) {
                    // $libros[] = $libro;
                    array_push($libros, array('label' => $libro->titulo));
                }
                $query->free_result();
                return $libros;
            }
            return NULL;
        }
    }

    /* Buscador de libros */

    public function buscar($cadena, $pmax = '100', $pmin = '0', $opts = '5') {
        //Realizamos la consulta
        if (!empty($cadena)) {//SI EL TITUL NO ESTÁ BUID
            $consulta = "SELECT l.id, l.isbn, l.titulo, a.nombre AS autor, l.precio, l.descripcion" .
                    " FROM libros l, autores a, libros_autores la" .
                    " WHERE a.id=la.fk_autores AND l.id=fk_libros" .
                    " AND (l.titulo LIKE '%" . $this->db->escape_like_str($cadena) . "%' OR l.isbn  LIKE '%" . $this->db->escape_like_str($cadena) . "%' OR a.nombre LIKE '%" . $this->db->escape_like_str($cadena) . "%')" .
                    " AND (l.precio>=" . $pmin . " AND l.precio<=" . $pmax . ")";
        } else {//TITUL BUID
            $consulta = "SELECT l.id, l.isbn, l.titulo, a.nombre AS autor, l.precio, l.descripcion" .
                    " FROM libros l, autores a, libros_autores la" .
                    " WHERE a.id=la.fk_autores AND l.id=fk_libros" .
                    " AND (l.precio>=" . $pmin . " AND l.precio<=" . $pmax . ")";
        }

        switch ($opts) {
            case '1':
                $consulta .= " ORDER BY l.titulo ASC";
                break;
            case '2':
                $consulta .= " ORDER BY l.titulo DESC";
                break;
            case '3':
                $consulta .= " ORDER BY l.precio ASC";
                break;
            case '4':
                $consulta .= " ORDER BY l.precio DESC";
                break;
            case '5':
                $consulta .= " ORDER BY l.id DESC";
                break;
            case '6':
                $consulta .= " ORDER BY l.id ASC";
                break;
        }



        $query = $this->db->query($consulta);
        $libros = array();
        if ($query->num_rows() > 0) {

            foreach ($query->result() as $libro) {

                //print_r($libro);

                $sqlimg = "SELECT path_foto FROM fotos WHERE orden=1 AND fk_libros=" . $libro->id;
                $query = $this->db->query($sqlimg);
                if ($query->num_rows() > 0) {
                    $libro->portada = $this->db->query($sqlimg)->row()->path_foto;
                } else {
                    $libro->portada = 'uploads/images/libros/thumb/nodisponible.jpg';
                }

                $libro->autores = $this->getAutores($libro->id);
                $libros[] = $libro;
            }
            $query->free_result();
            return $libros;
        }

        return null;
    }

    /* Devuelve el precio de un libro a partir de su identificador */

    public function getPrecio($id) {
        $sql = "SELECT precio FROM libros WHERE id = ?";

        $precio = $this->db->query($sql, array($id))->row();

        if (empty($precio)) {
            return null;
        }

        return $precio->precio;
    }
    
    /* devuelve la puntuación media de un libro a partir de su id */
    public function devuelvePuntuacion($idlibro)
    {
         $sql = "SELECT voto FROM libros WHERE id = ?";

        $votos = $this->db->query($sql, array($idlibro))->row();

        if (empty($votos)) {
            return null;
        }

        return $votos->voto;
    }
    
    

    /*
     * Actualizar puntuacion del libro
     */

    public function update_rating($valor,$libro) {

        $sql = "SELECT voto,num_voto " .
                "FROM libros " .
                "WHERE id=".$libro;

        $query = $this->db->query($sql);
        //$libros = array();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $milibro) {
                
                $numerodevotos=$milibro->num_voto+1;
                $votos=round(($milibro->voto+$valor)/2);
                
                $sql = "UPDATE libros SET voto=?, num_voto=?" .
                " WHERE id=?";

                $this->db->query($sql, array($votos,$numerodevotos,$libro));

            }
        }
        $query->free_result();
        return $votos;
        
    }

}
