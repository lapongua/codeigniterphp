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
if (isset($_GET['q']) === true) {
    $buscamos=$_GET['q'];
 //   $usuarios = $usuarioDAO->getUsuarios($_GET['q']);
}
 else {
     $buscamos="";
   // $usuarios = $usuarioDAO->getUsuarios();
}

$total_usuarios=$usuarioDAO->totalUsuarios('comprador');


?>

<div class="clearfix wrapper-title">
    <h1 class="pull-left"><span class="glyphicon glyphicon-book"></span>Usuarios <span class="badge"><?php echo $total_usuarios['total']; ?></span></h1>
</div>

<nav>
    <ol class="breadcrumb">
        <li><a href="home.php" title="Ir a Inicio">Home</a></li>
        <li class="active">Usuarios</li>
    </ol>
</nav>
<div class="row">
    <!-- formulario de busqueda -->
    <form role="form" class="form-inline" method="get" action="usuarios.php">
        <div class="input-group col-md-5">
            <input type="text" name="q" placeholder="Nombre de usuario o email" id="search-usuario" class="form-control" value="<?php echo $buscamos; ?>">
            <span class="input-group-btn">
                <button type="button" class="btn btn-success"><i class="fa fa-search"></i> Buscar</button>
            </span>
        </div>
    </form>


</div>

<?php
if (isset($_GET['q']) === true && $_GET['q']!="") {
    echo "<p class='mec'></p>";
    echo "<p><span class='glyphicon glyphicon-remove-circle' id='searchclearusuari'></span><span class='usersencontrados'></span> usuario/s con <strong>" . $_GET['q'] . "</strong></p>";
}
?>


<!-- Listado de usuarios -->        
<table id="listado-usuarios" class="table table-hover table-condensed">
    <thead>
        <tr>
            <th>id</th>
            <th>usuario</th>
            <th>dirección</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="3" style="padding: 0;border-top: none;">
                <div id="loading" style="display: none;"><img alt="loading" src="../assets/styles/images/loading.gif" /></div>
            </td>
        </tr>
        <?php

      /*  foreach ($usuarios as $usuario) {
            
            $ciudad = $usuarioDAO->getCiudad($usuario['fk_ciudades']);
            $pais=$usuarioDAO->getPais($ciudad['fk_paises']);
            //print_r($pais['nombre']);
            
            ?>
            <tr>
                <td>
                    <?php echo $usuario['id']; ?>
                </td>
                <td>
                    <h2><?php echo $usuario['nombre']; ?></h2>
                    <h3><a href="mailto:<?php echo $usuario['email']; ?>" title=""><?php echo $usuario['email']; ?></a></h3>
                </td>
                <td><?php echo $usuario['direccion']; ?><br/><?php echo $usuario['cp']; ?> - <?php echo $ciudad['nombre']; ?> (<?php echo $pais['nombre']; ?>)</td>
                
            </tr>
    <?php
}*/
?>

    </tbody>
</table>



<?php include_once '_pager.php'; ?>       
<?php include_once '_footer.php'; ?>