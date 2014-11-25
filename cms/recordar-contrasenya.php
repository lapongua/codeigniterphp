<?php 
session_start();

include_once '_header.php';

?>
<!--<div class="container">-->

    <div class="formreg col-xs-12 col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4">
        <h1><a href="../index.php" title="volver a la página principal">Read.me</a> <small>content management system</small></h1>
        <?php
       if (isset($_GET['error']) == 1) {
       ?>
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <i class="fa fa-exclamation-triangle"></i>
                <strong>Error:</strong> No existe ningún administrador con ese email.
            </div>
        <?php
        //Borramos la variable error para que en caso de recargar la página no aparezca
        unset($_SESSION['error']);
       }
       
       if (isset($_SESSION['success']) === true) {
       ?>
            <div class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <i class="fa fa-envelope"></i>
                <?php echo $_SESSION['success']; ?>
            </div>
        <?php
        //Borramos la variable error para que en caso de recargar la página no aparezca
        unset($_SESSION['success']);
       }
       
       
       ?>
        <form id="remember-pass" class="wrapper-form-reg panel panel-default" role="form" action="actions/recordar.php" method="post">
            <h2><small>Recordar contraseña</small></h2>
            <p>Escribe tu correo electrónico y recibirás un email recordando tu contraseña.</p>
            <div class="form-group">
                <label for="email">email</label>
                <input type="email" class="form-control" id="email" name="email" autofocus="" placeholder="Email">
            </div>
            <div class="relative clearfix">
                <a title="Volver atrás" href="index.php" class="pull-left bottom"><i class="fa fa-arrow-left"></i> Volver Acceso</a>
                <button type="submit" name="submit" class="btn btn-primary pull-right"><i class="fa fa-envelope"></i> Recordar contraseña</button>
            </div>

        </form> 
        <p class="text-center"><small>© Universidad de Alicante 2013</small></p>

    </div>
<!--</div>-->




<?php include_once '_footer.php'; ?>
