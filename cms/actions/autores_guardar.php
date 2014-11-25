<?php

session_start();

function __autoload($name) {
    include_once '../core/class.' . $name . '.php';
}

//Recogemos la conexión a la base de datos
$conexion = DBManager::getInstance()->getConnection();
$autorDAO = new AutoresDAO($conexion);

if (isset($_GET['id'])===TRUE) {
    //editar el autor
    $autor=$autorDAO->updateAutor($_GET['id'],$_POST['editedNombre']);
    
   // echo $_GET['id']."/".$_POST['editedNombre'];
} else {

    //Añade un autor
    $autor = $autorDAO->add($_POST['nombreAutor'], $_POST['biografiaAutor']);
  //  echo "no hay id";
}






//header('Location:../autores.php');