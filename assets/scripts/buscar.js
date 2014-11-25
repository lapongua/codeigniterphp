$(document).ready(inicio);
function inicio()
{
    $('#header-search').submit(function()
    {
        buscar_titulo();
        return false;//!!!!!
    });
    $('#header-search button').click(function()
    {
        buscar_titulo();
        return false;//!!!!!
    });

    function buscar_titulo()
    {
        // Ponemos un letrero para indicar que estamos cargando los libros
        $('#listaLibros').html('<span>Cargando Libros...</span>');

        var titulo = $('#searchweb').val();
        var min=$("#desplazador2").slider("values")[0];
        var max=$("#desplazador2").slider("values")[1];
       
        $.post(base_url + 'libros/buscar_titulo_ajax', 'titulo=' + titulo+'&min='+min+'&max='+max, ver_busqueda, 'json');

    }
    
    /*
     * FILTRAR POR PRECIO
     */
    $("#desplazador2").slider({min: 0, max: 100, step: 1, range: true, values: [0, 100], change: actualizarValores});
    function actualizarValores()
    {
//        var min=$("#desplazador2").slider('values', 0);
//        var max=$("#desplazador2").slider('values', 1);
//        var titulo = $('#searchweb').val();
//        $("#minimo").html(min);
//        $("#maximo").html(max);
//        
//        $.post(base_url + 'libros/filtrar_precio_ajax', 'titulo='+titulo+'&min=' + min+'&max='+max, ver_busqueda, 'json');
        var opts = $(".sorter select option:selected").val();
        ordenarPor(opts);    
        
    }

    function ver_busqueda(response)
    {
        var oLibros = response; //obtemos un vector de objetos libro.
        var html = '';
        if (oLibros !== null)
        {
            numlibros = oLibros.length;
        }
        else
        {
            numlibros = 0;
        }
        if (numlibros > 0)
        {
            for (var i = 0; i < oLibros.length; i++)
            {
                html += '<div class="product-list">';
                html += '<a href="" class="wrapper-thumb margin-right" title=""><img src="' + base_url + '' + oLibros[i]['portada'] + '" alt="" title="" width="130" /></a>';
                html += '<h3><a href="' + base_url + 'libros/ver/' + oLibros[i]['id'] + '">' + oLibros[i]['titulo'] + ' <span>' + oLibros[i]['autor'] + '</span></a></h3>';
                html += '<p>' + oLibros[i]['descripcion'] + '<a href="' + base_url + 'libros/ver/' + oLibros[i]['id'] + '">Leer más</a></p>';
                html += '<div class="price-box">';
                html += '<div class="content-price">';
                html += '<p class="price">' + parseFloat(oLibros[i]['precio']).toFixed(2) + '&nbsp;&euro;</p>';
                html += '</div>';
                html += '<a class="btn" title="comprar" href="' + base_url + 'libros/ver/' + oLibros[i]['id'] + '">comprar</a>';
                html += '</div>';
                html += '<div class="clearboth"></div>';
                html += '</div>';
            }
            $('#listaLibros').html(html);
        }
        else
        {
            $('#listaLibros').html("La cadena no corresponde con ningún libro.");

        }

    }
    
    /*
     * 
     * FILTRAR RESULTADOS POR
     *
     */

    
    function ordenarPor(opts){
        var titulo = $('#searchweb').val();
        var min=$("#desplazador2").slider("values")[0];
        var max=$("#desplazador2").slider("values")[1];
        
        $("#minimo").html(min);
        $("#maximo").html(max);
        
        $.ajax({
            type: "POST",
            url: base_url + 'libros/filtrar_precio_ajax',
            dataType : 'json',
            cache: false,
            data: {filterOpts: opts,titulo:titulo,min:min,max:max},
            success: ver_busqueda
        });
        
    }
    
    var $checkboxes = $(".sorter select");
    
    $checkboxes.on("change", function(){
        var opts = $(".sorter select option:selected").val();
        ordenarPor(opts);    
    });
}