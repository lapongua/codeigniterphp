<section>
    <?php if ($this->session->flashdata('exito')): ?>
        <p class="successful"><?php echo $this->session->flashdata('exito'); ?></p>
        <?php //$this->session->unset_userdata('exito'); ?>
    <?php endif; ?>
    <?php if ($this->session->flashdata('loginsuccess')): ?>
        <p class="successful"><?php echo $this->session->flashdata('loginsuccess'); ?></p>
        <?php //$this->session->unset_userdata('loginsuccess'); ?>
    <?php endif; ?>
    <?php if ($this->session->flashdata('logout')): ?>
        <p class="successful"><?php echo $this->session->flashdata('logout'); ?></p>
        <?php //$this->session->unset_userdata('logout'); ?>
    <?php endif; ?>
    <div id="slider-home" class="margin-bottom">
        <ul class="slides">
            <li style="position: relative;">
                <a class="slider01" href="#" title="Descubre nuestras ofertas">
                    <h2>Black <span>Friday</span></h2>
                    <div class="text-slider">
                        <h3><span class="solo">Sólo 3 días</span> para adelantar tus compras de navidad <span class="ofertas">con ofertas irrepetibles</span></h3>
                        <h4 class="vie">Viernes <span>29</span> noviembre</h4>
                        <h4 class="sab">Sábado <span>30</span> noviembre</h4>
                        <h4 class="dom">Domingo <span>1</span> diciembre</h4> 
                    </div>
                    <img src="<?php echo base_url() ?>uploads/images/galeria/black-friday.jpg" alt="Black friday en Read.me" title="Black Friday en Read.me"/>
                </a>
            </li>
            <li style="position: relative;">
                <a class="slider02" href="#" title="Descubre nuestras ofertas">
                    <h2>15% <span>DESCUENTO</span></h2>
                    <div class="text-slider">
                        <h3><span class="solo">Hoy</span> en todas tus compras <span class="ofertas">¡DATE PRISA!</span></h3>
                        <h4>Código promocional: OFERTON14</h4>
                    </div>
                    
                   
                </a>
            </li>
        </ul>

    </div>
    <h2><?php echo $cabecera ?></h2>
    <div class="wrapper-row">

        <?php
        $last_product = "";
        $contador = 0;
        foreach ($libros as $libro) {
            if ($contador < 5) {
                $misautores = "";
                foreach ($libro->autores as $autor) {
                    $misautores.=$autor->nombre . ',';
                }

                $misautores = rtrim($misautores, ',');

                //              print_r("<pre>");print_r($libro->portada);print_r("</pre>");

                if ($contador == 4) {
                    $last_product = 'last-product';
                }
                ?>
                <!-- ÚLTIMOS PRODUCTOS -->
                <div class="product <?php echo $last_product ?>">
                    <div id="libro-<?php echo $libro->id ?>" class="drag-libro"><?php echo anchor('libros/ver/' . $libro->id, '<img src="' . base_url() . '' . $libro->portada . '" alt="' . $libro->titulo . '" title="Comprar ' . $libro->titulo . '" width="130" />', 'class="wrapper-thumb" title="' . $libro->titulo . '"') ?></div>
                    <h3><?php echo anchor('libros/ver/' . $libro->id, $libro->titulo . '<span>' . $misautores . '</span>') ?></h3>
                    <div class="price-box">
                        <div class="content-price">
        <!--                    <p class="old-price">31,40 €</p>-->
                            <p class="price"><?php echo number_format($libro->precio, 2); ?> €</p>
                        </div>
                        <?php echo anchor('libros/ver/' . $libro->id, 'comprar', 'title="Comprar" class="btn"') ?>
                    </div>
                </div>
                <?php
                $contador++;
            }
        }
        ?>
        <div class="clearboth"></div>

    </div><!--ULTIMOS PRODUCTOS-->



    <h2>Lo más vendido</h2>
    <div class="wrapper-row">
        <?php
        $last_product = "";
        $contador = 0;
        shuffle($libros);
        foreach ($libros as $libro) {
            if ($contador < 5) {
                $misautores = "";
                foreach ($libro->autores as $autor) {
                    $misautores.=$autor->nombre . ',';
                }

                $misautores = rtrim($misautores, ',');

                //              print_r("<pre>");print_r($libro->portada);print_r("</pre>");

                if ($contador == 4) {
                    $last_product = 'last-product';
                }
                ?>
                <!-- LOS MÁS VENDIDOS -->
                <div class="product <?php echo $last_product ?>">
                    <div id="libro-<?php echo $libro->id ?>" class="drag-libro"><?php echo anchor('libros/ver/' . $libro->id, '<img src="' . base_url() . '' . $libro->portada . '" alt="' . $libro->titulo . '" title="Comprar ' . $libro->titulo . '" width="130"/>', 'class="wrapper-thumb" title="' . $libro->titulo . '"') ?></div>
                    <h3><?php echo anchor('libros/ver/' . $libro->id, $libro->titulo . '<span>' . $misautores . '</span>') ?></h3>
                    <div class="price-box">
                        <div class="content-price">
        <!--                    <p class="old-price">31,40 €</p>-->
                            <p class="price"><?php echo number_format($libro->precio, 2); ?> €</p>
                        </div>
                        <?php echo anchor('libros/ver/' . $libro->id, 'comprar', 'title="Comprar" class="btn"') ?>
                    </div>
                </div>
                <?php
                $contador++;
            }
        }
        ?>
        <div class="clearboth"></div>

    </div><!--Los mas vendidos-->

</section>

