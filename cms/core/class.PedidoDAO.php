<?php

class PedidoDAO {

    private $connection;

    function __construct($conn) {
        $this->connection = $conn;
    }

    /**
     * Devuelve los datos de un pedido a partir de su id
     */
    function read($id) {
        try {
            $query = "SELECT *" .
                    " FROM pedidos" .
                    " WHERE id = ? ";

            $stmt = $this->connection->prepare($query);
            $stmt->execute(array($id));
            $pedido = $stmt->fetch();
            if ($pedido === false) {
                return null;
            }

            return $pedido;
        } catch (Exception $ex) {
            throw new ErrorException($ex->getMessage());
        }
    }

    /**
     * Devuelve todos los pedidos si cadena es igual a vacío, si existe un parámetro,
     * mostramos solo los pedidos que coincidan con el parámetro enviado
     */
    function getPedidos($cadena = "",$limit) {
        try {
            if ($cadena != "") {
                $consulta = "SELECT DISTINCT p.id, p.total, p.fecha, p.pagado, u.nombre, u.email, u.direccion, u.fk_ciudades" .
                        " FROM pedidos p, libros_pedidos lp, usuarios u" .
                        " WHERE p.id=lp.fk_pedidos AND p.fk_usuarios=u.id" .
                        " AND (p.id LIKE '" . mysql_real_escape_string($cadena) . "' OR u.nombre  LIKE '%" . mysql_real_escape_string($cadena) . "%' OR u.email LIKE '%" . mysql_real_escape_string($cadena) . "%')" .
                        " ORDER BY id DESC".
                        " LIMIT ".$limit;
            } else {
                $consulta = "SELECT DISTINCT p.id, p.total, p.fecha, p.pagado, u.nombre, u.email, u.direccion, u.fk_ciudades" .
                        " FROM pedidos p, libros_pedidos lp, usuarios u" .
                        " WHERE p.id=lp.fk_pedidos AND p.fk_usuarios=u.id" .
                        " ORDER BY id DESC".
                        " LIMIT ".$limit;
            }

            $result = $this->connection->query($consulta);

            if ($result !== null) {
                $pedidos = array();
                while ($pedido = $result->fetch()) {
                    /* seleccionamos el id de cada pedido */
                    $sql = "SELECT fk_libros" .
                            " FROM libros_pedidos" .
                            " WHERE fk_pedidos=" . $pedido['id'];
                    $query = $this->connection->query($sql);
                    if ($query !== null) {
                        if ($query->rowCount() > 0) {
                            $i = 0;
                            while ($obj = $query->fetch()) { //libros del pedido
                                $pedido['mislibros'][$i] = $obj['fk_libros'];

                                /* seleccionamos la portada de cada libro */
                                $sqlimg = "SELECT path_foto FROM fotos WHERE orden=1 AND fk_libros=" . $obj['fk_libros'];
                                $querys = $this->connection->query($sqlimg);

                                if ($querys !== null) {
                                    if ($querys->rowCount() > 0) {
                                        while ($objs = $querys->fetch()) {
                                            $pedido['portada'][$i] = $objs['path_foto'];
                                        }
                                    } else {
                                        $pedido['portada'][$i] = 'uploads/images/libros/thumb/nodisponible.jpg';
                                    }
                                }

                                /* seleccionamos el nombre de cada libro */
                                $sqltitulo = "SELECT titulo FROM libros WHERE id=" . $obj['fk_libros'];
                                $queryt = $this->connection->query($sqltitulo);

                                if ($queryt !== null) {
                                    if ($queryt->rowCount() > 0) {
                                        while ($objt = $queryt->fetch()) {
                                            $pedido['titulo'][$i] = $objt['titulo'];
                                        }
                                    }
                                }

                                $i++;
                            }
                        }
                    }

                    $pedidos[] = $pedido;
                }

                return $pedidos;
            }
        } catch (Exception $ex) {
            throw new ErrorException($ex->getMessage());
        }
    }

    /**
     * Devulve la información detallada de un pedido a partir de su ID
     */

    function getInfoPedido($id) {
        try {
            $query = "SELECT *" .
                    " FROM libros_pedidos" .
                    " WHERE fk_pedidos = " . $id;

            $stmt = $this->connection->query($query);
            if ($stmt !== null) {
                $pedidos = array();
                while ($pedido = $stmt->fetch()) {
                    $pedidos[] = $pedido;
                }
                return $pedidos;
            }
        } catch (Exception $ex) {
            throw new ErrorException($ex->getMessage());
        }
    }
    
    
     /**
     * Obtiene el número de pedidos totales
     */
    function getTotalPedidos() {
        $query = "SELECT COUNT(*) FROM pedidos";
        $stmt = $this->connection->prepare($query);
        //$stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchColumn();
    }
    
    /**
     * Obtiene la suma de los pedidos PAGADOS
     */
    
    function totalPagados()
    {
        $query="SELECT SUM(total) FROM pedidos WHERE pagado=1";
        $stmt=  $this->connection->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchColumn();
    }
    
    /**
     * Obtiene el número de los pedidos PAGADOS
     */
    
    function pedidosPagados()
    {
        $query="SELECT COUNT(*) FROM pedidos WHERE pagado=1";
        $stmt=  $this->connection->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchColumn();
    }
    
    /*
     * Suma total por mes de los pedidos pagados del año 2014
     */
    function pedidosMes()
    {
        try
        {
            $sql="SELECT MONTH(fecha) AS mes, YEAR(fecha) as anyo, sum(total) as total, COUNT(*) as filas".
                 " FROM pedidos".
                 " WHERE YEAR(fecha)=2014 AND pagado=1".
                 " GROUP BY mes";
            
            $stmt = $this->connection->query($sql);
            if ($stmt !== null) {
                $pedidos = array();
                while ($pedido = $stmt->fetch()) {
                    $pedidos[] = $pedido;
                }
                return $pedidos;
            }
            
        } catch (Exception $ex) {
            throw new ErrorException($ex->getMessage());
        }
        
    }
    

}
