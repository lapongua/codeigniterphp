<script type="text/javascript" src="<?php echo base_url()?>assets/scripts/buscar.js"></script>
<section>   
    <h2>Listado de libros</h2>
    <div class=" toolbar toolbar-top">
        <p><span><?php echo count($libros); ?></span> libro/s <span class="sorter">ordenado por: 
                <select>
                    <option value="1">Título: A->Z</option>
                    <option value="2">Título: Z->A</option>
                    <option value="3">Precio: los más baratos</option>
                    <option value="4">Precio: los más caros</option>
                    <option value="5" selected="selected">Los más recientes</option>
                    <option value="4">Los más antiguos</option>
                </select>
            </span>
        </p>
    </div>
    <h3>Filtrar por:</h3>
    <?php
     if($this->uri->uri_string() == 'libros/buscar')
       {
        ?>
        <!-- filtro por PRECIO -->
        <div><label>Precio: <span id="minimo">0</span>€ - <span id="maximo">100</span>€</label></div>

        <div id='desplazador2' class='ui-slider-1' style="margin:5px;">
            <div class='ui-slider-handle'></div>	
            <div class='ui-slider-handle'></div>
        </div>
        <?php
       }
       ?>
    <div id="listaLibros">
    <?php
    if (count($libros) > 0) {
        foreach ($libros as $libro) {
            $misautores = "";
            foreach ($libro->autores as $autor) {
                $misautores.=$autor->nombre . ',';
            }

            $misautores = rtrim($misautores, ',');
            ?>
            <div class="product-list"> 
                <?php echo anchor('libros/ver/' . $libro->id, '<img src="' . base_url() . '' . $libro->portada . '" alt="' . $libro->titulo . '" title="Comprar ' . $libro->titulo . '" width="130" />', 'class="wrapper-thumb margin-right" title="' . $libro->titulo . '"') ?>
                <h3><?php echo anchor('libros/ver/' . $libro->id, $libro->titulo . '<span>' . $misautores . '</span>') ?></h3>          
                <p><?php echo $libro->descripcion; ?> <?php echo anchor('libros/ver/' . $libro->id, 'Leer más'); ?></p>             
                <div class="price-box">
                    <div class="content-price">
        <!--                            <p class="old-price">31,40 €</p>-->
                        <p class="price"><?php echo number_format($libro->precio,2); ?> €</p>
                    </div>
                    <?php echo anchor('libros/ver/' . $libro->id, 'comprar', 'title="Comprar" class="btn"') ?>
                </div>
                <div class="clearboth"></div>
            </div>

            <?php
        }
    } else {
        ?>
        <p><?php echo $error; ?></p>
        <?php
    }
    ?>

    </div>
    <div class="toolbar toolbar-bottom">
        <div class="pages">
            <strong>Páginas: </strong>
            <ol>
                <li class="current">1</li>
                <li><a href="#" title="Ir a página 2">2</a></li>
                <li><a href="#" title="Ir a página 3">3</a></li>
                <li><a href="#" title="Ir a página 4">4</a></li>
                <li><a href="#" title="Ir a página 5">5</a></li>
                <li><a href="#" title="Siguiente">></a></li>
            </ol>
        </div>
    </div>             

</section>