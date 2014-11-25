<?php

class AutoresDAO {

    private $connection;

    function __construct($conn) {
        $this->connection = $conn;
    }

    /**
     * Devuelve todos los autores
     */
    function getAutores() {
        try {
            $consulta = "SELECT * FROM autores ORDER BY id DESC";
            $result = $this->connection->query($consulta);
            if ($result !== null) {
                $autores = array();
                while ($reg = $result->fetch()) {
                    $autores[] = $reg;
                }
                return $autores;
            }
        } catch (Exception $ex) {
            throw new ErrorException($ex->getMessage());
        }
    }

//fin getAutores

    /**
     * Devuelve todos los autores de un libro especifico
     */
    function getAutoresLibro($id) {
        try {
            $sql = "SELECT a.nombre, a.id " .
                    "FROM autores a, libros_autores la " .
                    "WHERE a.id=la.fk_autores and la.fk_libros=" . $id;

            $result = $this->connection->query($sql);
            $autor = null;
            if ($result->rowCount() > 0) {
                while ($autor = $result->fetch()) {
                    $autores[] = $autor;
                }
            }
            //$result->free_result();
            return $autores;
        } catch (Exception $ex) {
            throw new ErrorException($ex->getMessage());
        }
    }

//fin getAutores


    /* buscador de titulo del autor */

    public function buscar_autor($cadena) {
        try {

            if (!empty($cadena)) {
                $sql = "SELECT nombre AS label, id FROM autores WHERE nombre like '%" . $cadena . "%'";
                $result = $this->connection->query($sql);

                $autor = null;
                if ($result->rowCount() > 0) {
                    while ($autor = $result->fetch()) {
                        $autores[] = $autor;
                    }
                }
                return $autores;
            }
        } catch (Exception $ex) {
            throw new ErrorException($ex->getMessage());
        }
    }

    /*
     * Añade un nuevo autor
     */

    function add($nombre, $biografia) {
        try {
            if (!empty($nombre)) {
                /* Iniciar una transacción, desactivando 'autocommit' */
                $this->connection->beginTransaction();

                //Insertar autor
                $consulta = "INSERT INTO autores (nombre, biografia)" .
                        " VALUES(:nombre,:biografia)";


                $stmt = $this->connection->prepare($consulta);
                $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
                $stmt->bindParam(':biografia', $biografia, PDO::PARAM_STR);

                $stmt->execute();

                $_SESSION['addAutor'] = "Autor insertado correctamente.";
                //Forzamos escritura de los datos en la base de datos
                $this->connection->commit(); //Autor insertado correctamente.';
            } else {
                throw new ErrorException('Nombre vacío!');
            }
        } catch (PDOException $pdoe) {
            // Si alguno de los insert falla, volvemos hacia atras la
            // transaccion actual antes del ultimo commit.
            $this->connection->rollback();
            throw new ErrorException($pdoe->getMessage());
        }
        // echo "Autor insertado correctamente";
    }

    /**
     * Borra un autor a partir de su id
     */
    function delete($id) {
        try {
            /* Iniciar una transacción, desactivando 'autocommit' */
            $this->connection->beginTransaction();

            /* comprobamos primero si hay libros asignados a los autores antes de eliminar */
            $query = "SELECT COUNT(*) FROM libros_autores WHERE fk_autores=:id";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->fetchColumn() == 0) { //no hay libros asignados al autor, PODEMOS BORRARLO
                $query = "DELETE FROM autores WHERE id = :id";
                $stmt = $this->connection->prepare($query);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();

                $_SESSION['delAutor'] = "Autor eliminado correctamente.";
                //Forzamos el borrado en la base de datos
                $this->connection->commit();
            } else {
                $_SESSION['NotDelAutor'] = "¡NO se puede eliminar el autor porque tiene pedidos asignados!";
            }
        } catch (Exception $ex) {
            $this->connection->rollback();

            throw new ErrorException($ex->getMessage());
        }
    }

    /*
     * Actualiza el nombre del autor
     */

    function updateAutor($id, $nombre) {
        try {
            if (!empty($nombre)) {
                $this->connection->beginTransaction();

                $consulta = "UPDATE autores SET nombre=:nombre" .
                        " WHERE id=:id";
                $stmt = $this->connection->prepare($consulta);
                $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);

                $stmt->execute();

                $this->connection->commit();
            } else {
                throw new ErrorException('Nombre vacío!');
            }
        } catch (Exception $ex) {
            $this->connection->rollback();
            throw new ErrorException($ex->getMessage());
        }
    }

}
