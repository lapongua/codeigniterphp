<?php

class UsuariosDAO {

    private $connection;

    function __construct($conn) {
        $this->connection = $conn;
    }

    function generaPass() {
        //Se define una cadena de caractares. Te recomiendo que uses esta.
        $cadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
        //Obtenemos la longitud de la cadena de caracteres
        $longitudCadena = strlen($cadena);

        //Se define la variable que va a contener la contraseña
        $pass = "";
        //Se define la longitud de la contraseña, en mi caso 10, pero puedes poner la longitud que quieras
        $longitudPass = 7;

        //Creamos la contraseña
        for ($i = 1; $i <= $longitudPass; $i++) {
            //Definimos numero aleatorio entre 0 y la longitud de la cadena de caracteres-1
            $pos = rand(0, $longitudCadena - 1);

            //Vamos formando la contraseña en cada iteraccion del bucle, añadiendo a la cadena $pass la letra correspondiente a la posicion $pos en la cadena de caracteres definida.
            $pass .= substr($cadena, $pos, 1);
        }
        return $pass;
    }

    function updatePass($id, $pass) {
        try {
            /* Iniciar una transacción, desactivando 'autocommit' */
            $this->connection->beginTransaction();

            $sql = "UPDATE usuarios SET contrasenya=:password WHERE id=:id";

            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':password', sha1($pass), PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $this->connection->commit();
        } catch (Exception $ex) {
            $this->connection->rollback();
            throw $ex;
        }
    }
    
    function updatePassEmail($id, $pass,$mail) {
        try {
            /* Iniciar una transacción, desactivando 'autocommit' */
            $this->connection->beginTransaction();

            $sql = "UPDATE usuarios SET contrasenya=:password, email=:email WHERE id=:id";

            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':password', sha1($pass), PDO::PARAM_STR);
            $stmt->bindParam(':email', $mail, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            $_SESSION['updateAdmin'] = "Datos actualizados correctamente.";
            $_SESSION['admin']=$mail;
            
            $this->connection->commit();
        } catch (Exception $ex) {
            $this->connection->rollback();
            throw $ex;
        }
    }
    
    

    /* Carga todos los usuarios si no se especifica una cadena */

    function getUsuarios($cadena = "", $limit = "",$pagina) {
        try {
            
            $pagina -= 1;
            $per_page = $limit; // Per page records
            $start = $pagina * $per_page;
            
            
            if ($cadena != "") {
                $consulta = "SELECT * FROM usuarios" .
                        " WHERE rol LIKE 'comprador'" .
                        " AND (nombre LIKE '%" . mysql_real_escape_string($cadena) . "%' OR" .
                        " email LIKE '%" . mysql_real_escape_string($cadena) . "%')" .
                        " ORDER BY id DESC" .
                        " LIMIT " . $start.", ".$per_page;
            } else {
                $consulta = "SELECT * FROM usuarios" .
                        " WHERE rol LIKE 'comprador'" .
                        " ORDER BY id DESC" .
                        " LIMIT " . $start.", ".$per_page;
            }

            $result = $this->connection->query($consulta);
            if ($result !== null) {
                $usuarios = array();
                while ($usuario = $result->fetch()) {
                    
                    $usuario['ciudad']=$this->getCiudad($usuario['fk_ciudades']);
                    $usuario['provincia']=  $this->getPais($usuario['ciudad']['fk_paises']);
                   
                    $usuarios[] = $usuario;
                }
                
                return $usuarios;
            }
        } catch (Exception $ex) {
            throw new ErrorException($ex->getMessage());
        }
    }
    /* devuelve la información de un usuario pasandole su id */
    function read($id)
    {
      try {
            $query = "SELECT *" .
                    " FROM usuarios" .
                    " WHERE id = ? ";

            $stmt = $this->connection->prepare($query);
            $stmt->execute(array($id));
            $usuario = $stmt->fetch();
            if ($usuario === false) {
                return null;
            }
            
            $usuario['ciudad']=$this->getCiudad($usuario['fk_ciudades']);
            $usuario['pais']=  $this->getPais($usuario['ciudad']['fk_paises']);

            return $usuario;
        } catch (Exception $ex) {
            throw new ErrorException($ex->getMessage());
        }
    }

    /*
     * Devuelve todos las ciudades en el caso de que no se especifique un id de ciudad
     * Sino devuelve la ciudad específica
     */

    function getCiudad($id = '') {
        if (empty($id)) {
            $sql = "SELECT id, nombre, fk_paises " .
                    "FROM ciudades ORDER BY id ASC";
        } else {
            $sql = "SELECT nombre, fk_paises FROM ciudades WHERE id= ?";
        }
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(array($id));
        $ciudad = $stmt->fetch();
        if ($ciudad === false) {
            return null;
        }

        return $ciudad;
    }

    /*
     * Devuelve todos los paises en el caso de que no se especifique un id de país
     * Sino devuelve el pais específico
     */

    function getPais($id = '') {
        if (empty($id)) {
            $sql = "SELECT * FROM paises ORDER BY nombre ASC";
        } else {
            $sql = "SELECT * FROM paises WHERE id=?";
        }
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(array($id));
        $paises = $stmt->fetch();
        if ($paises === false) {
            return null;
        }

        return $paises;
    }

    /* calcula el numero total de usuarios del rol que se le pasa por parametro */

    function totalUsuarios($rol) {
        $sql = "SELECT COUNT(*) as total FROM usuarios WHERE rol LIKE ?";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute(array($rol));
        $total = $stmt->fetch();
        if ($total === false) {
            return null;
        }

        return $total;
    }

}
