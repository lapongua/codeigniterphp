
<?php if ($this->session->flashdata('comentario_insertado')): ?>
    <p class="successful"><?php echo $this->session->flashdata('comentario_insertado'); ?></p>
<?php endif; ?>   
  <?php if ($this->session->flashdata('loginsuccess')): ?>
        <p class="successful"><?php echo $this->session->flashdata('loginsuccess'); ?></p>
    <?php endif; ?>
<?php   
if($this->session->flashdata('RequiredComment')): ?>
    <p class="error"><?php echo $this->session->flashdata('RequiredComment'); ?></p>
<?php endif;  ?>
    
<section class="ficha-libro">

    <div class="contenido-ficha-left f-left">
        <div id="product-gallery">
            <a href="<?php echo base_url().''.$libro->fotos[0]->path_foto; ?>" rel="lightbox" title="<?php echo $libro->titulo;?>">
                <img class="foto-ficha" src="<?php echo base_url().''.$libro->fotos[0]->path_foto; ?>" alt="<?php echo $libro->titulo;?>" title="<?php echo $libro->titulo;?>" />
                <img class="hoverimage" src="<?php echo base_url()?>assets/styles/images/zoom.png" alt="" />
            </a>
        </div>
       
        <ul id="product-gallery-thumbs">
            <?php
       foreach($libro->fotos as $foto)
        {    
       ?>
            <li><a href="#" title="Ver miniatura"><img src="<?php echo base_url().''.$foto->path_foto; ?>" alt="<?php echo $libro->titulo;?>" title="<?php echo $libro->titulo;?>" /></a></li>           
        <?php
        }
        ?>
        </ul>
        
        <h3>los más leídos</h3>
        
        <?php
           
           $last_product="";
           $contador=0;
           
           foreach($libros as $book)
           {
                if($contador<3)
                {
                    
                    $autoreslibro="";
                    foreach($book->autores as $autor)
                    {
                        $autoreslibro.=$autor->nombre.',';
                    }

                    $autoreslibro=  rtrim($autoreslibro,',');
                
        ?>
        <div class="row-leidos">
            <?php echo anchor('libros/ver/'.$book->id,'<img src="'.base_url().''.$book->portada.'" alt="'.$book->titulo.'" title="Comprar '.$book->titulo.'" width="130" />','class="wrapper-thumb" title="'.$book->titulo.'"')?> 
            <h4><?php echo $book->titulo; ?><span><?php echo $autoreslibro;?></span></h4>
            <div class="price-box">
                <div class="content-price">
<!--                    <p class="old-price f-left">31,40 €</p>-->
                    <p class="price f-left"><?php echo number_format($book->precio,2); ?> €</p>
                </div>
            </div>
            <div class="clearboth"></div>
        </div>
        <?php
                    $contador++;
                }
           }
        ?>

    </div>
<?php 
$misautores="";
$biografia="";
foreach($libro->autores as $autor)
{
  $misautores.=$autor->nombre.',';
  $biografia.=$autor->biografia.'<br>';
}
$misautores=  rtrim($misautores,',');
?>
    <div class="contenido-ficha-center f-left">
        <hgroup>
            <h1><?php echo $libro->titulo;?></h1>
            <h2><?php echo $misautores;?>  <span class="separador">·</span> <span class='editorial'><?php echo $libro->editorial;?></span> isbn: <span class='isbn'><?php echo $libro->isbn;?></span> <a href='#'>Fantasía</a></h2>
        </hgroup>
<!--        <img class="mbless" src="<?php echo base_url() ?>assets/styles/images/ratings2.png" alt='valoraciones' title='valoraciones del libro'/>-->
        <div class="input select rating-e">
                <select id="example-e" name="rating">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>
            </div>
        
        <p><?php echo $libro->descripcion;?></p>
        <p><strong>Ver precio en:</strong> 
        <select name="precioen" id="precioen">
            <option value="EUR">Euro</option>
            <option value="USD">Dolar</option>
            <option value="GBP">British Pound</option>
            <option value="CZK">Czech Koruna</option>
            <option value="DOP">Dominican Peso</option>
        </select>
            <span id="valor"><?php echo number_format($libro->precio,2);?></span> <span id="moneda">EUR</span></p>
        <h4>Compartir:</h4>
        <div class="share-buttons margin-bottom">
            <div class="fb-like" data-href="<?php echo base_url()."libros/ver/".$libro->id; ?>" data-layout="button_count" data-action="like" data-show-faces="true" data-share="true"></div>
            
            <a href="https://twitter.com/share" class="twitter-share-button" title="Comparte en twitter">Tweet</a>

        </div>
        <div id="tabs">
            <ul>
                <li><a href="#resumen">Resumen</a></li>
                <li><a href="#biografia">Biografía</a></li>
                <li><a href="#foto">Foto</a></li>
            </ul>
            <div id="resumen"><?php echo $libro->resumen;?></div>
            <div id="biografia"><?php echo $biografia; ?></div>
            <div id="foto"><img width="150" src="<?php echo base_url().''.$libro->fotos[0]->path_foto; ?>" alt="<?php echo $libro->titulo;?>" title="<?php echo $libro->titulo;?>" /></div>
            
        </div>
        <h3>valora</h3>
        <div class="content-rating margin-bottom">
