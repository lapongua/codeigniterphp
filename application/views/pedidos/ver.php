<?php if (count($carro_session) > 0): ?>
<?php if ($this->session->flashdata('exito')): ?>
        <p class="successful"><?php echo $this->session->flashdata('exito'); ?></p>
        <?php //$this->session->unset_userdata('exito'); ?>
    <?php endif; ?>
    <h2>Resumen del pedido</h2>
    <?php
    echo form_open('pedidos/confirmar', 'id=""');
    ?>
    <table width="100%" cellspacing="0" border="0" id="resumen-pedido">
        <thead align="center" class="table-head">
            <tr>
                <th width="10%" align="left">Artículo</th>
                <th width="40%"></th>
                <th width="15%">Precio unitario</th>
                <th width="15%">Cantidad</th>
                <th width="15%" align="right">Base imponible</th>
            </tr>
        </thead>
        <tbody class="table-body">
            <?php
            foreach ($carro_session as $id => $cantidad):

                $libro = $librosCarro[$id];
                //print_r($libro);
                
                //print_r($libro->fotos[0]->path_foto);
                $ruta_foto=base_url().''.$libro->fotos[0]->path_foto;
                ?>
                <tr class="content-cart">

                    <td><?php echo anchor('libros/ver/'.$libro->id,'<img width="40" height="60" src="'.$ruta_foto.'">','title="Ver ficha del libro"')?></td>
                    <td><?php echo anchor('libros/ver/'.$libro->id,$libro->titulo,'title="Ver ficha del libro"') ?></td>
                    <td align="center"><?php echo $libro->precio?> €</td>
                    <td align="center"><input type="number" value="<?php echo $cantidad ?>" name="<?php echo $id ?>" readonly="readonly"></td>
                    <td align="right"><?php echo number_format($cantidad*$libro->precio,2) ?> €</td>
                </tr>
                <?php
            endforeach;
            ?>
        </tbody>
        <tfoot>
            <tr>
                <td align="right" colspan="5">      
                    
                   
                </td>
            </tr>
        </tfoot>    
    </table>
   
    <div class="totals f-right">
        <table id="shopping-cart-totals-table">
            <tfoot>
                <tr>
                    <td class="a-right">
                        <strong>Total</strong>
                    </td>
                    <td class="a-right">
                        <strong><span class="price"><?php echo number_format($total_precio,2);?> €</span></strong>
                    </td>
                </tr>
            </tfoot>
            <?php
                $base_imponible=$total_precio/1.21;
                $iva=$total_precio-$base_imponible;
            ?>
            <tbody>
                <tr>
                    <td class="a-right">Base Imponible</td>
                    <td class="a-right"><span class="price"><?php echo number_format($base_imponible,2)?> €</span></td>
                </tr>
                <tr>
                    <td class="a-right">IVA  21%</td>
                    <td class="a-right"><span class="price"><?php echo number_format($iva,2)?> €</span></td>
                </tr>
            </tbody>
        </table>
      
    </div>
    <h3 class="clearboth">Datos del cliente</h3>
    <table id="datos-usuario-pedido" width="100%" cellspacing="0" border="0">
        <tbody class="table-body">
            <tr>
                <td><strong>Nombre</strong></td>
                <td><?php echo $usuario->nombre;?></td>
            </tr>
            <tr>
                <td><strong>Email</strong></td>
                <td><?php echo $usuario->email;?></td>
            </tr>
            <tr>
                <td><strong>Dirección</strong></td>
                <td><?php echo $usuario->direccion;?></td>
            </tr>
            <tr>
                <td><strong>Ciurdad</strong></td>
                <td><?php echo $usuario->ciudad;?></td>
            </tr>
            <tr>
                <td><strong>Código Postal</strong></td>
                <td><?php echo $usuario->cp;?></td>
            </tr>
        </tbody>
        
    </table>
    <?php
            //print_r($usuario);
    ?>
        <?php echo form_submit('submitOrder', 'Enviar pedido', 'class="btn btn-checkout f-right" title="Enviar Pedido"'); ?>
    <?php echo form_submit('pagarConPaypal', 'Pagar con Paypal', 'class="btn btn-checkout f-right" title="Pagar con Paypal"'); ?>
    <div class="clearboth"></div>
     <?php
    echo form_close();
    echo anchor('libros/buscar', 'Seguir comprando', 'title="Continuar comprando" class="btn btn-contiune f-left"');
    ?>
<div class="clearboth"></div>
<?php else: ?>
    <h2>Pedido incorrecto</h2>
    <p>No tienes artículos en tu pedido. Por favor, añade algún libro al carrito y realiza el pedido.</p>
<?php endif; ?>



