<?php
session_start();

function __autoload($name) {
    include_once '../core/class.' . $name . '.php';
}

//Recogemos la conexión a la base de datos
$conexion = DBManager::getInstance()->getConnection();                                 
$usuarioDAO= new UsuariosDAO($conexion);

//print_r($_POST['term']);
$listado_usuarios=$usuarioDAO->getUsuarios($_POST['cadena'],5,$_POST['page']);

echo json_encode($listado_usuarios);
