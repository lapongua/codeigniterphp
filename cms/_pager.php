<?php
switch ($page_name) {
    case 'autores.php':
        $scripts = "<script src='../assets/scripts/cmsautores.js'></script>";
        $variable=$autores;
        $totalitems="";
        $son="autores";
        break;
    case 'libros.php':
        $scripts="<script src='../assets/scripts/cmslibros.js'></script>";
        $variable=$libros;
        $totalitems="";
        $son="libros";
        break;
    case 'usuarios.php':
        $scripts="<script src='../assets/scripts/cmsusuarios.js'></script>";
        $variable=$usuarios;
        $totalitems=$total_usuarios;
        $son="usuarios";
        break;
    case 'comentarios.php':
        $scripts="<script src='../assets/scripts/cmscomentarios.js'></script>";
        $variable=$comentarios;
        $son="comentarios";
        $totalitems="";
        break;
    case 'pedidos.php':
        $scripts="<script src='../assets/scripts/cmsPedidos.js'></script><script src='http://code.highcharts.com/highcharts.js'></script>";
        //$variable=$comentarios;
        //$son="pedidos";
        //$totalitems="";
        break;
    default:
        $scripts = "";
        $son="";
        $variable=0;
        $totalitems="";
        break;
}
?>

<div class="wrapper-pager clearfix">
    <?php
    $maxpages="";
    if(isset($total_usuarios))
    {
        $maxpages=round(($total_usuarios['total']/5)+1,0);
    }
    ?>
    <p class="pull-left">Página <span class="currentPage">1</span> de <span class="totalpaginas"><?php echo $maxpages;?></span></p>
   
    <ul class="pagination pull-right">
      <li class="previous"><a href="#" title="Ir a página anterior" p="0">«</a></li>
      <li class="first active"><a href="#" title="Ir a página 1" p="1">1</a></li>
      <?php
      for ($i=2; $i<$maxpages+1;$i++)
      {
          
      ?>
      <li class="<?php echo ($i==$maxpages)?'last':''; ?>"><a href="#" title="Ir a página <?php echo $i;?>" p="<?php echo $i;?>"><?php echo $i;?></a></li>
      <?php
          
      }
      ?>
      <li class="next"><a href="#" title="Ir a página siguiente" p="">»</a></li>
    </ul>
</div>
