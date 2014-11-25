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
$comentariosDAO = new ComentariosDAO($conexion);
$librosDAO = new LibroDAO($conexion);
$comentarios = $comentariosDAO->getComentarios();
$total_comentarios=$comentariosDAO->totalComentarios();
?>

<div class="clearfix wrapper-title">
    <h1 class="pull-left"><span class="fa fa-user"></span>Comentarios <span class="badge"><?php echo $total_comentarios['total']; ?></span></h1>
</div>

<nav>
    <ol class="breadcrumb">
        <li><a href="home.php" title="Ir a Inicio">Home</a></li>
        <li class="active">Comentarios</li>
    </ol>
</nav>
<div class="row">

    <form id="form-search-comentarios" role="form" class="form-inline">
        <div class="col-md-5">
            <div class="form-group col-md-6">
                <label class="sr-only" for="comentarios-search">¿qué estás buscando?</label>
                <input type="text" placeholder="Texto..." id="comentarios-search" class="form-control" name="q">
                <span id="searchclear" class="glyphicon glyphicon-remove-circle"></span>
            </div>
            <div class="form-group col-md-4">
                <select id="esValido" class="form-control" name="validado">
                    <option value="-">Todos</option>
                    <option value="1">Validados</option>
                    <option value="0">No Validados</option>
                </select>
            </div>
            <div class="form-group col-md-2">
                    <button type="button" class="btn btn-success"><i class="fa fa-search"></i> Buscar</button>
            </div>

        </div>
    </form>


</div>
<?php
if (isset($_SESSION['delComentario']) === true) {
    ?>
    <div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <i class="fa fa-thumbs-o-up"></i>
        <?php
            echo $_SESSION['delComentario'];
        ?>
    </div>
    <?php
    //Borramos la variable para que en caso de recargar la página no aparezca
    unset($_SESSION['delComentario']);
}



?>

<!-- Listado de comentarios -->        
<table id="listado-comentarios" class="table table-hover table-condensed tablesorter">
    <thead>
        <tr>
            <th>id</th>
            <th>comentario</th>
            <th>validado</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($comentarios as $comentario) {
            $libro = $librosDAO->read($comentario['fk_libros']);
//            print_r("<pre>");
//            print_r($comentario);
//            print_r("</pre>");
            ?>
            <tr>
                <td class="idcomentario"><?php echo $comentario['idc'] ?></td>
                <td>
                    <h2><span class="comenta"><?php echo $comentario['autor'] ?></span> <span class="comenta-text">comenta <a href="libros_ficha.php?id=<?php echo $libro['id']; ?>"><?php echo $libro['titulo']; ?></a></span></h2>
                    <p><?php echo $comentario['descripcion'] ?></p>
                </td>
                <?php
                if ($comentario['validado'] == 0) {
                    $miclase = 'noValido';
                    $miicono = '<span class="fa fa-square-o btn-lg"></span>';
                } else {
                    $miclase = 'valido';
                    $miicono = '<span class="fa fa-check-square-o btn-lg"></span>';
                }
                ?>
                <td class="comentarioValidado <?php echo $miclase; ?>">
                    <?php echo $miicono; ?>
                </td>
                <td>
                    <a href="" class="tooltipt transition eliminar-comentario" data-toggle="modal" data-target="#deleteComment" title="Eliminar"><span class="glyphicon glyphicon-remove btn-lg"></span></a>
                </td>
            </tr>
            <?php
        }
        ?>

    </tbody>
</table>

<!-- popup eliminar autor -->
<div class="modal fade modalDelComentario" id="deleteComment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Eliminar comentario</h4>
            </div>
            <div class="modal-body">
                ¿Estás seguro que deseas eliminar el comentario xxx de xxxx?
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