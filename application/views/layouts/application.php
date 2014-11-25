<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width" />
        <?php
        //FACEBOK
        if($this->uri->segment(1)=='libros' && $this->uri->segment(2)=='ver'):        
        ?>
       <meta property="og:title" content="<?php echo $titulo; ?>" />
       <meta property="og:description" content="<?php echo $descriptionF->descripcion; ?>" />
       <meta property="og:image" content="<?php echo base_url().''.$fotolibro[0]->path_foto;?>" />
        <?php
        endif;
        //FACEBOOK
        ?>

        <title><?php echo $titulo; ?></title>

        <!-- Estilos -->
        <!--	<link rel="stylesheet" type="text/css" href="assets/styles/css/main.css" /> 
                <link rel="stylesheet/less" type="text/x-less" href="assets/styles/css/content.less" />-->


        <!--[if !IE]><!-->
        <link rel="stylesheet/less" type="text/css" href="<?php echo base_url() ?>assets/styles/css/main.less" /> 
        
        <!--<![endif]-->
        <?php
        if($_SERVER['REQUEST_URI']=='/proyectoPHP/' || $_SERVER['REQUEST_URI']=='/')
        {
            ?>
        <link href="<?php echo base_url() ?>assets/styles/css/flexslider.css" rel="stylesheet" type="text/css">
            <?php   
        }
        ?>



        <!-- IE 9 or above -->
        <!--[if gte IE 9]>
        <link rel="stylesheet/less" type="text/css" href="<?php echo base_url() ?>assets/styles/css/main.less" />
        <![endif]-->

        <!-- IE 8 or below -->   
        <!--[if lt IE 9]>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/styles/css/main.css" />
        <![endif]-->

        <!-- Tipografía -->
        <link href='http://fonts.googleapis.com/css?family=Noto+Sans:400,700' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Boogaloo' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Bangers' rel='stylesheet' type='text/css'>
        <link href="<?php echo base_url() ?>assets/styles/css/jquery-ui-1.10.4.custom.min.css" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url() ?>assets/styles/css/lightbox.css" rel="stylesheet" type="text/css">
        
        <script> var base_url = "<?php echo base_url(); ?>"</script>

        <!-- pongo estos escripts aqui porque me interesa que se carguen antes que la pagina sino se veen cosas raras -->
        <script type="text/javascript" src="<?php echo base_url() ?>assets/scripts/lib/jquery/jquery.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>assets/scripts/lib/less/less.min.js"></script>
        <!--[if lt IE 9]>
           <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
           <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
         <![endif]-->

    </head>
    <body>
        
        <div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/es_ES/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

        <div class="page">
            <header>
                <h1 id="logo"><?php echo anchor('', 'Read.me tu tienda de libros online', 'title="Tu tienda de libros online"') ?></h1>
                <div id="header-user-info">
                    <div id="user-access">
                        <?php
                        if ($this->session->userdata('usuario_valido')) {
                            echo '<p class="welcome marginZero">Hola, ' . anchor('usuarios/ver', $this->session->userdata('usuario_valido'), 'class="account"') . ' ' . anchor('usuarios/logout', 'Cerrar sesión', 'class="logout"') . '</p>';
                        } else {
                            ?>
                            <div id="user-login-wrapper">
                                <a id="user-login" class="userlink">Identifícate<span class="caret"></span></a>
                                <?php
                                // echo anchor('usuarios/loginform', 'Identifícate<span class="caret"></span>','id="user-login" class="userlink"');
                                ?>
                                <div class="dropdown-menu" style="display: none;">
                                    <div class="error-login error" style="display: none;"></div>
                                    <form method="post" id="ajax-login-form">
                                        <div class="field">
                                            <label class="required" for="usernameT">Dirección de email <em>*</em></label>
                                            <div class="input-box">
                                                <input type="email" title="Dirección de email" id="usernameT" value="<?php echo set_value('username') ?>" name="username">

                                            </div>
                                        </div>
                                        <div class="field">
                                            <label class="required" for="contrasenyaT">Contraseña <em>*</em></label>
                                            <div class="input-box">
                                                <input type="password" title="Contraseña" id="contrasenyaT" name="contrasenya" value="<?php echo set_value('contrasenya') ?>">
                                            </div>
                                        </div>


                                        <p class="required">* Campos Obligatorios</p>
                                        <div class="buttons-set">
                                            <?php echo anchor('#', '¿Ha olvidado su contraseña?', 'class="f-left"') ?>

                                            <input type="submit" id="loggin" class="btn f-right" name="sender" value="Acceder" />
                                        </div>
                                        <?php
                                        echo form_close();
                                        ?>
                                </div> 
                            </div> /  
                            <?php
                            echo anchor('usuarios/addform', 'Regístrate', 'class="userlink"');
                        }
                        ?>



                    </div>
