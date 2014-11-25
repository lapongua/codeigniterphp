<?php
session_start();

function __autoload($name) {
    include_once '../core/class.' . $name . '.php';
}

//Recogemos la conexión a la base de datos
$conexion = DBManager::getInstance()->getConnection();                                 
$autorDAO= new AutoresDAO($conexion);

//Eliminar autor de la base de datos
$autor = $autorDAO->delete($_GET['id']);


header('Location:../autores.php');  