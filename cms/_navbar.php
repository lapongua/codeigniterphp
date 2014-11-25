<header id="navbar" class="navbar navbar-default navbar-fixed-top">
    <div class="wrapper-navbar clearfix">
        
           
        <div class="navbar-header navbar-left">
            <a class="navbar-brand" href="home.php" title="Ir a Dashboard"><span class="pull-left">Read.me</span><small> Content Management System</small></a>
            
        </div>
           <button data-target=".navbar-collapse" data-toggle="collapse" class="navbar-toggle navbar-right" type="button">
           <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <div class="navbar-header navbar-right navbar-collapse collapse">
            <p class="navbar-text">Hola, <a href="#" title="Ir a mi cuenta"><?php echo $_SESSION['user'];?></a><a title="Salir" class="logout transition" href="actions/logout.php"><span class="glyphicon glyphicon-log-out"></span></a></p>
        </div>
            
    </div>
    
    <nav id="subnavbar" class="navbar-collapse collapse">          
        <ul class="nav navbar-nav">  
            <?php
                $full_name = $_SERVER['PHP_SELF'];
                $name_array = explode('/',$full_name);
                $count = count($name_array);
                $page_name = $name_array[$count-1];
            

            ?>
            <li><a class="<?php echo ($page_name=='home.php')?'active':'';?>" href="home.php" title="Ir a Dashboard"><span class="fa fa-home"></span><span>Dashboard</span></a></li>  
            <li><a class="<?php echo ($page_name=='libros.php')?'active':'';?>" href="libros.php" title="Ir a Libros"><span class="glyphicon glyphicon-book"></span><span>Libros</span></a></li>  
            <li class="divider"><a class="<?php echo ($page_name=='autores.php')?'active':'';?>" href="autores.php" title="Ir a Autores"><span class="fa fa-user"></span><span>Autores</span></a></li>  
            <li><a class="<?php echo ($page_name=='pedidos.php')?'active':'';?>" href="pedidos.php" title="Ir a pedidos"><span class="glyphicon glyphicon-shopping-cart"></span><span>Pedidos</span></a></li>
            <li><a class="<?php echo ($page_name=='comentarios.php')?'active':'';?>" href="comentarios.php" title="Ir a comentarios"><span class="fa fa-comment"></span><span>Comentarios</span></a></li>
            <li class="divider"><a class="<?php echo ($page_name=='usuarios.php')?'active':'';?>" href="usuarios.php" title="Ir a Usuarios"><span class="fa fa-group"></span><span>Usuarios</span></a></li>
            <li><a class="<?php echo ($page_name=='config.php')?'active':'';?>" href="config.php" title="Ir a configuración"><span class="glyphicon glyphicon-cog"></span><span>Config</span></a></li>
        </ul>
    </nav>
    
    
</header>
<section>