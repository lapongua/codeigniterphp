<?php

class LibroDAO {

    private $connection;

    function __construct($conn) {
        $this->connection = $conn;
    }

    /**
     * Inserta un nuevo libro
     */
    function create($libro) {
        try {
            /* Iniciar una transacción, desactivando 'autocommit' */
            $this->connection->beginTransaction();

            //Insertar libro
            $consulta = "INSERT INTO libros (isbn, voto, num_voto, n_pags, precio, titulo, descripcion, editorial)" .
                    " VALUES(:isbn,:voto,:num_voto,:paginas,:precio,:titulo,:descripcion,:editorial)";


            $stmt = $this->connection->prepare($consulta);
            $stmt->bindParam(':isbn', $libro['isbn'], PDO::PARAM_INT);
            $stmt->bindParam(':voto', $libro['voto'], PDO::PARAM_INT);
            $stmt->bindParam(':num_voto', $libro['num_voto'], PDO::PARAM_INT);
            $stmt->bindParam(':paginas', $libro['paginas'], PDO::PARAM_INT);
            $stmt->bindParam(':precio', $libro['precio'], PDO::PARAM_STR);
            $stmt->bindParam(':titulo', $libro['titulo'], PDO::PARAM_STR);
            $stmt->bindParam(':descripcion', $libro['descripcion'], PDO::PARAM_STR);
            $stmt->bindParam(':editorial', $libro['editorial'], PDO::PARAM_STR);

            $stmt->execute();

            //Nos guardamos el ultimo id insertado (autoincremento)
            $fk_libros = $this->connection->lastInsertId();

            //Insertar relación entre libro y autor
            $consulta = "INSERT INTO libros_autores (fk_libros, fk_autores, fecha)" .
                    " VALUES(:fk_libros,:fk_autores,:fecha)";

            $stmt = $this->connection->prepare($consulta);
            foreach ($libro['autores'] as $autor) {
                $stmt->bindParam(':fk_libros', $fk_libros, PDO::PARAM_INT);
                $stmt->bindParam(':fk_autores', $autor, PDO::PARAM_INT);
                $stmt->bindParam(':fecha', $libro['fecha'], PDO::PARAM_STR);
                $stmt->execute();
            }

            $_SESSION['insLibro'] = "Libro insertado correctamente.";
            //Forzamos escritura de los datos en la base de datos
            $this->connection->commit(); //Pedido insertado correctamente.';
        } catch (PDOException $pdoe) {
            // Si alguno de los insert falla, volvemos hacia atras la
            // transaccion actual antes del ultimo commit.
        }
        // echo "Libro insertado correctamente";
    }

    /**
     * Devuelve los datos de un libro a partir de su id
     */
    function read($id) {
        try {
            $query = "SELECT id, isbn, titulo, precio, editorial, n_pags, descripcion, num_voto, voto " .
                    "FROM libros " .
                    "WHERE id = ? ";

            $stmt = $this->connection->prepare($query);
            $stmt->execute(array($id));
            $libro = $stmt->fetch();
            if ($libro === false) {
                return null;
            }

            return $libro;
        } catch (Exception $ex) {
            throw new ErrorException($ex->getMessage());
        }
    }

