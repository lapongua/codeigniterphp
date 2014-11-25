<?php
session_start();


function __autoload($name) {
    include_once '../core/class.' . $name . '.php';
}


function validarCampos()
{
    //Definimos las variables de manera global para poder usarlas dentro de la función
    global $libro, $error;
    $valido = true;
    
    $isbn = trim($libro['isbn']);
    $titulo = trim($libro['titulo']);
    $editorial = trim($libro['editorial']);
    $precio = doubleval(trim($libro['precio']));
    $paginas = intval(trim($libro['paginas']));
    $descripcion = trim($libro['descripcion']);
      
    
    if (get_magic_quotes_gpc() == false) {
        $isbn = addslashes($isbn);
        $titulo = addslashes($titulo);
        $editorial = addslashes($editorial);
        $descripcion = addslashes($descripcion);
    }
    
    if (empty($isbn)) {
        $error .= 'ISBN vacío y entero. ';
        $valido = false;
    }
    if (empty($titulo) || !is_string($titulo)) {
        $error .= 'Título vacío. ';
        $valido = false;
    }
    if (empty($editorial) || !is_string($editorial)) {
        $error .= 'Editorial vacía. ';
        $valido = false;
    }
    if (!is_double($precio) || $precio <= 0.0) {
        $error .= 'El precio debe ser mayor que cero. ';
        $valido = false;
    } else {
        $precio = number_format($precio, 2);
    }
    if (!is_int($paginas) || $paginas <= 0) {
        $error .= 'El número de páginas debe ser mayor que cero. ';
        $valido = false;
    }
    if (empty($descripcion) || !is_string($descripcion)) {
        $error .= 'Descripción vacía. ';
        $valido = false;
    }
    
    if (count($libro['autores']) <= 0) {
        $error .= 'El nº de autores seleccionado debe ser como mínimo uno. ';
        $valido = false;
    }
       
    return $valido;
}

try
{
    
        if (!isset($_POST['submit']))
        {
            throw new ErrorException("Tienes que acceder a esta página a través del formulario.");   
        } 
        
        $error="";
        $libro= array();
        $libro['isbn']=$_POST['isbn'];
        $libro['titulo']=$_POST['titulo'];
        $libro['editorial']=$_POST['editorial'];
        $libro['precio']=str_replace(',','.',$_POST['precio']);
        $libro['paginas']=$_POST['paginas'];
        $libro['descripcion']=$_POST['descripcion'];
        $libro['autores']=$_POST['autor']; //Array de autores
        $libro['voto']=0;
        $libro['num_voto']=0;
        $libro['fecha']=date('Y-m-d');
        $libro['id']=$_GET['id'];
    
        if(validarCampos()==false)
        {
            $_SESSION['erroreslibro']=$error;
            $_SESSION['isbn']=$libro['isbn'];
            $_SESSION['titulo']=$libro['titulo'];
            $_SESSION['editorial']=$libro['editorial'];
            $_SESSION['precio']=$libro['precio'];
            $_SESSION['paginas']=$libro['paginas'];
            $_SESSION['descripcion']=$libro['descripcion'];
           // $libro['autores']=$_POST['autor']; //Array de autores
            
            
            header('Location:../libros_ficha.php?error=1');
           throw new ErrorException($error);
           
        }
       
        //Recogemos la conexión a la base de datos
        $conexion = DBManager::getInstance()->getConnection();                                 
        $libroDAO= new LibroDAO($conexion);

       if(isset($_GET['id']) && is_numeric($_GET['id'])){
           //Guarda un libro en la base de datos
        $libros = $libroDAO->update($libro);
           
       }
       else
       {
        //Guarda un libro en la base de datos
        $libros = $libroDAO->create($libro);
       }
       
        
       header('Location:../libros.php');            
        
}
catch (Exception $e) 
{
     echo ' <strong>Error:</strong> '.$e->getMessage();
}