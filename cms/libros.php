<?php
session_start();

//Comprobamos si la sesión está activa, sino volvemos a la página index.php
if (!isset($_SESSION['admin'])) {
    header('Location: index.php');
}
include_once '_header.php';
include_once '_navbar.php';


//Recogemos la conexión a la base de datos
$conexion = DBManager::getInstance()->getConnection();
$libroDAO = new LibroDAO($conexion);
if (isset($_GET['q']) === true) {
    $buscamos=$_GET['q'];
    $libros = $libroDAO->getLibros($_GET['q'],10);
} else {
    $libros = $libroDAO->getLibros("",10);
    $buscamos="";
}
?>

<div class="clearfix wrapper-title">
    <h1 class="pull-left"><span class="glyphicon glyphicon-book"></span>Libros <span class="badge"><?php echo count($libros); ?></span></h1>
    <a href="libros_ficha.php" class="add-item pull-right" title="Nuevo libro"><span class="fa fa-plus-square"></span> Nuevo Libro</a>
</div>

<nav>
    <ol class="breadcrumb">
        <li><a href="home.php" title="Ir a Inicio">Home</a></li>
        <li class="active">Libros</li>
    </ol>
</nav>
<div class="row">
    <!-- formulario de busqueda -->
    <form method="get" action="libros.php" role="form" class="form-inline">
        <div class="input-group col-md-5">
            <input name="q" type="text" placeholder="Título del libro o nombre del autor..." id="libro" class="form-control" value="<?php echo $buscamos; ?>">
            <span class="input-group-btn">
                <button type="button" class="btn btn-success"><i class="fa fa-search"></i> Buscar</button>
            </span>
        </div>
    </form>  
</div>
<?php
if (isset($_GET['q']) === true && $_GET['q']!="") {
    
    echo "<p><span class='glyphicon glyphicon-remove-circle' id='searchclearbook'></span>" . count($libros) . " libro/s con <strong>" . $_GET['q'] . "</strong></p>";
}

if (isset($_SESSION['delLibro']) === true) {
    ?>
    <div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <i class="fa fa-thumbs-o-up"></i>
        Libro eliminado correctamente.
    </div>
    <?php
    //Borramos la variable para que en caso de recargar la página no aparezca
    unset($_SESSION['delLibro']);
}

if (isset($_SESSION['insLibro']) === true) {
    ?>
    <div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <i class="fa fa-thumbs-o-up"></i>
        Libro insertado correctamente.
    </div>
    <?php
    //Borramos la variable para que en caso de recargar la página no aparezca
    unset($_SESSION['insLibro']);
}
if (isset($_SESSION['NotDelLibro']) === true) {
    ?>
    <div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <i class="fa fa-exclamation-triangle"></i>
        No se puedo eliminar el libro porque hay pedidos realizados.
    </div>
    <?php
    //Borramos la variable para que en caso de recargar la página no aparezca
    unset($_SESSION['NotDelLibro']);
}
?>

<!-- Listado de libros -->        
<table id="listado-libros" class="table table-hover table-condensed">
    <thead>
        <tr>
            <th></th>
            <th>libro</th>
            <th>precio</th>
            <th>comentarios</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
<?php
//echo "libros total: ".count($libros);
//                  print_r("<pre>");
//                  print_r($libros);
//                  print_r("</pre>");

foreach ($libros as $libro) {
    $misautores = "";
    foreach ($libro['autores'] as $autor) {
        $misautores.=$autor['nombre'] . ', ';
    }

    $misautores = rtrim($misautores, ', ');
    $comentariosValidos = $libroDAO->getComentariosValidadosLibro($libro['id']);
    $comentariosTotales = $libroDAO->getComentariosTotalesLibro($libro['id']);
    ?>
            <tr>
                <td>
                    <img style="width: 70px;" src="<?php echo '../' . $libro['portada']; ?>" alt="<?php echo $libro['titulo']; ?>" title="<?php echo $libro['titulo']; ?>"/>
                </td>
                <td>
                    <h2><a href="libros_ficha.php?id=<?php echo $libro['id']; ?>" title="<?php echo $libro['titulo']; ?>"><?php echo $libro['titulo']; ?></a></h2>
                    <h3><?php echo $misautores; ?></h3>
                    <h4 class="editorial"><?php echo $libro['editorial']; ?></h4>
                    <h5>isbn: <?php echo $libro['isbn']; ?> / <?php echo $libro['n_pags']; ?> páginas</h5>

                </td>
                <td><?php echo $libro['precio']; ?> €</td>
                <td>
                    <span class="comments"><span class="fa fa-comment"></span> <?php echo $comentariosValidos; ?> / <?php echo $comentariosTotales; ?></span>
                    <span class="puntuacion">
                        <?php
                        for($i=1;$i<=5;$i++){
                            if($i<=$libro['voto'])
                            {
                                echo '<span class="fa fa-star "></span>';
                            }
                            else
                            {
                                echo '<span class="fa fa-star-o"></span>';
                            }
                        }
                        ?>
                    </span>
                </td>
                <td>
                    <a href="libros_ficha.php?id=<?php echo $libro['id']; ?>" class="tooltipt transition editar-libro" title="Editar"><span class="glyphicon glyphicon-edit btn-lg"></span></a>
                    <a href="" class="tooltipt transition eliminar-libro" data-toggle="modal" data-target="#myModal" title="Eliminar"><span class="glyphicon glyphicon-remove btn-lg"></span></a>
                </td>
            </tr>
    <?php
}
?>

    </tbody>
</table>

<div class="modal fade modalDelBook" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Eliminar libro</h4>
            </div>
            <div class="modal-body">
                ¿Estás seguro que deseas eliminar el libro xxx de xxxx?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <a href="" class="btn btn-danger elimina-este">Eliminar</a>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<?php include_once '_pager.php'; ?>       
<?php include_once '_footer.php'; ?>