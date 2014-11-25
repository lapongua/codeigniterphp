<?php 
session_start();

//si tenemos el rol de adminsitrador vamos directamente a la home.php
if (isset($_SESSION['admin'])) {
    header('Location: home.php');
}
include_once '_header.php';
?>


    <div id="login" class="formreg col-xs-12 col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4">
        <h1><a href="../index.php" title="Volver a la página principal">Read.me</a> <small>content management system</small></h1>
       <?php
       if (isset($_SESSION['error']) === true) {
       ?>
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <i class="fa fa-exclamation-triangle"></i>
                <strong>Error:</strong> El usuario/contraseña son incorrectos.
            </div>
        <?php
        //Borramos la variable error para que en caso de recargar la página no aparezca
        unset($_SESSION['error']);
       }
         ?>
        
        <?php
       if (isset($_SESSION['success']) === true) {
       ?>
            <div class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <i class="fa fa-smile-o"></i>
                <strong>¡Hasta pronto!</strong> <?php echo $_SESSION['success']; ?>
            </div>
        <?php
        //Borramos la variable error para que en caso de recargar la página no aparezca
        unset($_SESSION['success']);
       }
     
         ?>
        
        
        <form id="loginform" class="wrapper-form-reg panel panel-default" role="form" method="post" action="actions/login.php">
            <div class="form-group">
                <label for="email">email</label>
                <input type="email" class="form-control" id="email" autofocus="" placeholder="Email" name="email">
            </div>
            <div class="form-group">
                <label for="pass">contraseña</label>
                <input type="password" class="form-control" id="pass" placeholder="Contraseña" name="pass">
            </div>
            <div class="relative clearfix">
                <a title="Recupera tu contraseña perdida" href="recordar-contrasenya.php" class="bottom pull-left"><i class="fa fa-exclamation-circle"></i> ¿Has olvidado tu contraseña?</a>
                <button type="submit" class="btn btn-primary pull-right" name="submit"><i class="fa fa-sign-in"></i> Entrar</button>
            </div>
        </form> 
        <p class="text-center"><small>© Universidad de Alicante 2013</small></p>
    </div>

<?php include_once '_footer.php';?>