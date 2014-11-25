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
$comentarioDAO = new ComentariosDAO($conexion);
$librosDAO = new LibroDAO($conexion);
$pedidosDAO = new PedidoDAO($conexion);

$total_comentarios = $comentarioDAO->totalComentarios();
$total_usuarios = $usuarioDAO->totalUsuarios('comprador');
?>

<div class="col-md-4">
    <div id="resumen" class="panel panel-default">
        <div class="panel-heading clearfix">
            <span class="fa fa-dashboard pull-left"></span>
            <h2 class="panel-title pull-left">Resumen</h2>
        </div>
        <div class="panel-body clearfix">
            <div id="container-chart" style="width:100%; height:300px;"></div>
            <?php
            $totalPagado = $pedidosDAO->totalPagados();
            $pedidosPagados = $pedidosDAO->pedidosPagados();
            ?>
            <div class="details pull-left">
                <h1 class="number"><?php echo number_format($totalPagado, 2) . " €"; ?></h1>
                <p class="avg">Total pagado</p>
                <ul class="list-inline">
                    <li><h4 class="num"><a href="pedidos.php" data-toggle="tooltipb" title="Pedidos Pagados"><span class="glyphicon glyphicon-shopping-cart"></span><?php echo $pedidosPagados; ?> <small>pedidos</small></a></h4></li>
                    <li><h4 class="newcustomer"><a href="usuarios.php" data-toggle="tooltipb" title="Total COMPRADOR"><span class="fa fa-group"></span> <?php echo $total_usuarios['total']; ?></a></h4></li>
                    <li><h4 class="newcomment"><a href="comentarios.php" data-toggle="tooltipb" title="Total comentarios"><span class="fa fa-comment"></span> <?php echo $total_comentarios['total']; ?></a></h4></li>
                </ul>
            </div>

        </div>
    </div>
    <?php
    $libros = $librosDAO->getLibros("",6);
    $totalLibros=$librosDAO->getTotalLibros();
    ?>
    <div id="r-last-books" class="panel panel-default">
        <div class="panel-heading clearfix">
            <span class="glyphicon glyphicon-book pull-left"></span>
            <h2 class="panel-title pull-left">Últimos libros</h2>
            <span class="badge pull-right"><?php echo $totalLibros; ?></span>
        </div>
        <div class="panel-body">
            <ul class="list-inline">

                <?php
                foreach ($libros as $libro) {
                    $misautores = "";
                    foreach ($libro['autores'] as $autor) {
                        $misautores.=$autor['nombre'] . ', ';
                    }

                    $misautores = rtrim($misautores, ', ');
                    ?>
                    <li>
                        <a href="libros_ficha.php?id=<?php echo $libro['id']; ?>" title="<?php echo $libro['titulo']; ?>"><img width="130" src="<?php echo '../' . $libro['portada']; ?>" alt="<?php echo $libro['titulo']; ?>" /></a>
                        <h2><?php echo $libro['titulo']; ?></h2>
                        <h3><?php echo $misautores; ?> <span><?php echo $libro['editorial']; ?></span></h3>
                    </li>
                    <?php
                }
                ?>
            </ul>
        </div>
        <div class="panel-footer">
            <?php if($totalLibros>6)
            {
                ?>
            
            <a class="" href="libros.php" title="Ver más libros">Ver todos los libros</a>
            <?php
            }
            ?>
        </div>
    </div>

