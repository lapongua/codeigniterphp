<?php
function __autoload($name) {
    include_once 'core/class.' . $name . '.php';
}
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <title>Backend Read.me</title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
         
        <!-- Bootstrap -->
        <link href="../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
        <!--<link href="../assets/styles/css/backend.less" rel="stylesheet/less" type="text/css" />-->
		
		
		<!--[if !IE]><!-->
		<link href="../assets/styles/css/backend.less" rel="stylesheet/less" type="text/css" />
		<!--<![endif]-->
		
		
		
		<!-- IE 9 or above -->
		<!--[if gte IE 9]>
		<link href="../assets/styles/css/backend.less" rel="stylesheet/less" type="text/css" />
	   <![endif]-->
		
		<!-- IE 8 or below -->   
		<!--[if lt IE 9]>
		<link href="../assets/styles/css/backend.css" rel="stylesheet" type="text/css" />
		<![endif]-->
		
		
         
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet"> 
        <link href="../assets/styles/css/jquery-ui-1.10.4.custom.min.css" rel="stylesheet" type="text/css">
        <script type="text/javascript" src="../assets/scripts/lib/less/less.min.js"></script>
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
          <![endif]-->  
        
        <script> var base_url = "<?php echo "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER["REQUEST_URI"]); ?>"</script>
    </head>
    <body> 
        <div class="container">
            