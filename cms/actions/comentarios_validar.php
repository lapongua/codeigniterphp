<?php
session_start();

function __autoload($name) {
    include_once '../core/class.' . $name . '.php';
}

//Recogemos la conexión a la base de datos
$conexion = DBManager::getInstance()->getConnection();                                 
$comentarioDAO= new ComentariosDAO($conexion);

//print_r($_POST['id']);
$validado=$comentarioDAO->actualizar_validacion($_POST['id']);
echo $validado;

//header('Location:../autores.php');  