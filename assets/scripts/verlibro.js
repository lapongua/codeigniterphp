$(document).ready(function() {

    $('#precioen').change(function()
    {
        var url = base_url + "libros/cambiar_divisa_ajax";
        $desde = $('#moneda').html();
        var pars = "desde=" + $desde + "&hasta=" + $('#precioen').val() + "&cantidad=" + $('#valor').html();
        $('#valor').html("Calculando...");
        $('#moneda').html("");
        $.post(url, pars, cambiarCantidad, 'json');

        return false;
    });


    function cambiarCantidad(response)
    {
        $('#valor').html(response['cantidad'].toFixed(2));
        $('#moneda').html(response['moneda']);
    }

    tabs = $('#tabs').tabs();
    
    /*
     * Rating Books . puntuar libros.
     */
    var urls=$(location).attr('href');
    var idlibro=urls.substring(urls.lastIndexOf('/') + 1);
    $.post(base_url+"libros/cargarPuntuacion_ajax", "idlibro="+idlibro, actualizarPuntuacion, 'json');
    
    

    $('#example-f').barrating('show',{
        showSelectedRating: true,
        onSelect:function(value, text)
        {
            $.post(base_url+"libros/insertarPuntuacion_ajax", "puntuacion="+value+"&idlibro="+idlibro,actualizarPuntuacion,"json");
            
        }
    });
    
    function actualizarPuntuacion(response)
    {
        
        $('#example-e').barrating({
            showSelectedRating: false,
            readonly:true,
            initialRating:response
        });
    }
    

});


//ratings
//$(function() {
//    $('.rating-enable').click(function() {
//
//        $('#example-f').barrating({showSelectedRating: false});
//
//        $(this).addClass('deactivated');
//        $('.rating-disable').removeClass('deactivated');
//    });
//
//    $('.rating-disable').click(function() {
//        $('select').barrating('destroy');
//        $(this).addClass('deactivated');
//        $('.rating-enable').removeClass('deactivated');
//    });
//
//    $('.rating-enable').trigger('click');
//});