<!--                    <div id="currency"><a href="#" title="Modificar moneda" class="userlink">€<span class="caret"></span></a></div>-->
                </div>

                <?php
                $attributes = array('id' => 'header-search');
                echo form_open('libros/buscar', $attributes);
                ?>
                <input type="search" id="searchweb" name="searchweb" placeholder="Buscar por título, autor, ISN, ..." />
                <?php
                echo form_button('bLibro', 'Buscar');      
              
                echo form_close();

                if ($this->session->userdata('items')) {
                    $articulos = $this->session->userdata('items');
                } else {
                    $articulos = 0;
                }

                if ($this->session->userdata('vacia') == TRUE) {
                    $articulos = 0;
                    $this->session->set_userdata('items', 0);
                    $this->session->unset_userdata('vacia');
                }
                ?>


                <div id="shopping-cart" class="dropable-cart">
                    <?php echo anchor('cesta/ver', '<span class="shopping-cart-total">' . $articulos . '</span><span class="shopping-cart-text">Carrito</span><span class="caret"></span>', 'title="Ir al carrito" class="cesta"'); ?>
                    
                        <ul id="listado_cesta" style="display: none;">
                            <?php if (count($carro_session) > 0 && $this->session->userdata('carro')): ?>
                            <?php
                            foreach ($carro_session as $id => $cantidad):
                                $libro = $librosCarro[$id];
                                $ruta_foto = base_url() . '' . $libro->fotos[0]->path_foto;
                                ?>
                                <li>

                                    <div class="shopping-cart-product clearboth">
                                        <div class="image">
                                            <?php echo anchor('libros/ver/' . $libro->id, '<img width="40" alt="' . $libro->titulo . '" src="' . $ruta_foto . '">', 'width="40" title="Ver ' . $libro->titulo . '"') ?>      
                                        </div>
                                        <div class="info">
                                            <?php echo anchor('libros/ver/' . $libro->id, $libro->titulo, 'class="title" title="Ver ' . $libro->titulo . '"') ?>
                                            <span class="qty">Cantidad: <strong><?php echo $cantidad ?> </strong> / Precio: <strong><?php echo number_format($cantidad * $libro->precio, 2) ?> €</strong></span> <a onclick="eliminarLibro(<?php echo $libro->id; ?>)" class="delete-libro" data-id="<?php echo $libro->id; ?>" title="Eliminar <?php echo $libro->titulo ?>">x</a>
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                            <li><?php echo anchor('pedidos/ver', 'Realizar pedido', 'class="btn btn-checkout clearboth"') ?></li>
                        <?php endif; ?>
                        </ul>
                    
                </div>
                <nav id="nav">
                    <ul>
                        <li class="first active"><?php echo anchor('', 'home', 'title="Ir a Inicio"') ?></li>
                        <li class=""><?php echo anchor('libros/buscar', 'libros', 'title="Ir a Libros"') ?></li>
                        <li><?php echo anchor('autores/ver', 'autores', 'title="Ir a Autores"') ?></li>
                        <li><a href="#" title="Lo más leído">lo más leído</a></li>
                        <li><?php echo anchor('usuarios/mapa','mapa usuarios','title="Ver localización de usuarios"') ?></li>
                        <li><a href="#contact-form" title="Contactar con nosotros" rel="ibox">contacto</a></li>
                    </ul>
                </nav>
                <form id="contact-form" method="post" style="display: none;">
                    <div id="contact-error" class="error" style="display: none;"></div>
                    <div id="contact-success" class="successful" style="display: none;"></div>
                    <table>
                        <tbody>
                            <tr>
                                <td><label for="contact-name">Nombre:</label></td>
                                <td><input type="text" id="contact-name" name="contact-name"></td>
                            </tr>
                            <tr>
                                <td><label for="contact-mail">E-mail:</label></td>
                                <td><input type="email" id="contact-mail" name="contact-mail"></td>
                            </tr>
                            <tr>
                                <td><label for="contact-text">Comentarios:</label></td>
                                <td><textarea id="contact-text" name="contact-text"></textarea></td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <input class="btn" id="contact-submit" type="submit" name="contact-submit">
                                </td>
                            </tr>
                        </tbody>
                    </table>                    
                </form>


            </header>

            <?php
            if (isset($yield_breadcrumb)) {
                echo $yield_breadcrumb;
            }

            if (isset($yield_sidebar)) {
                echo $yield_sidebar;
            }
            ?>
            <?php echo $yield ?>


            <footer>   
                <div id="footer-social-wrapper">
                    <div class="f-left" id="footer-share-links">

                        <div class="btn-share plus f-left">

                            <div class="g-plusone" data-size="medium"></div>
                            <div class="fb-follow" data-href="https://www.facebook.com/zuck" data-height="30" data-colorscheme="light" data-layout="standard" data-show-faces="true"></div>


                        </div>
                        <?php echo anchor('libros/rss', '<img src="'.base_url().'assets/styles/images/feed-icon.png" title="Suscrítbete al RSS" alt="Suscrítbete al RSS"/>')?>
                        <div class="btn-share clearboth"></div>
                    </div>
                    <div class="f-right" id="footer-follow-links">
                        <h4 class="label">Síguenos en:</h4>
                        <a class="fb" href="https://www.facebook.com" target="_blank" title="Hazte fan">Facebook</a>
                        <a class="tw" href="http://www.twitter.com" target="_blank" title="Síguenos en Twitter">Twitter</a>
                        <a class="yt" href="http://www.youtube.com" target="_blank" title="Visita nuestro canal">Youtube</a>
                        <a class="go" href="https://accounts.google.com" target="_blank" title="Síguenos en Google+">Google+</a>
                    </div>
                    <div class="clearboth"></div>
                </div>   


                <div id="footer-top-wrapper" class="">
                    <div class="footer-left f-left">
                        <div id="footer-links">
                            <div class="f-nosotros f-left">
                                <h4><span class="icon f-left"></span>nosotros</h4>
                                <ul>
                                    <li class="first"><a title="quiénes somos" href="#">Quiénes somos</a></li>
                                </ul>
                            </div>
                            <div class="f-tienda f-left">
                                <h4><span class="icon f-left"></span>tienda</h4>
                                <ul>
                                    <li><a title="términos de uso" href="#">Términos de uso</a></li>
                                    <li><a title="política de privacidad" href="#">Política de privacidad</a></li>
                                    <li><a title="Sitemap" href="#">Sitemap</a></li>
                                    <li><a title="RSS" href="#">RSS</a></li>
                                </ul>
                            </div>
                            <div class="f-ayuda f-left">
                                <h4><span class="icon f-left"></span>ayuda</h4>
                                <ul>
                                    <li><a title="faq" href="#">Preguntas frecuentes</a></li>
                                    <li><a href="#">Contáctanos</a></li>
                                </ul>
                            </div>
                        </div>      
                    </div>
                    <div id="footer-right" class="f-left">
                        <div id="cusomter-service">
                            <h4><span class="icon f-left"></span>Atención al cliente</h4>
                            <div><span>Teléfono: </span>966 688 999</div>
                            <div><span>Skype: </span>lapongua</div> 
                        </div>
                        <div id="subscribe-footer">    
                            <form id="newsletter-footer" method="post" action="#">            
                                <label class="f-left" for="newsletter-footer-input">suscríbete</label>
                                <div class="f-left" id="subscribe-footer-input-container">                    
                                    <div class="input-box f-left">
                                        <input type="text" id="newsletter-input" title="suscríbete al newletter" name="newsletter-email" placeholder="introduce tu e-email">             
                                    </div>     
                                    <button class="f-right" title="subscríbete" type="submit">subscríbete</button>
                                </div>
                                <div class="clearboth"></div>
                            </form>
                        </div>
                    </div>
                    <div class="clearboth"></div>
                </div>
                <div id="footer-bottom">
                    <p>© 2013 Universidad de Alicante <span class="designer f-right">By Lara Pont</span></p>
                </div>
            </footer>

        </div><!-- fin de page-->
        
        
        

        <script type="text/javascript" src="<?php echo base_url() ?>assets/scripts/lib/jquery/jquery-ui-1.10.4.custom.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>assets/scripts/lib/lightbox/lightbox-2.6.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>assets/scripts/lib/ibox/ibox.js"></script>
        <?php
            
                if($_SERVER['REQUEST_URI']=='/proyectoPHP/' || $_SERVER['REQUEST_URI']=='/' || $_SERVER['REQUEST_URI']=='/proyectoPHP/index.php')
                {
                    ?>
                <script type="text/javascript" src="<?php echo base_url() ?>assets/scripts/lib/flexslider/jquery.flexslider-min.js"></script>
                <script type="text/javascript" src="<?php echo base_url() ?>assets/scripts/index.js"></script>
                    <?php   
                }

            ?>
        <script type="text/javascript" src="<?php echo base_url() ?>assets/scripts/comunes.js"></script>        
        <script type="text/javascript" src="<?php echo base_url() ?>assets/scripts/frontend.js"></script>
        <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

            ga('create', 'UA-3343938-19', 'ua.es');
            ga('send', 'pageview');

          </script>
        
    </body>
</html>