</div>
<div class="col-md-8">
    <?php
    $totalPedidos = $pedidosDAO->getTotalPedidos();

    $pedidos = $pedidosDAO->getPedidos("", 4);
    ?>
    <div id="r-last-orders" class="panel panel-default">
        <div class="panel-heading clearfix">
            <span class="glyphicon glyphicon-shopping-cart pull-left"></span>
            <h2 class="panel-title pull-left">Últimos pedidos</h2>
            <span class="badge pull-right"><?php echo $totalPedidos; ?></span>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-hover table-condensed">
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

                            if ($pedido['pagado'] == 1) {
                                $pagado = "PAGADO";
                                $classe = "pagado";
                            } else {
                                $pagado = "NO PAGADO";
                                $classe = "nopagado";
                            }
                            ?>
                          <tr class="clickableRow" data-url="pedidos_ficha.php?id=<?php echo $pedido['id']; ?>">
                                <td><?php echo date("d/m/Y", strtotime($pedido['fecha'])); ?></td>
                                <td class="idpedido"><span>#<?php echo $pedido['id']; ?></span>
                                    <?php
                                    foreach ($pedido['portada'] as $key => $value) {
                                        echo '<a href="libros_ficha.php?id=' . $pedido['mislibros'][$key] . '" data-toggle="tooltipb" title="' . $pedido['titulo'][$key] . '"><img height="45" src="../' . $value . '" alt="' . $pedido['titulo'][$key] . '" title="' . $pedido['titulo'][$key] . '" /></a>';
                                    }
                                    ?>


    <!--                                    <a href="#" data-toggle="tooltipb" title=""><img src='../uploads/images/libros/thumb-small/thumb-oliver-twist.jpg' alt='Oliver Twist' /></a>
                                        <a href="#" data-toggle="tooltipb" title="Anna Karennina"><img src='../uploads/images/libros/thumb-small/thumb-anna-karennina.jpg' alt='Anna Karennina' /></a>-->
                                </td>
                                <td>
                                    <h2><?php echo $pedido['nombre']; ?></h2>
                                    <a href="mailto:<?php echo $pedido['email']; ?>"><?php echo $pedido['email']; ?></a>
                                </td>
                                <td class="estadopedido"><span class="<?php echo $classe; ?>"><?php echo $pagado; ?></span></td>
                                <td><?php echo number_format($pedido['total'], 2) . " €"; ?></td>

                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="panel-footer">
            <?php
            if ($totalPedidos > 4) {
                ?>
                <a class="" href="pedidos.php" title="Ver más pedidos">Ver todos los pedidos</a>
                <?php
            }
            ?>
        </div>
    </div>

    <?php
    $usuarios = $usuarioDAO->getUsuarios("", 5, 1);
    ?>
    <div id="r-last-users" class="panel panel-default">
        <div class="panel-heading clearfix">
            <span class="fa fa-group pull-left"></span>
            <h2 class="panel-title pull-left">Últimos usuarios</h2>
            <span class="badge pull-right"><?php echo $total_usuarios['total']; ?></span>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-hover table-condensed">
                    <thead>
                        <tr>
                            <th>id</th>
                            <th>usuario</th>
                        </tr>
                    </thead>
                    <tbody>
<?php
foreach ($usuarios as $usuario) {
    ?>
                            <tr>
                                <td><?php echo $usuario['id']; ?></td>
                                <td>
                                    <h2><?php echo $usuario['nombre']; ?></h2>
                                    <p><a href="mailto:<?php echo $usuario['email']; ?>" title=""><?php echo $usuario['email']; ?></a></p>
                                </td>
                            </tr>
    <?php
}
?>

                    </tbody>
                </table>
            </div>
        </div>
        <div class="panel-footer">
            <a class="" href="autores.php" title="Ver más usuarios">Ver todos los usuarios</a>
        </div>
    </div>

<?php
$comentarios = $comentarioDAO->getComentarios("", "-", 3);
?>
    <div id="r-last-comments" class="panel panel-default">
        <div class="panel-heading clearfix">
            <span class="fa fa-comment pull-left"></span>
            <h2 class="panel-title pull-left">Últimos comentarios</h2>
            <span class="badge pull-right"><?php echo $total_comentarios['total']; ?></span>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-hover table-condensed">
                    <thead>
                        <tr>
                            <th class="displaynone">id</th>
                            <th>comentarios</th>
                            <th>activo</th>
                        </tr>
                    </thead>
                    <tbody>
<?php
foreach ($comentarios as $comentario) {
    $libro = $librosDAO->read($comentario['fk_libros']);
    ?>
                            <tr>
                                <td class="idcomentario displaynone"><?php echo $comentario['idc'] ?></td>
                                <td>
                                    <h2><?php echo $comentario['autor'] ?> <span>comenta <a href="libros_ficha.php?id=<?php echo $libro['id']; ?>"><?php echo $libro['titulo']; ?></a></span></h2>
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



                            </tr>
    <?php
}
?>

                    </tbody>
                </table>
            </div>
        </div>
        <div class="panel-footer">
            <a class="" href="comentarios.php" title="Ver más comentarios">Ver todos los comentarios</a>
        </div>
    </div>

</div>
<?php include_once '_footer.php'; ?>