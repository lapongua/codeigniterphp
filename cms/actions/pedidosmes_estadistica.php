<?php
session_start();

function __autoload($name) {
    include_once '../core/class.' . $name . '.php';
}

//Recogemos la conexión a la base de datos
$conexion = DBManager::getInstance()->getConnection();                                 
$pedidosDAO= new PedidoDAO($conexion);

$pedidos=$pedidosDAO->pedidosMes();


echo json_encode($pedidos);
  