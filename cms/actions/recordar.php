<?php
// Iniciar la sesion para poder tener acceso a las todas las variables registradas
session_start();

function __autoload($name) {
    include_once '../core/class.' . $name . '.php';
}

//Recogemos la conexión a la base de datos
$conexion = DBManager::getInstance()->getConnection();                                 
$usuariosDAO= new UsuariosDAO($conexion);


try {
    // Comprovem si venim desde el submit
    if (isset($_POST['submit']) === false) {
        throw new Exception("Acceso incorrecto.");
    }

    $email = trim($_POST['email']);

    //Establecer conexion con la base de datos
    if ($_SERVER['SERVER_NAME'] === 'localhost') {
        $servidor = 'localhost';
        $bd = 'readme';
        $user = 'root';
        $pwd = 'root';
    } else if ($_SERVER['SERVER_NAME'] === 'proyectos.proweb.ua.es') {
        $servidor = 'localhost';
        $bd = 'DBp13lpg';
        $user = 'p13lpg';
        $pwd = '48328029S';
    }

    $db = new PDO('mysql:host=' . $servidor . ';dbname=' . $bd, $user, $pwd);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->query("SET NAMES 'utf8'");

    try {
        $consulta = "SELECT * " .
                " FROM usuarios" .
                " WHERE email=:email AND rol=:rol";

        $rol = "administrador";
        //Preparamos la consulta
        $stmt = $db->prepare($consulta);

        //Asociamos los parámetros
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':rol', $rol, PDO::PARAM_STR);

        //Ejecutamos la instrucción
        if (!$stmt->execute()) {
            throw new PDOException("No existe ningún administrador con ese email.");
        }

        //Obtener el usuario
        $usuario = $stmt->fetch();
        if ($usuario != 0) {
            $stored_email = $usuario['email'];
            $stored_nombre = $usuario['nombre'];
            $stored_id=$usuario['id'];
             $_SESSION['success'] = " Se ha enviado un email a la cuenta solicitada con un nuevo password.";
             $pass_aleatorio=$usuariosDAO->generaPass();
             
             $asunto="[PROWEB] Solicitud de Nueva Contraseña";
             $cuerpo="Buenas <b>".$stored_nombre."</b>:<br> Se ha renovado su contraseña correctamente. Sus datos son los siguientes:".
                     "<br>Email: <b>".$stored_email."</b>".
                     "<br>Password: <b>".$pass_aleatorio."</b>".
                     "<br>Un saludo".
                     "<br>----".
                     "<br>Proweb Team";
             $cabeceras  = 'MIME-Version: 1.0' . "\r\n";
             $cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
             $cabeceras .= 'From: webmaster@example.com';
             //Enviamos el email con el password
             mail($stored_email, $asunto, $cuerpo,$cabeceras);
             //actualiazamos el email en la base de datos
             $usuariosDAO->updatePass($stored_id,$pass_aleatorio);
             
             $lugar = '../recordar-contrasenya.php';
            
           // echo $stored_email;
            
            
        } else {
            throw new PDOException("No existe ningún administrador con ese email.");
           
        }
    } catch (Exception $ex) {     
        $lugar = '../recordar-contrasenya.php?error=1';
    }
} catch (Exception $ex) {
     $lugar = '../recordar-contrasenya.php?error=1';
}

if (isset($db) === true) {
    $db = null;
}
header('Location: '.$lugar);