<!--            <img class="f-left" src="<?php echo base_url() ?>assets/styles/images/ratings.png" alt='valoraciones' title='valora este libro'/>-->
            <div class="input select rating-f">
                <select id="example-f" name="rating">
                    <option value="1">No me ha gustado nada</option>
                    <option value="2">No me ha gustado</option>
                    <option value="3">Está bien</option>
                    <option value="4">Me ha gustado</option>
                    <option value="5">Me ha encantado</option>
                </select>
            </div>
        </div>
        <h3>comentarios</h3>
        <div class="content-coment">
            <?php
            $extracomment=0;
            if($libro->comentarios !=NULL)
            {
                $numcoment=1;
                
                foreach($libro->comentarios as $comentario)
                {
                  if($numcoment<=3)  
                  {
                ?>
                    <p class="linea-comentarios"><span><?php echo $comentario->autor;?>:</span> <?php echo $comentario->descripcion;?></p>
                <?php
                    $numcoment++;
                  }
                  else
                  {
                      $extracomment++;
                  }
                }
            }
            else
            {
               ?>
                    <p>No hay comentarios sobre este libro.</p>
              <?php
                    
            }
            if($extracomment>0)
            {
            ?>
                <p><a href="#" title="Ver todos los comentarios">+<?php echo $extracomment; ?> comentarios</a></p>
            <?php
            }
            if($this->session->userdata('usuario_valido'))
            {//libros/comentar/'.$libro->id
            ?>
                <h4>Comentar:</h4>
                <?php echo form_open('libros/comentar/'.$libro->id, 'id="sendComment"')?>
                    <?php echo form_error('autor')?>
                    <input type="text" placeholder="autor" name="autor" id="auth-comment" value="<?php echo $this->session->userdata('usuario_valido')?>" readonly="readonly">
                    <?php echo form_error('text-comment')?>
                    <textarea id='text-comment' placeholder="mensaje" name="text-comment"><?php echo set_value('text-comment')?></textarea>
                    <input class="btn" type='submit' name="CommentSubmit" value="comentar"/>
                
            <?php
                echo form_close();
            }
            else // Para comentar registrase o login
            {
                 //Nos guardamos la url para luego redireccionar aquí
                   $this->session->set_userdata('origen',current_url());
                ?>
                <p><?php echo anchor('usuarios/loginform', 'Inicie sesión')?> o <?php echo anchor('usuarios/addform', 'regístrese')?> para comentar</p>
                <?php
            }
            ?>
        </div>
        <div class="fb-comments" data-href="<?php echo base_url()."libros/ver/".$libro->id; ?>" data-width="405" data-numposts="3" data-colorscheme="light"></div>

        <div class="clearboth"></div>

    </div>
    <div class="clearboth"></div>
    <div class="contenido-ficha-bottom">
        <h3>últimos libros visitados</h3>
        <div class="wrapper-row">
            
            <?php
           
           $last_product="";
           $contador=0;
           shuffle($libros);
           foreach($libros as $book)
           {
                if($contador<5)
                {
                    $autoreslibro="";
                    foreach($book->autores as $autor)
                    {
                        $autoreslibro.=$autor->nombre.',';
                    }

                    $autoreslibro=  rtrim($autoreslibro,',');
                    if($contador==4)
                    {
                        $last_product='last-product';
                    }
                    ?>
            
            
            
            <div class="product <?php echo $last_product; ?>">
                <?php echo anchor('libros/ver/'.$book->id,'<img src="'.base_url().''.$book->portada.'" alt="'.$book->titulo.'" title="Comprar '.$book->titulo.'" width="130" />','class="wrapper-thumb" title="'.$book->titulo.'"')?>
                <h3><?php echo anchor('libros/ver/'.$book->id, $book->titulo.'<span>'.$autoreslibro.'</span>')?></h3>
                <div class="price-box">
                    <div class="content-price">
<!--                        <p class="old-price">31,40 €</p>-->
                        <p class="price"><?php echo number_format($book->precio,2); ?> €</p>
                    </div>
                    <?php echo anchor('libros/ver/'.$book->id, 'comprar', 'title="Comprar" class="btn"')?>
                </div>
            </div>
            <?php
                    $contador++;
                }
           }
        ?>
            
            <div class="clearboth"></div>
        </div>

</section>
<aside class="aside-right">

    <div id='wrapper-qty' class="">
<!--        <label>Cantidad: </label>
        <select>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
        </select>-->
        <div class="price-box">
            <div class="content-price">
<!--                <p class="old-price f-left">31,40 €</p>-->
                <p class="price f-left"><?php echo number_format($libro->precio,2);?> €</p>
                <div class="clearboth"></div>
            </div>
            <?php echo anchor('cesta/add/'.$libro->id, '+ añadir a cesta','title="Añadir a la cesta" class="btn"');?>
        </div>   
    </div>
    <?php
    if($this->session->userdata('items')>0)
    {
        echo anchor('pedidos/ver','realizar pedido','class="btn btn-checkout margin-bottom" title="finalizar el pedido"');
    }
    
    ?>
    <div id='who-read' class='margin-bottom'>
        <p>3 amigos han leído <strong><?php echo $libro->titulo;?></strong></p>
        <img src="<?php echo base_url() ?>assets/styles/images/amigos.jpg" alt="amigos" title='Amigos que han leído este libro'/>
    </div>

    <div class="fb-like-box" data-href="http://www.facebook.com/FacebookDevelopers" data-width="240" data-colorscheme="light" data-show-faces="true" data-header="true" data-stream="false" data-show-border="true"></div>
     <div id="fb-root"></div>       
     
     <script>(function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id))
                    return;
                js = d.createElement(s);
                js.id = id;
                js.src = "//connect.facebook.net/es_ES/all.js#xfbml=1";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
        </script>
</aside>
    <script type="text/javascript" src="<?php echo base_url() ?>assets/scripts/lib/barrating/jquery.barrating.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/scripts/verlibro.js"></script>




