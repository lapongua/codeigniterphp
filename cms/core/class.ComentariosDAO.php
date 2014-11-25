<?php

class ComentariosDAO {

    private $connection;

    function __construct($conn) {
        $this->connection = $conn;
    }

    /**
     * Devuelve todos los comentarios
     */
    function getComentarios($cadena = "", $valido="-",$limit=100) {
        try {

            if ($cadena != "") {
                if ($valido == "-") {
                    $consulta = "SELECT l.titulo, l.id as id, c.autor, c.descripcion, c.id as idc, c.fk_libros, c.validado" .
                            " FROM libros l, comentarios c" .
                            " WHERE l.id=c.fk_libros" .
                            " AND (l.titulo LIKE '%" . mysql_real_escape_string($cadena) . "%' OR" .
                            " c.autor LIKE '%" . mysql_real_escape_string($cadena) . "%' OR" .
                            " c.descripcion LIKE '%" . mysql_real_escape_string($cadena) . "%')" .
                            " ORDER BY c.id DESC ".
                            " LIMIT ".$limit;
                }
                else
                {
                    $consulta = "SELECT l.titulo, l.id as id, c.autor, c.descripcion, c.id as idc, c.fk_libros, c.validado" .
                            " FROM libros l, comentarios c" .
                            " WHERE l.id=c.fk_libros" .
                            " AND (l.titulo LIKE '%" . mysql_real_escape_string($cadena) . "%' OR" .
                            " c.autor LIKE '%" . mysql_real_escape_string($cadena) . "%' OR" .
                            " c.descripcion LIKE '%" . mysql_real_escape_string($cadena) . "%')" .
                            " AND c.validado=".mysql_real_escape_string($valido)."".
                            " ORDER BY c.id DESC".
                            " LIMIT ".$limit;
                }
            } else {
                if ($valido == "-") {
                    $consulta = "SELECT l.titulo, l.id as id, c.autor, c.descripcion, c.id as idc, c.fk_libros, c.validado".
                            " FROM comentarios c, libros l".
                            " WHERE l.id=c.fk_libros".
                            " ORDER BY c.id DESC".
                            " LIMIT ".$limit;
                }
                else
                {
                    $consulta = 
                            "SELECT l.titulo, l.id as id, c.autor, c.descripcion, c.id as idc, c.fk_libros, c.validado".
                            " FROM comentarios c, libros l".
                            " WHERE l.id=c.fk_libros".
                            " AND c.validado=".mysql_real_escape_string($valido).".".
                            " ORDER BY c.id DESC".
                            " LIMIT ".$limit;
                }
            }

            $result = $this->connection->query($consulta);
            if ($result !== null) {
                $comentarios = array();



                while ($reg = $result->fetch()) {
                    $sql = "SELECT titulo FROM libros WHERE id=" . $reg['id'];
                    $query = $this->connection->query($sql);

                    if ($query !== null) {
                        if ($query->rowCount() > 0) {
                            while ($obj = $query->fetch()) {
                                $reg['nomllibre'] = $obj['titulo'];
                            }
                        }
                    }

                    $comentarios[] = $reg;

                }
                return $comentarios;
            }
        } catch (Exception $ex) {
            throw new ErrorException($ex->getMessage());
        }
    }

    /* actualiza el estado de un comentario, de validado a no validado y viceversa */

    function actualizar_validacion($id) {
        try {


            $this->connection->beginTransaction();

            $query = "SELECT validado FROM comentarios WHERE id=:id";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $validado = str_replace('"', '', $stmt->fetchColumn());

            if ($validado == "0") {
                $validado = 1;
            } else {
                $validado = 0;
            }

            $consulta = "UPDATE comentarios SET validado=:validado" .
                    " WHERE id=:id";
            $stmt = $this->connection->prepare($consulta);
            $stmt->bindParam(':validado', $validado, PDO::PARAM_INT);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            $stmt->execute();

            $this->connection->commit();

            return $validado;
        } catch (Exception $ex) {
            $this->connection->rollback();
            throw new ErrorException($ex->getMessage());
        }
    }


    /**
     * Borra un comentario a partir de su id
     */
    function delete($id) {
        try {
            /* Iniciar una transacción, desactivando 'autocommit' */
            $this->connection->beginTransaction();

            $query = "DELETE FROM comentarios WHERE id = :id";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $_SESSION['delComentario'] = "Comentario eliminado correctamente.";
            //Forzamos el borrado en la base de datos
            $this->connection->commit();              
            
        } catch (Exception $ex) {
            $this->connection->rollback();

            throw new ErrorException($ex->getMessage());
        }
    }
    
    /* calcula el numero total de comentarios */
    function totalComentarios()
    {
        $sql="SELECT COUNT(*) as total FROM comentarios";
        
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $total = $stmt->fetch();
        if ($total === false) {
            return null;
        }

        return $total;  
    }
}
