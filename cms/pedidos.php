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
$pedidoDAO = new PedidoDAO($conexion);
$usuarioDAO = new UsuariosDAO($conexion);
if (isset($_GET['q']) === true) {
    $buscamos = $_GET['q'];
    $pedidos = $pedidoDAO->getPedidos($_GET['q'], 100);
} else {
    $pedidos = $pedidoDAO->getPedidos("", 100);
    $buscamos = "";
}

$totalPedidos = $pedidoDAO->getTotalPedidos();
?>

<div class="clearfix wrapper-title">
    <h1 class="pull-left"><span class="glyphicon glyphicon-shopping-cart"></span>Pedidos <span class="badge"><?php echo $totalPedidos; ?></span></h1>
</div>

<nav>
    <ol class="breadcrumb">
        <li><a href="home.php" title="Ir a Inicio">Home</a></li>
        <li class="active">Pedidos</li>
    </ol>
</nav>
<div class="row">
    <!-- formulario de busqueda -->
    <div id="pedidos-col-left" class="col-md-6">
        <div class="row">
            <form method="get" action="pedidos.php" role="form" class="form-inline">
                <div class="input-group col-md-12">
                    <input name="q" type="text" placeholder="Id de pedido, usuario o email..." id="pedido" class="form-control" value="<?php echo $buscamos; ?>">
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-success"><i class="fa fa-search"></i> Buscar</button>
                    </span>
                </div>
            </form>
        </div>
        <div class="row">
            <ul id="resumen-pedidos">
                <li id="TotalPagado"><strong>Total pagado:</strong> </li>
                <li id="PedidosPagados"><strong>Nº pedidos pagados:</strong> </li>
            </ul>
        </div>

    </div>

    <div id="chart-pedidos" class="col-md-6" style="height: 300px;"></div>
</div>
<?php
if (isset($_GET['q']) === true && $_GET['q'] != "") {

    echo "<p><span class='glyphicon glyphicon-remove-circle' id='searchclearpedido'></span>" . count($pedidos) . " pedido/s con <strong>" . $_GET['q'] . "</strong></p>";
}
?>

<!-- Listado de pedidos -->        
<table id="listado-pedidos" class="table table-hover table-condensed">
    <thead>
        <tr>
            <th>fecha</th>
            <th>pedido</th>
            <th>usuario</th>
            <th>estado</th>
            <th>importe</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($pedidos as $pedido) {
            $ciudad = $usuarioDAO->getCiudad($pedido['fk_ciudades']);
            $pais = $usuarioDAO->getPais($ciudad['fk_paises']);

            if ($pedido['pagado'] == 1) {
                $pagado = "PAGADO";
                $classe = "pagado";
            } else {
                $pagado = "NO PAGADO";
                $classe = "nopagado";
            }
            ?>
            <tr class='clickableRow' data-url="pedidos_ficha.php?id=<?php echo $pedido['id']; ?>">
                <td>
            <?php echo date("d/m/Y", strtotime($pedido['fecha'])); ?>
                </td>
                <td>
                    #<?php echo $pedido['id']; ?><br>
                    <?php
                    foreach ($pedido['portada'] as $key => $value) {
                        echo '<a href="libros_ficha.php?id=' . $pedido['mislibros'][$key] . '"><img height="45" src="../' . $value . '" alt="' . $pedido['titulo'][$key] . '" title="' . $pedido['titulo'][$key] . '" data-toggle="tooltipr" /></a>';
                    }
                    ?>
                </td>
                <td>
                    <h2><?php echo $pedido['nombre']; ?></h2>
                    <?php echo $pedido['email']; ?>
                    <p><?php echo $pedido['direccion']; ?> - <?php echo $ciudad['nombre']; ?> (<?php echo $pais['nombre']; ?>)</p>
                </td>
                <td class="estadopedido">
                    <span class="<?php echo $classe; ?>"><?php echo $pagado; ?></span>
                </td>
                <td>
    <?php echo number_format($pedido['total'], 2) . " €"; ?>
                </td>
            </tr>
    <?php
}
?>

    </tbody>
</table>


        <?php include_once '_pager.php'; ?>       
<?php include_once '_footer.php'; ?>