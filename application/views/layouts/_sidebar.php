<aside>
    <nav id="category-list" class="margin-bottom">
        <ul>
            <li><a href="listado-libros.php" title="Arte">arte</a></li>
            <li>
                <a href="listado-libros.php" title="Informática"><span class="cplus"></span> informática</a>
                <ul>
                    <li><a href="listado-libros.php" title="Ofimática">ofimática</a></li>
                    <li><a href="listado-libros.php" title="Programación">programación</a></li>
                    <li><a href="listado-libros.php" title="Sistemas operativos">sistemas operativos</a></li>
                </ul>
            </li>
            <li><a href="listado-libros.php" title="Literatura">literatura</a></li>
            <li><a href="listado-libros.php" title="Ciencias">ciencias</a></li>
            <li><a href="listado-libros.php" title="Novela romántica">novela romántica</a></li>
            <li><a href="listado-libros.php" title="Novela histórica">novela histórica</a></li>
        </ul>
    </nav>
    <div id="publicidad" class="margin-bottom">
        <div id="ad-2">
            <h2>¿Te gusta<br />leer?</h2>
            <ul id="patin">
                <li>
                    <div id="question-mark"></div>
                </li>
            </ul>
            <div id="content">
                <h3>¡Enhorabuena!<br />Has elegido bien.</h3>
                <?php echo anchor('libros/buscar','Ver libros')?>
            </div>
        </div>
    </div>
    <div id="mis-tweets"><h4>Cargando tweets...</h4></div>
    <script type="text/javascript" src="<?php echo base_url('/assets/scripts/aside.js'); ?>"></script>
</aside>
