<div class="breadcrumbs">
    <ul>
        <li class="home"><?php echo anchor('', 'home','title="Ir a Inicio"') ?> <span>/ </span></li>
        
        <?php if($cabecera=="Ficha libro")
        {
            ?>
        <li class="libros"><?php echo anchor('libros/buscar', 'libros','title="Ir a Libros"') ?> <span>/ </span></li>
        <li class=""><strong><?php echo $libro->titulo; ?></strong></li>
        <?php
        }
        else
        {
           ?>
        <li class="libros"><strong>libros</strong></li>
        <?php
        }
        ?>
        
    </ul>
</div>



