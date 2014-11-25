<?php
session_start();

function validar_campos()
{
    global $email;
    global $password;
    global $errors;
    
      
    //Si no hay letras devolvemos falso.
    if (!preg_match("/[a-z]/i", $password)) {
        $errors="La contraseña debe contener letras";
        return false;
    }
        //Si no hay números devolvemos falso.
//    if (!preg_match("/[0-9]/", $password)) {
//        return false;
//    }
    
    if (strlen($password) < 5 || strlen($password) > 10) {
        $errors="La contraseña debe contener entre 5 y 10 carateres.";
        return false;
    }
    
    //El email tiene que tener más de 4 caracteres
    if (empty($email) || strlen($email) < 5 ) {
        $errors="El usuario tiene que tener más de 5 caracteres";
        return false;
    }
    
    //Formato de email incorrecto
    if(!preg_match("/^[a-z0-9]+([\.]?[a-z0-9_-]+)*@[a-z0-9]+([\.-]+[a-z0-9]+)*\.[a-z]{2,}$/", $email))
    {    
        $errors="El usuario tiene que tener el formato de email: 'usuario@email.com'";
        return false;
    }
    
     if (get_magic_quotes_gpc() == false) { 
        $email = addslashes($email);
        $password = addslashes($password);
     }

    return true;
}

try
{
    
    // Comprovem si venim desde el submit
    if (isset($_POST['submit']) === false) {
        throw new Exception("Acceso incorrecto.");
    }
    
    //Recoger las variables email y password
    $email=trim($_POST['email']);
    $password=trim($_POST['pass']);
    
    $errors = "";
    
    if(validar_campos()==false)
    {
        throw new Exception($errors);
    }
    
    $password=sha1($password);
    
    //Establecer conexion con la base de datos
    if ($_SERVER['SERVER_NAME'] === 'localhost') {
        $servidor = 'localhost';
        $bd = 'readme'; $user = 'root'; $pwd = 'root';

    } else if ($_SERVER['SERVER_NAME'] === 'proyectos.proweb.ua.es') {             
        $servidor = 'localhost'; $bd = 'DBp13lpg'; $user = 'p13lpg'; $pwd = '48328029S';
    }

    $db = new PDO('mysql:host='.$servidor.';dbname='. $bd,$user, $pwd);
    $db->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    $db->query("SET NAMES 'utf8'");
    
     try{
            $consulta="SELECT * ".
                 " FROM usuarios".
                 " WHERE email=:email AND contrasenya=:password AND rol=:rol";

            $rol="administrador";
            //Preparamos la consulta
            $stmt = $db->prepare($consulta);
            
            //Asociamos los parámetros a la plantilla
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':password', $password, PDO::PARAM_STR);
            $stmt->bindParam(':rol', $rol, PDO::PARAM_STR);

            //Ejecutamos la instrucción
            if(!$stmt->execute())
            {
                throw new PDOException("El usuario no existe.");
            }

            //Obtener el usuario
            $usuario=$stmt->fetch();
            if($usuario != 0)
            {
                $stored_email=$usuario['email'];
                $stored_pass=$usuario['contrasenya'];
                $stored_rol=$usuario['rol'];
                $stored_nombre=$usuario['nombre'];
                $stored_id=$usuario['id'];
            }
            else
            {
                throw new PDOException("El usuario no existe.");
                
            }
                        
            //Si el usuario es correcto
            
            if($email===$stored_email && $password===$stored_pass && $rol===$stored_rol)
            {
                $_SESSION['admin']=$stored_email;
                $_SESSION['user']=$stored_nombre;
                $_SESSION['id']=$stored_id;
                $lugar='../home.php';
            }
            
        } catch (PDOException $pdoex) {
            $_SESSION['error'] = "El usuario/contraseña son incorrectos.";
            $lugar='../index.php';
        }
        
} catch (Exception $ex) {
    $_SESSION['error'] = "El usuario/contraseña son incorrectos.";
    $lugar='../index.php';
}

if (isset($db) === true) {
    $db = null;
}
header('Location: '.$lugar);

?>
