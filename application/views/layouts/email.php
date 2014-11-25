<html>
    <head>
        <title>Pedido realizado en Read.me</title>
        <style type="text/css">
            f-right{float: right;}
        </style>
    </head>
    <body>
        <p>Hola {nombre},</p>
        <p>Gracias por realizar un pedido en <strong>Read.me, tu librería online</strong>. A continuación te detallamos los datos de envío:</p>
        
        <h2>DATOS DEL CLIENTE</h2>
        <ul>
            <li>Nombre: {nombre}</li>
            <li>Dirección: {direccion}</li>
            <li>Código postal: {cp}</li>
            <li>Población: {ciudad}</li>
        </ul>
        
        <h2>DATOS DEL PEDIDO NÚMERO</h2>   
        <table width="100%">
            <tr>
                <th width="50%" align="left">Título</th>
                <th width="15%">Precio</th>
                <th width="15%">Cantidad</th>
                <th width="15%" align="right">Base Imponible</th>
            </tr>
            {libro}
            <tr>
                <td align="left">{titulo}</td>
                <td align="center">{precio}</td>
                <td align="center">{cantidad}</td>
                <td align="right">{total_fila}</td>
            </tr>
            {/libro}
        </table>
        <p class="f-right">Total del pedido: <strong>{total_precio}</strong>€</p>
       
    </body>
</html>


