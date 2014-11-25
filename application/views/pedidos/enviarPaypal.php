<!-- Vamos a redirigir la pagina a la pasarela de pago de paypal con los valores que nos llegan del formulario -->
<body onLoad="document.formulario.submit()" >
<div>Connect to Paypal...</div>
<form  name="formulario" id="formulario" action="https://sandbox.paypal.com/cgi-bin/webscr" method="post">
<!-- Identificar tu tienda (recuerda los datos puestos en la cuente creada como seller en developer.paypal.com) -->
<input type="hidden" name="bn" value="<?php echo PAYPAL_STORE; ?>"><!-- el nombre da igual -->
<input type="hidden" name="business" value="<?php echo PAYPAL_VENDEDOR_EMAIL;?>"><!-- introducir el email de la cuenta de prueba -->
<!-- Especificar el tipo de boton/formulario en paypal -->
<input type="hidden" name="cmd" value="_xclick"> 
<input type="hidden" name="no_note" value="1">
<!-- Ponemos que no pregunten sobre el envio -nuestra web se hace cargo- -->
<input type="hidden" name="no_shipping" value="1">
<!-- El unico item es la compra realizada -nosotros con el id de la compra ya sacamos los producto- -->
<input type="hidden" name="item_name" value="Compra desde Tienda de Prueba">
<!-- Especificamos el id del pedido -->
<input type="hidden" name="item_number" value="<?php echo $paypal_order; ?>">
<!-- Ponemos el total de la compra -->
<input type="hidden" name="amount" value="<?php echo $total_precio;?>">
<!-- Especificamos pago en euro -->
<input type="hidden" name="currency_code" value="EUR">
<input type="hidden" name="rm" value="2">
<!-- Como dato extra, enviamos el codigo del cliente -->
<input type="hidden" name="custom" value="<?php echo $cliente; ?>">
<!-- Pagina de vuelta de la compra efectuada -->
<input type="hidden" name="return" value="<?php echo base_url(PAYPAL_RETURN);?>">
<!-- Pagina de vuelta de la compra con error-->
<input type="hidden" name="cancel_return" value="<?php echo base_url(PAYPAL_CANCEL_RETURN);?>">
</form>
</body>
