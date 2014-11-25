<?php
session_start();

//Comprobamos si la sesión está activa, sino volvemos a la página index.php
if (!isset($_SESSION['admin'])) {
    header('Location: index.php');
}
include_once '_header.php'; 
include_once '_navbar.php';

$editar=FALSE;
$isok=false; //la inicializamos a true si el id es correcto o estamos dando de alta un usuario
if(isset($_GET['id']) && is_numeric($_GET['id'])){
    //Recogemos la conexión a la base de datos
   
    $conexion = DBManager::getInstance()->getConnection();                                 
    $libroDAO= new LibroDAO($conexion);
    $libros = $libroDAO->read($_GET['id']);
    if($libros==NULL){ echo 'El libro que está editando no existe';}
    else {
           echo 'ediando<br>';
           $editar=TRUE;          
           $isok=true;
           if(!isset($_GET['error']))
            {
               foreach ($libros as $libro=>$value)
               { 
                  $$libro=$value;              
               }
            }
            else {
                $titulo=$_SESSION['titulo'];
                $isbn=$_SESSION['isbn'];
                $editorial=$_SESSION['editorial'];
                $n_pags=$_SESSION['paginas'];
                $descripcion=$_SESSION['descripcion'];
                $precio=$_SESSION['precio'];
            }
           $comentariosValidos= $libroDAO->getComentariosValidadosLibro($_GET['id']);
           $comentariosTotales= $libroDAO->getComentariosTotalesLibro($_GET['id']);
           
           
        }
}
else if(isset($_GET['id']) && !is_numeric($_GET['id']))
{
    echo 'editando. libro no existe<br>';
}
else
{
       
    echo 'Libro nuevo';
    $isok=TRUE;
    if(!isset($_GET['error']))
    {
        $titulo='';
        $isbn='';
        $editorial='';
        $n_pags='';
        $descripcion='';
        $precio=''; 
    }
    else {
        $titulo=$_SESSION['titulo'];
        $isbn=$_SESSION['isbn'];
        $editorial=$_SESSION['editorial'];
        $n_pags=$_SESSION['paginas'];
        $descripcion=$_SESSION['descripcion'];
        $precio=$_SESSION['precio'];
    }
    
    $comentariosValidos=0;
    $comentariosTotales=0;
    $num_voto=0;
}
if($isok)
{
?>

<div class="clearfix wrapper-title">
    <h1 class="pull-left"><span class="glyphicon glyphicon-book"></span>Libros <span class="autor"><?php echo $titulo; ?></span></h1>
    <a href="javascript:history.back()" class="back pull-right" title="Atrás"><span class="fa fa-arrow-left"></span> Volver</a>
</div>

<nav>
    <ol class="breadcrumb">
        <li><a href="home.php" title="Ir a Inicio">Home</a></li>
        <li><a href="libros.php" title="Ir a Libros">Libros</a></li>
       <li class="active"><?php echo $titulo; ?></li>
    </ol>
</nav>

<?php

if (isset($_GET['error']) && isset($_SESSION['erroreslibro']) === true) {
       ?>
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <i class="fa fa-thumbs-o-up"></i>
                <?php echo $_SESSION['erroreslibro'];?>
            </div>
        <?php
        //Borramos la variable para que en caso de recargar la página no aparezca
        unset($_SESSION['erroreslibro']);
}
        
if(isset($_GET['id']) && is_numeric($_GET['id'])){
    
?>
    <form id="edit-book" role="form" action="actions/libros_guardar.php?id=<?php echo $_GET['id']; ?>" method="post">
<?php
}
 else {
 ?>
    <form id="edit-book" role="form" action="actions/libros_guardar.php" method="post">
<?php   
}
    ?>
<div class="row"> 
    
    <div class="col-md-6">
        
            <div class="row">
                <div class="form-group col-md-4">
                    <label for="e-isbn">ISBN</label>
                    <input type="text" class="form-control" name="isbn" id="e-isbn" placeholder="Introduce el isbn" value="<?php echo $isbn; ?>">
                </div>
                <div class="fl-stars col-md-5 col-xs-8">
                    <p>
<!--                      <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i>-->
                      <?php
                        for($i=1;$i<=5;$i++){
                            if($i<=$voto)
                            {
                                echo '<i class="fa fa-star "></i>';
                            }
                            else
                            {
                                echo '<i class="fa fa-star-o"></i>';
                            }
                        }
                        ?>
                      
                      
                      <small><?php echo $num_voto; ?> votos</small>
                    </p>
                </div>
                <div class="fl-comments col-md-3 col-xs-4">
                    <p>
                      <i class="fa fa-comments"></i> <?php echo $comentariosValidos; ?>/<?php echo $comentariosTotales; ?>
                    </p>
                  </div>
                
            </div>
            <div class="row">
                <div class="form-group col-md-10">
                    <label for="e-titulo">Título</label>
                    <input type="text" class="form-control" name="titulo" id="e-titulo" placeholder="Introduce el título del libro" value="<?php echo $titulo; ?>">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-5">
                    <label for="e-editorial">Editorial</label>
                    <input type="text" class="form-control" name="editorial" id="e-editorial" placeholder="Introduce la editorial" value="<?php echo $editorial; ?>">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-10">
                    <label for="e-descripcion">Descripción</label>
                    <textarea class="form-control" name="descripcion" id="e-descripcion" rows="3"><?php echo $descripcion; ?></textarea>
                </div>
            </div>
            <div class="row">
                <div class="form-group right-inner-addon col-md-3">
                    <label for="e-precio">Precio</label>
                    <input type="text" class="form-control input-group" name="precio" id="e-precio" placeholder="Precio"  value="<?php echo $precio; ?>">
                    <i class="fa fa-euro"></i>
                </div>
                <div class="form-group col-md-3">
                    <label for="e-paginas">Páginas</label>
                    <input type="text" class="form-control input-group" name="paginas" id="e-paginas" placeholder="Nº páginas" value="<?php echo $n_pags; ?>">
                </div>
                
            </div>
            <div class="row">
                <div class="col-md-10">
                    <button type="submit" name="submit" class="btn btn-primary pull-right"><i class="fa fa-check"></i> Guardar</button>
                </div>
            </div>
        
      </div>
      <div class="col-md-6">
          <div class="row">
              <div class="col-md-6">                  
                <div class="form-group">
                    <label for="search-autor">Autores</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-search"></i></span>
                        <input type="text" placeholder="Nombre autor" id="search-autor" class="form-control" name="search-autor">
                    </div>
                </div>
  
                  <?php
            try
            {
                $conexion = DBManager::getInstance()->getConnection();
                  $autoresDAO= new AutoresDAO($conexion);
                  $aut = $autoresDAO->getAutores();
                  if($editar)
                  {
                      $au=$autoresDAO->getAutoresLibro($_GET['id']);

                      
                      foreach ($au as $au_selecc)
                      {
                          $autoresdellibro[]=$au_selecc['id'];
                      }
                      
//                      print_r("<pre>");
//                      print_r($autoresdellibro);
//                      print_r("</pre>");
//                      
                      
                  }
                                  
//                 echo "numero de autores: ".count($aut);
                    
                 ?>                  
                <select name="autor[]" id="autor" multiple class="form-control">
                    <?php
                   foreach ($aut as $autor)
                   {  
                       $selected='';
                       if($editar)
                       {
                           if(in_array($autor['id'], $autoresdellibro))
                           {
                                $selected='selected';
                           }
                       }
                       
                        ?>
                        <option value="<?php echo $autor['id'];?>" <?php echo $selected?>><?php echo $autor['nombre'];?></option>
                  <?php
                    }
                    ?>
                </select>
                  <?php
              //  $db=null;

            }catch (Exception $e) {
            echo $e->getMessage();
          }
        ?>
                    
              </div>
              <div class="col-md-6">
                  <label>Autores de <?php echo $titulo; ?></label>
                  <select multiple class="form-control">
                      <?php
                      
                   $autores=$autoresDAO->getAutoresLibro($_GET['id']);
                   foreach ($autores as $autor)
                   {                        
                        ?>
                      <option value="<?php echo $autor['id']; ?>"><?php echo $autor['nombre']; ?></option>
                    <?php
                    }
                    ?>
                  </select>
              </div>
          </div>
      </div>
        
    
</div><!-- fin de row -->

<div class="row">
      <div class="col-md-12">
          <h2>Fotografías</h2>
          <div class="form-group">
            <label for="e-subir">Subir fotografías</label>
            <input type="file" id="e-subir">
            <p class="help-block">Fotografías en formato jpg a 800x600px con máximo.</p>
          </div>
          <div class="imagenes-subidas">
              <?php
              $fotoslibro = $libroDAO->getTodasFotosLibro($_GET['id']);
              
              foreach ($fotoslibro as $fotos)
              {
              ?>
              <img height="120" src="../<?php echo $fotos['path_foto'];?>" alt="" />
              <?php
              }
              ?>
          </div>
      </div>
 </div>
</form>





<?php
}//fin de editar o dar de alta
else
{
    ?>
        <div class="clearfix wrapper-title">
    <h1 class="pull-left">El Libro que está intentando editar no Existe.</h1>
    <a href="javascript:history.back()" class="back pull-right" title="Atrás"><span class="fa fa-arrow-left"></span> Volver</a>
</div>
    <?php
}
?>


<?php include_once '_footer.php';?>
