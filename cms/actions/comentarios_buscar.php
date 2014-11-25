<?php
session_start();

function __autoload($name) {
    include_once '../core/class.' . $name . '.php';
}

//Recogemos la conexión a la base de datos
$conexion = DBManager::getInstance()->getConnection();                                 
$comentarioDAO= new ComentariosDAO($conexion);

$comentarios=$comentarioDAO->getComentarios($_POST['q'],$_POST['validado']);

echo json_encode($comentarios);

//header('Location:../autores.php');  