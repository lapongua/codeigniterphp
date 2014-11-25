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
$autoresDAO = new AutoresDAO($conexion);
$autores = $autoresDAO->getAutores();
?>

<div class="clearfix wrapper-title">
    <h1 class="pull-left"><span class="fa fa-user"></span>Autores <span class="badge"><?php echo count($autores); ?></span></h1>
    <a href="" data-toggle="modal" data-target="#addAuthor" class="add-item pull-right" title="Nuevo autor"><span class="fa fa-plus-square"></span> Nuevo Autor</a>
</div>

<nav>
    <ol class="breadcrumb">
        <li><a href="home.php" title="Ir a Inicio">Home</a></li>
        <li class="active">Autores</li>
    </ol>
</nav>
<div class="row">

    <form id="form-search-autor" role="form" class="form-inline">
        <div class="input-group col-md-5">

            <input type="text" placeholder="Nombre del autor..." id="autor-search" class="form-control" name="term">
            <span id="searchclear" class="glyphicon glyphicon-remove-circle"></span>
            <span class="input-group-btn">
                <button type="button" class="btn btn-success"><i class="fa fa-search"></i> Buscar</button>
            </span>

        </div>
    </form>


</div>
<?php
if (isset($_SESSION['delAutor']) === true) {
    ?>
    <div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <i class="fa fa-thumbs-o-up"></i>
        Autor eliminado correctamente.
    </div>
    <?php
    //Borramos la variable para que en caso de recargar la página no aparezca
    unset($_SESSION['delAutor']);
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
if (isset($_SESSION['NotDelAutor']) === true) {
    ?>
    <div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <i class="fa fa-exclamation-triangle"></i>
        No se puedo eliminar el autor porque tiene pedidos asignados.
    </div>
    <?php
    //Borramos la variable para que en caso de recargar la página no aparezca
    unset($_SESSION['NotDelAutor']);
}
?>

<!-- Listado de autores -->        
<table id="listado-autores" class="table table-hover table-condensed tablesorter">
    <thead>
        <tr>
            <th>id</th>
            <th>nombre <span class="fa fa-sort"></span></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($autores as $autor) {
            ?>
            <tr>
                <td><?php echo $autor['id']; ?></td>
                <td id="edit-<?php echo $autor['id']; ?>" class="edit">
                    <span class="nomAutor"><?php echo $autor['nombre']; ?></span>

                </td>
                <td>
                    <a href="actions/autores_guardar.php?id=<?php echo $autor['id']; ?>" class="tooltipt transition editar-autor" title="Editar"><span class="glyphicon glyphicon-edit btn-lg"></span></a>
                    <a href="" class="tooltipt transition eliminar-autor" data-toggle="modal" data-target="#deleteAuthor" title="Eliminar"><span class="glyphicon glyphicon-remove btn-lg"></span></a>
                </td>
            </tr>
            <?php
        }
        ?>

    </tbody>
</table>

<!-- popup eliminar autor -->
<div class="modal fade modalDelAutor" id="deleteAuthor" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Eliminar autor</h4>
            </div>
            <div class="modal-body">
                ¿Estás seguro que deseas eliminar el autor xxx de xxxx?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <a href="" class="btn btn-danger elimina-este">Eliminar</a>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<!-- popup nuevo autor -->
<div class="modal fade modalAddAutor" id="addAuthor" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="addModalLabel">Nuevo autor</h4>
            </div>
            <div class="modal-body">
                
                <span id="error-autor"></span>
                <form class="formAddAutor"> 

                <label>nombre</label>
                <input type="text" name="nombreAutor" id="nombreAutor" class="form-control">
                <label>biografía</label>
                <textarea class="form-control" name="biografiaAutor" id="biografiaAutor" rows="3"></textarea>
                </form>  
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <a href="" class="btn btn-primary add-este-autor">Guardar</a>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php include_once '_pager.php'; ?>
<?php include_once '_footer.php'; ?>