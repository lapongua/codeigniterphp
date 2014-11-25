<?php
session_start();

function __autoload($name) {
    include_once '../core/class.' . $name . '.php';
}

//Recogemos la conexión a la base de datos
$conexion = DBManager::getInstance()->getConnection();                                 
$commentDAO= new ComentariosDAO($conexion);

//Eliminar autor de la base de datos
$comment = $commentDAO->delete($_GET['id']);


header('Location:../comentarios.php');  