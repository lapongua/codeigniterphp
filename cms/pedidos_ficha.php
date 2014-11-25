<?php
session_start();

//Comprobamos si la sesión está activa, sino volvemos a la página index.php
if (!isset($_SESSION['admin'])) {
    header('Location: index.php');
}
include_once '_header.php';
include_once '_navbar.php';

$isok = false; //la inicializamos a true si el id es correcto o estamos dando de alta un usuario
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    //Recogemos la conexión a la base de datos

    $conexion = DBManager::getInstance()->getConnection();
    $pedidoDAO = new PedidoDAO($conexion);
    $usuarioDAO = new UsuariosDAO($conexion);
    $autoresDAO = new AutoresDAO($conexion);
    $libroDAO = new LibroDAO($conexion);
    $pedidos = $pedidoDAO->read($_GET['id']);
   // $pedido = $pedidoDAO->getPedidos($_GET['id'],1);


    if ($pedidos == NULL) {
        echo 'El pedido que está editando no existe';
    } else {
        echo 'ediando<br>';
        $isok = true;
    }
} else {
    echo 'El pedido que está editando no existe<br>';
}

if ($isok) {
    ?>

    <div class="clearfix wrapper-title">
        <h1 class="pull-left"><span class="glyphicon glyphicon-shopping-cart"></span>Pedido #<span class="autor"><?php echo $pedidos['id']; ?></span></h1>
        <a href="javascript:history.back()" class="back pull-right" title="Atrás"><span class="fa fa-arrow-left"></span> Volver</a>
    </div>

    <nav>
        <ol class="breadcrumb">
            <li><a href="home.php" title="Ir a Inicio">Home</a></li>
            <li><a href="pedidos.php" title="Ir a Libros">Pedidos</a></li>
            <li class="active"><?php echo "pedido #" . $pedidos['id']; ?></li>
        </ol>
    </nav>




    <?php
}//fin de editar o dar de alta
else {
    ?>
    <div class="clearfix wrapper-title">
        <h1 class="pull-left">La ficha de pedido a la que quiere acceder no Existe.</h1>
        <a href="javascript:history.back()" class="back pull-right" title="Atrás"><span class="fa fa-arrow-left"></span> Volver</a>
    </div>
    <?php
}


if ($pedidos['pagado'] == 1) {
    $pagado = "PAGADO";
    $classe = "pagado";
} else {
    $pagado = "NO PAGADO";
    $classe = "nopagado";
}

$usuario = $usuarioDAO->read($pedidos['fk_usuarios']);
?>

<!-- Información del pedido -->        
<table id="pedido-<?php echo $pedidos['id']; ?>" class="table table-hover table-condensed">
    <thead>
        <tr>
            <th>fecha</th>
            <th>id</th>
            <th>usuario</th>
            <th>dirección</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><?php echo date("d/m/Y", strtotime($pedidos['fecha'])); ?></td>
            <td>#<?php echo $pedidos['id']; ?></td>
            <td>
<?php echo $usuario['nombre']; ?><br/>
<?php echo $usuario['email']; ?>
            </td>
            <td>
<?php echo $usuario['direccion']; ?><br/>
<?php echo $usuario['cp'] . " - " . $usuario['ciudad']['nombre'] . " (" . $usuario['pais']['nombre'] . ")"; ?>
            </td>
            <td>
                <?php
                echo number_format($pedidos['total'], 2) . " €";

                echo "<br/><span class='" . $classe . "'>" . $pagado . "</span>";
                ?>           
            </td>
        </tr>
    </tbody>
</table>


<!-- Información detallada del pedido -->        
<table id="contenido-pedido-<?php echo $pedidos['id']; ?>" class="table table-hover table-condensed">
    <thead>
        <tr>
            <th></th>
            <th>libro</th>
            <th>cantidad</th>
            <th>precio unitario</th>
            <th>total</th>
        </tr>
    </thead>
    <tbody>
<?php
$infoPedido = $pedidoDAO->getInfoPedido($_GET['id']);

// print_r($infoPedido);

foreach ($infoPedido as $info) {

    $fotos = $libroDAO->getFotosLibro($info['fk_libros']);
    $libro = $libroDAO->read($info['fk_libros']);
    $autores = $autoresDAO->getAutoresLibro($info['fk_libros']);

    $misautores = "";
    foreach ($autores as $autor) {
        $misautores.=$autor['nombre'] . ', ';
    }

    $misautores = rtrim($misautores, ', ');

    ?>
            <tr>
                <td><img height="65" src="../<?php echo $fotos['path_foto']; ?>" alt="<?php echo $libro['titulo'] ?>" /></td>
                <td>
                    <h2><?php echo $libro['titulo'] ?></h2>
                    <h3><?php echo $misautores; ?></h3>
                    <h4><?php echo $libro['editorial'] ?></h4>
                    <p><?php echo "isbn: " . $libro['isbn'] . " / " . $libro['n_pags'] . " páginas"; ?></p>
                </td>
                <td>
    <?php echo $info['cantidad']; ?><br/>
                </td>
                <td>
                    <?php echo number_format($info['precio']/$info['cantidad'], 2) . " €";
                    ?>           
                </td>
                <td><?php echo number_format($info['precio'], 2) . " €"; ?></td>
            </tr>
    <?php
}
?>
    </tbody>
    <tfoot>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td class="sumaPedido"><?php echo number_format($pedidos['total'], 2) . " €";?></td>
        </tr>
    </tfoot>
</table>


        <?php include_once '_footer.php'; ?>
