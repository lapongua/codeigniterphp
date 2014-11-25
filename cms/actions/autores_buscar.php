<?php
session_start();

function __autoload($name) {
    include_once '../core/class.' . $name . '.php';
}

//Recogemos la conexión a la base de datos
$conexion = DBManager::getInstance()->getConnection();                                 
$autorDAO= new AutoresDAO($conexion);

//print_r($_POST['term']);
$autor=$autorDAO->buscar_autor($_POST['term']);

echo json_encode($autor);

//header('Location:../autores.php');  