    /**
     * Actualiza un libro
     */
    function update($libro) {
        try {
            $this->connection->beginTransaction();

            $consulta = "UPDATE libros SET isbn=:isbn, voto=:voto, num_voto=:num_voto, n_pags=:paginas, precio=:precio, titulo=:titulo, descripcion=:descripcion, editorial=:editorial" .
                    " WHERE id=:id";
            $stmt = $this->connection->prepare($consulta);
            $stmt->bindParam(':isbn', $libro['isbn'], PDO::PARAM_INT);
            $stmt->bindParam(':voto', $libro['voto'], PDO::PARAM_INT);
            $stmt->bindParam(':num_voto', $libro['num_voto'], PDO::PARAM_INT);
            $stmt->bindParam(':paginas', $libro['paginas'], PDO::PARAM_INT);
            $stmt->bindParam(':precio', $libro['precio'], PDO::PARAM_STR);
            $stmt->bindParam(':titulo', $libro['titulo'], PDO::PARAM_STR);
            $stmt->bindParam(':descripcion', $libro['descripcion'], PDO::PARAM_STR);
            $stmt->bindParam(':editorial', $libro['editorial'], PDO::PARAM_STR);
            $stmt->bindParam(':id', $libro['id'], PDO::PARAM_INT);

            $stmt->execute();

            /*
             * ESTA SOLUCIÓN ES TEMPORAL HASTA QUE TENGAMOS POR JAVASCRIPT PARA AÑADIR AUTORES
             * Borramos los autores del libro e insertamos los nuevos que existan 
             */
            $sentencia = $this->connection->prepare("SELECT fk_libros FROM libros_autores WHERE fk_libros= :id");
            $sentencia->bindParam(':id', $libro['id'], PDO::PARAM_INT);
            if ($sentencia->execute()) {
                while ($milibros = $sentencia->fetch()) {
                    $this->connection->exec("DELETE FROM libros_autores WHERE fk_libros = " . $milibros['fk_libros']);
                }
            }


            //Insertar relación entre libro y autor
            $consulta = "INSERT INTO libros_autores (fk_libros, fk_autores, fecha)" .
                    " VALUES(:fk_libros,:fk_autores,:fecha)";

            $stmt = $this->connection->prepare($consulta);
            foreach ($libro['autores'] as $autor) {
                $stmt->bindParam(':fk_libros', $libro['id'], PDO::PARAM_INT);
                $stmt->bindParam(':fk_autores', $autor, PDO::PARAM_INT);
                $stmt->bindParam(':fecha', $libro['fecha'], PDO::PARAM_STR);
                $stmt->execute();
            }

            $this->connection->commit();
        } catch (Exception $ex) {
            $this->connection->rollback();
            throw new ErrorException($ex->getMessage());
        }
    }

    /**
     * Borra un libro a partir de su id
     */
    function delete($id) {
        try {
            /* Iniciar una transacción, desactivando 'autocommit' */
            $this->connection->beginTransaction();

            /* comprobamos primero si hay pedidos antes de eliminar un libro */
            $query = "SELECT COUNT(*) FROM libros_pedidos WHERE fk_libros=:id";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();


            if ($stmt->fetchColumn() == 0) {//si no hay pedidos
                $query = "DELETE FROM libros WHERE id = :id";
                $stmt = $this->connection->prepare($query);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();

                //eliminar imagenes asociadas
                $sentencia = $this->connection->prepare("SELECT id FROM fotos WHERE fk_libros= ?");
                if ($sentencia->execute($id)) {
                    while ($fotos = $sentencia->fetch()) {
                        $this->connection->exec("DELETE FROM fotos WHERE fk_libros = " . $fotos['id']);
                    }
                }

                //Eliminamos las fotos asociadas
                $sentencia = $this->connection->prepare("SELECT id FROM comentarios WHERE fk_libros= ?");
                if ($sentencia->execute($id)) {
                    while ($comentarios = $sentencia->fetch()) {
                        $this->connection->exec("DELETE FROM comentarios WHERE fk_libros = " . $comentarios['id']);
                    }
                }

                $_SESSION['delLibro'] = "Libro eliminado correctamente.";
                //Forzamos el borrado en la base de datos
                $this->connection->commit();
            } else {
                $_SESSION['NotDelLibro'] = "NO se puede eliminar el libro porque hay pedidos ";
            }
        } catch (Exception $ex) {
            $this->connection->rollback();

            throw new ErrorException($ex->getMessage());
        }
    }

