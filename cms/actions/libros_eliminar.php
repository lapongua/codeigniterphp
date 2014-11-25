<?php
session_start();

function __autoload($name) {
    include_once '../core/class.' . $name . '.php';
}

//Recogemos la conexión a la base de datos
$conexion = DBManager::getInstance()->getConnection();                                 
$libroDAO= new LibroDAO($conexion);

//Guarda un libro en la base de datos
$libros = $libroDAO->delete($_GET['id']);


header('Location:../libros.php');  