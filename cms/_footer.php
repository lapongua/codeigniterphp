</section>
</div>
<footer class="clearfix">                    
    <div class="pull-left">&copy; 2013 Universidad de Alicante</div><div class="pull-right">By Lara Pont</div>
</footer>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script type="text/javascript" src="../assets/scripts/lib/jquery/jquery.min.js"></script>
<script type="text/javascript" src="../assets/scripts/lib/jquery/jquery-ui-1.10.4.custom.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="../assets/bootstrap/js/bootstrap.min.js"></script>
<?php
$full_name = $_SERVER['PHP_SELF'];
$name_array = explode('/', $full_name);
$count = count($name_array);
$page_name = $name_array[$count - 1];
?>
<script src="../assets/scripts/general.js"></script>
<?php
if (isset($scripts)) {
    echo $scripts;
}
//echo "PageName: ".$page_name;
if(isset($page_name) && $page_name=="home.php")
{
    ?>
    <script src="../assets/scripts/cmsHome.js"></script>
    <script src="http://code.highcharts.com/highcharts.js"></script>
    <?php
}

if(isset($page_name) && $page_name=="libros_ficha.php")
{
    ?>
    <script src="../assets/scripts/cmsLibrosFicha.js"></script>
    <?php
}

?>
<script src="../assets/scripts/lib/tablesorter/jquery.tablesorter.min.js"></script> 
</body>
</html>
