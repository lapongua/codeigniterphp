/*
 * JQUERY BACKEND
 */
$(document).ready(function() {

    function getURLParameter(url, name) {
        return (RegExp(name + '=' + '(.+?)(&|$)').exec(url) || [, null])[1];
    }

    $('#listado-libros a.eliminar-libro').click(function() {

        var url = $(this).parent().find(".editar-libro").attr('href');
        var libro = $(this).parent().parent().find("h2 a").html();
        var autor = $(this).parent().parent().find("h3 a").html();

        var texto = "¿Estás seguro que deseas eliminar el libro <strong>" + libro + "</strong> de <strong>" + autor + "</strong>?";
        // alert(texto);
        var id = getURLParameter(url, 'id');
        $('a.elimina-este').attr('href', 'actions/libros_eliminar.php?id=' + id);
        $('.modalDelBook .modal-body').html(texto);
    });
    
    $("#searchclearbook").click(function() {
        $(window).attr("location", base_url + "/libros.php");
    });
    
    
   
        
});