    /**
     * Devuelve todos los libros con su autor y fotos si no hay parámetro
     * Si existe un parámetro mostramos solo los libros que coincidan con el parámetro enviado
     */
    function getLibros($cadena = "", $limit) {
        try {
            if ($cadena != "") {
                $consulta = "SELECT l.id, l.isbn, l.titulo,l.n_pags,l.editorial,l.voto a.nombre AS autor, l.precio, l.descripcion" .
                        " FROM libros l, autores a, libros_autores la" .
                        " WHERE a.id=la.fk_autores AND l.id=fk_libros" .
                        " AND (l.titulo LIKE '%" . mysql_real_escape_string($cadena) . "%' OR l.isbn  LIKE '%" . mysql_real_escape_string($cadena) . "%' OR a.nombre LIKE '%" . mysql_real_escape_string($cadena) . "%')" .
                        " LIMIT " . $limit;
            } else {
                $consulta = "SELECT * FROM libros ORDER BY id DESC" .
                        " LIMIT " . $limit;
            }

            $result = $this->connection->query($consulta);

            if ($result !== null) {
                $libros = array();
                while ($libro = $result->fetch()) {

                    $sqlimg = "SELECT path_foto FROM fotos WHERE orden=1 AND fk_libros=" . $libro['id'];
                    $query = $this->connection->query($sqlimg);

                    if ($query !== null) {
                        if ($query->rowCount() > 0) {
                            while ($obj = $query->fetch()) {
                                $libro['portada'] = $obj['path_foto'];
                            }
                        } else {
                            $libro['portada'] = 'uploads/images/libros/thumb/nodisponible.jpg';
                        }
                    }

                    $autoresDAO = new AutoresDAO($this->connection);
                    //print_r($libro['id']);
                    $libro['autores'] = $autoresDAO->getAutoresLibro($libro['id']);

                    //print_r($libro['autores'])."<br/>";
                    $libros[] = $libro;
                }

                return $libros;
            }
        } catch (Exception $ex) {
            throw new ErrorException($ex->getMessage());
        }
    }

    /**
     * Obtiene el número de comentarios validados de un libro a partir de su id
     */
    function getComentariosValidadosLibro($id) {
        $query = "SELECT COUNT(*) FROM comentarios WHERE fk_libros=:id AND validado=1";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchColumn(); //como hay un resultado con fetchColumn accedemos a él
    }

    /**
     * Obtiene el número de comentarios de un libro a partir de su id
     */
    function getComentariosTotalesLibro($id) {
        $query = "SELECT COUNT(*) FROM comentarios WHERE fk_libros=:id";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    /*
     * Devuelve la portada de un libro a partir de su ID
     */

    function getFotosLibro($id) {
        try {

            $query = "SELECT * " .
                    "FROM fotos " .
                    "WHERE fk_libros = ? " .
                    "AND orden=1";

            $stmt = $this->connection->prepare($query);
            $stmt->execute(array($id));
            $foto = $stmt->fetch();
            if ($foto === false) {
                return null;
            }

            return $foto;
        } catch (Exception $ex) {
            throw new ErrorException($ex->getMessage());
        }
    }

    /*
     * Devuelve todas las fotos de un libro a partir de su ID
     */

    function getTodasFotosLibro($id) {
        try {

            $consulta = "SELECT * " .
                    "FROM fotos " .
                    "WHERE fk_libros = " . $id .
                    " ORDER BY orden ASC";

            $result = $this->connection->query($consulta);

            if ($result !== null) {
                $fotos = array();
                while ($foto = $result->fetch()) {
                    $fotos[] = $foto;
                }

                return $fotos;
            } else {
                return "null";
            }
        } catch (Exception $ex) {
            throw new ErrorException($ex->getMessage());
        }
    }

    /**
     * Obtiene el número de libros totales
     */
    function getTotalLibros() {
        $query = "SELECT COUNT(*) FROM libros";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    function upload_fotos($ficheros) {

//        $data = array();
//
//
//        $error = false;
//        $files = array();
//
//        $uploaddir = './uploads/';
//        foreach ($_FILES as $file) {
//            if (move_uploaded_file($file['tmp_name'], $uploaddir . basename($file['name']))) {
//                $files[] = $uploaddir . $file['name'];
//            } else {
//                $error = true;
//            }
//        }
//        $data = ($error) ? array('error' => 'There was an error uploading your files') : array('files' => $files);
//
//
//        return $data;
    }

}
