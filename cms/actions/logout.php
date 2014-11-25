<?php
// Iniciar la sesion para poder tener acceso a las todas las variables registradas
session_start();
//Establecer conexion con la base de datos
    if ($_SERVER['SERVER_NAME'] === 'localhost') {
        $url='http://localhost:8888/proyectoPHP/cms/';

    } else if ($_SERVER['SERVER_NAME'] === 'proyectos.proweb.ua.es') {             
        $url='http://proyectos.proweb.ua.es/p13lpg/proyecto/cms/';
    } 

if(isset($_SESSION['admin']))
{
    //Guardamos el usuario para comprobar que el usuario se había logeado
    $antiguo_usuario = $_SESSION['admin'];
    
    // Anular el registro del usuario valido de la sesion
    unset($_SESSION['admin']);
    
    // Destruir la sesion borrando el identificador de la sesion 
    session_destroy();
    session_start();
    $_SESSION['success'] = "Sesión cerrada correctamente.";
    
    header('Location: '.$url);
}
else
{
    $antiguo_usuario="";
}



?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8"/>
        <title>CERRAR SESIÓN EN UAZON - Universidad de Alicante Books</title>
    </head>
    <body>
    <?php 

//      //Si no esta vacia la variable antiguo_usuario
//      if (!empty($antiguo_usuario))
//      {
//        echo '<h1>Sesion cerrada.</h1><br />';
//      }
//      else
          if(empty($antiguo_usuario))
      {
        // Si no habian iniciado sesion pero han entrado en esta p�gina
        echo '<h1>No estabas logeado.</h1><br />'; 
      }
    ?>    
    <a href="../index.php">Volver a la pagina de login</a>
    
    </body>
</html>
