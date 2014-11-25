<?php

session_start();

function __autoload($name) {
    include_once '../core/class.' . $name . '.php';
}

//Recogemos la conexión a la base de datos
$conexion = DBManager::getInstance()->getConnection();
$usuarioDAO = new UsuariosDAO($conexion);

if (isset($_GET['id'])===TRUE) {
    //MODIFICAR EL PASSWORD
    $usuarioDAO->updatePassEmail($_GET['id'], $_POST['updatePass'],$_POST['updateEmail']);
 
} else {

    //Añade un autor
    //$autor = $autorDAO->add($_POST['nombreAutor'], $_POST['biografiaAutor']);
}

 header('Location:../config.php'); 