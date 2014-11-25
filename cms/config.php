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
$usuarioDAO = new UsuariosDAO($conexion);
?>

<div class="clearfix wrapper-title">
    <h1 class="pull-left"><span class="glyphicon glyphicon-cog"></span>Config</h1>
</div>

<nav>
    <ol class="breadcrumb">
        <li><a href="home.php" title="Ir a Inicio">Home</a></li>
        <li class="active">Config</li>
    </ol>
</nav>
<?php
if (isset($_SESSION['updateAdmin']) === true) {
    ?>
    <div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <i class="fa fa-thumbs-o-up"></i>
        <?php echo $_SESSION['updateAdmin']; ?>
    </div>
    <?php
    //Borramos la variable para que en caso de recargar la página no aparezca
    unset($_SESSION['updateAdmin']);
}
?>

<div class="row">

    <form class="col-md-4" role="form" method="post" action="actions/config_guardar.php?id=<?php echo $_SESSION['id'];?>">
        <div class="form-group">
            <label for="exampleInputEmail1">email</label>
            <input type="email" class="form-control" name="updateEmail" placeholder="Email" value="<?php echo $_SESSION['admin']; ?>">
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">contraseña</label>
            <input type="password" class="form-control" name="updatePass" placeholder="Contraseña">
        </div>
        <button type="submit" class="btn btn-primary pull-right">Guardar</button>
    </form>



</div>

<?php include_once '_footer.php'; ?>