<?php if (count($carro_session) > 0): ?>
<?php //echo "Numero de libros:".count($carro_session);
 //print_r($carro_session);
 //print_r($librosCarro);
?>
    <h2>Tu cesta de la compra</h2>
    <?php
    echo form_open('cesta/update', 'id="cesta"');
    ?>
    <table width="100%" cellspacing="0" border="0" id="table-cart">
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
                //print_r($librosCarro);
                
                //print_r($libro->fotos[0]->path_foto);
                $ruta_foto=base_url().''.$libro->fotos[0]->path_foto;
                ?>
                <tr class="content-cart">

                    <td><?php echo anchor('libros/ver/'.$libro->id,'<img width="40" height="60" src="'.$ruta_foto.'">','title="Ver ficha del libro"')?></td>
                    <td><?php echo anchor('libros/ver/'.$libro->id,$libro->titulo,'title="Ver ficha del libro"') ?></td>
                    <td align="center"><?php echo $libro->precio?> €</td>
                    <td align="center"><input type="number" value="<?php echo $cantidad ?>" name="<?php echo $id ?>"></td>
                    <td align="right"><?php echo number_format($cantidad*$libro->precio,2) ?> €</td>
                </tr>
                <?php
            endforeach;
            ?>
        </tbody>
        <tfoot>
            <tr>
                <td align="right" colspan="5">      
                    <?php echo form_submit('update', 'Actualizar cesta', 'class="button-act btn f-right"'); ?>
                    <?php echo anchor('libros/buscar', 'Seguir comprando', 'title="Continuar comprando" class="btn btn-contiune f-left"'); ?>
                </td>
            </tr>
        </tfoot>    
    </table>
    <?php
    echo form_close();
    ?>
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
        <?php echo anchor('pedidos/ver', 'Realizar pedido', 'class="btn btn-checkout" title="Realizar Pedido"') ?>      
    </div>

<?php else: ?>
    <?php $this->session->set_userdata('vacia',TRUE)?>
    <h2>La cesta está vacía.</h2>
    <p>No tiene artículos en su cesta de la compra.</p>
    <p><?php echo anchor('/','Clic aquí');?> para seguir comprando.</p>
<?php endif; ?>



