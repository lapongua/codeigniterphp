<?php
session_start();

function __autoload($name) {
    include_once '../core/class.' . $name . '.php';
}

//Recogemos la conexión a la base de datos
$conexion = DBManager::getInstance()->getConnection();                                 
$libroDAO= new LibroDAO($conexion);
//print_r($_POST['data']);
//Guarda un libro en la base de datos
//if(isset($_GET['files']))
//{
//    $data = $libroDAO->upload_fotos($_POST['data']);
//}
//else
//{
//    $data = array('success' => 'Form was submitted', 'formData' => $_POST);
//}


//echo json_encode($data);


//$return = Array('ok'=>TRUE);
//
//$upload_folder ='uploads/images/libros';
//
//$nombre_archivo = $_FILES['archivo']['name'];
//
//$tipo_archivo = $_FILES['archivo']['type'];
//
//$tamano_archivo = $_FILES['archivo']['size'];
//
//$tmp_archivo = $_FILES['archivo']['tmp_name'];
//
//$archivador = $upload_folder . '/' . $nombre_archivo;
//
//print_r($tmp_archivo."////".$archivador);
//
//if (!move_uploaded_file($tmp_archivo, $archivador)) {
//
//$return = Array('ok' => FALSE, 'msg' => "Ocurrio un error al subir el archivo. No pudo guardarse.", 'status' => 'error');
//
//}

//echo json_encode($return);
echo '<pre>'; print_r($_FILES); echo '</pre>';

$output_dir = "uploads/";

if (!is_writeable('../uploads/' . $_FILES['archivo']['name'])) {
   die("Cannot write to destination file");
}
 
if(isset($_FILES["archivo"]))
{
    //Filter the file types , if you want.
    if ($_FILES["archivo"]["error"] > 0)
    {
      $return= "Error: " . $_FILES["file"]["error"] . "<br>";
    }
    else
    {
        //move the uploaded file to uploads folder;
       if(move_uploaded_file($_FILES["archivo"]["tmp_name"],$output_dir. $_FILES["archivo"]["name"]))
       {
           $return="Uploaded File :".$_FILES["archivo"]["name"];
       }
    else 
     {
           $return="No se ha podido subir";
       }
       
 
      
    }
    
    echo json_encode($return);
 
}
else
{
    echo json_encode("noexitste");
}

