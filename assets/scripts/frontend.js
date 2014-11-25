$(document).ready(inicio);
function inicio()
{
    // Shopping cart
    $('#shopping-cart').mouseenter(function() {
        if ($('#listado_cesta .shopping-cart-product').length > 0)
        {
            $('#listado_cesta').show();
        }
    }).mouseleave(function() {
        $('#listado_cesta').hide();
    });
    var path = $(location).attr('pathname');
    if (document.location.hostname === "localhost") {
        if (path == '/proyectoPHP/')
        {
            $('#header-search button').click(function()
            {
                $('#header-search').submit();
            });
        }

    }
    else // estem en proweb
    {
        if (path == '/p13lpg/proyecto/')
        {
            $('#header-search button').click(function()
            {
                $('#header-search').submit();
            });
        }
    }


    /*
     * DRAG&DROP
     * 
     */
    $(".drag-libro").draggable({revert: true});
    $(".dropable-cart").droppable({
        accept: ".drag-libro",
        hoverClass: 'bloqueDropHover',
        drop: function(evento, ui) {
            // alert(ui.draggable.attr('id'));
            var name = ui.draggable.attr('id');
            var toRemove = 'libro-';
            var id = name.replace(toRemove, '');

            $.post(base_url + 'cesta/add_libro_ajax', 'id=' + id, add_libro, 'json');
        }});

    function add_libro(respuesta)
    {
        var html = '';

        $.each(respuesta['libroscesta'], function(index, element) {
            preciolibro = respuesta['carro_session'][index] * element['precio'];
            html += '<li><div class="shopping-cart-product clearboth"><div class="image">';
            html += '<a width="40" href="' + base_url + 'libros/ver/' + element['id'] + '"><img width="40" src="' + base_url + '' + element['fotos'][0].path_foto + '" alt="' + element['titulo'] + '"/></a></div>';
            html += '<div class="info"><a class="title" href="' + base_url + 'libros/ver/' + element['id'] + '">' + element['titulo'] + '</a>';
            html += '<span class="qty">Cantidad: <strong>' + respuesta['carro_session'][index] + ' </strong> / Precio: <strong>' + preciolibro + ' €</strong></span> <a onclick="eliminarLibro(' + element['id'] + ')" data-id="' + element['id'] + '" class="delete-libro">x</a>';
            html += '</div></div></li>';
        });

        html += '<li><a class="btn btn-checkout clearboth" href="' + base_url + 'pedidos/ver">Realizar pedido</a></li>';

        $('#listado_cesta').html(html);
        $('.shopping-cart-total').html(respuesta['items_cesta']);
    }

    /*
     * LOGIN
     */

    //Menu de login
    $('#user-login').click(function()
    {
        $('#user-login-wrapper .dropdown-menu').show();
    });
    $('#user-login-wrapper').mouseenter(function() {
        $('#user-login-wrapper .dropdown-menu').show();
    });

    $('#user-login-wrapper').mouseleave(function() {
        $('#user-login-wrapper .dropdown-menu').hide();
    });

    var loginajaxform = false;
    $('#ajax-login-form input[type=submit]').click(function() {
        loginajaxform = true;
    });

    var aux = false;
    $('#loggin,#ajax-login-form input[type=submit]').click(function() {
        if (!loginajaxform)
        {
            email = $('#username').val();
            password = $('#contrasenya').val();
        }
        else//venim del login capçalera
        {
            email = $('#usernameT').val();
            password = $('#contrasenyaT').val();
        }

        $.post(base_url + "usuarios/ajax_login_form", "user=" + email + "&pass=" + password, function(datos)
        {

            if (datos == 0)
            {
                $(".error-login").html('Email o Password incorrecto');
                $(".error-login").show();
            }
            else
            {
                var html = '<p class="welcome marginZero">Hola, ';
                html += '<a href="' + base_url + 'usuarios/ver" class="account">' + datos + '</a> ';
                html += '<a href="' + base_url + 'usuarios/logout" class="logout">Cerrar sesión</a>';
                html += '</p>';

                $('#user-access').html(html);
                if (!loginajaxform)
                {
                    window.location.href = base_url;
                }
                aux = true;
            }
        });

        if (aux == false)
        {
            return false;
        }

    });  //fin de login click

    $('#ibox_footer_wrapper a').click(function(){
        alert("cerrando");
        
//        $('#contact-error').hide();
//        $('#contact-success').hide();
    });
    $('#contact-form').submit(function()
    {
        $('#contact-error').empty();
        var nombre = $('#contact-name').val();
        var correo = $('#contact-mail').val();
        var comentarios = $('#contact-text').val();
        if (nombre == "" || correo == "" || comentarios == "")
        {
            $('#contact-error').append("Todos los campos son obligatorios");
            $('#contact-error').show();
        }
        else
        {
            if (validarEmail(correo))
            {
                $.post(base_url + 'contacto/send_email_ajax', 'nombre=' + nombre + '&correo=' + correo + '&comentarios=' + comentarios, respuesta_formulario);
            }
        }
        return false;
    });

    /*
     * Autocomplete
     */
    $('#searchweb').autocomplete({
        minLength: 1,
        //request: informacion que vamos a  buscar
        source: function(request, response) {

            var term = request.term;

            $.post(base_url + "libros/autocomplete_ajax",
                    request,
                    function(data)
                    {
                        response(data);
                        //response(data[i]['titulo']);
                    }
            , 'json');
        }

    });
    

}//fin de inicio

function respuesta_formulario(datos)
{
    if (datos == 1)
    {
        $('#contact-error').hide();
        $('#contact-success').append("Mensaje enviado correctamente.");
        $('#contact-success').show();
        $('#contact-name').val("");
        $('#contact-mail').val("");
        $('#contact-text').val("");
    }
    else
    {
        $('#contact-error').append("Error enviando el mensaje. Inténtelo más tarde.");
        $('#contact-error').show();
    }
}

function eliminarLibro(id)
{
    $.post(base_url + 'cesta/eliminar_libro_ajax', 'id=' + id, actualizar_cesta, 'json');
}

function actualizar_cesta(respuesta)
{
    if (respuesta == "")
    {
        $('#listado_cesta').html("No tiene artículos en su cesta de la compra.");
        $('.shopping-cart-total').html(0);
    }
    else
    {
        //var oLibros = respuesta;
        var html = '';

        $.each(respuesta['libroscesta'], function(index, element) {
            preciolibro = respuesta['carro_session'][index] * element['precio'];
            html += '<li><div class="shopping-cart-product clearboth"><div class="image">';
            html += '<a width="40" href="' + base_url + 'libros/ver/' + element['id'] + '"><img width="40" src="' + base_url + '' + element['fotos'][0].path_foto + '" alt="' + element['titulo'] + '"/></a></div>';
            html += '<div class="info"><a class="title" href="' + base_url + 'libros/ver/' + element['id'] + '">' + element['titulo'] + '</a>';
            html += '<span class="qty">Cantidad: <strong>' + respuesta['carro_session'][index] + ' </strong> / Precio: <strong>' + preciolibro + ' €</strong></span> <a onclick="eliminarLibro(' + element['id'] + ')" data-id="' + element['id'] + '" class="delete-libro">x</a>';
            html += '</div></div></li>';
        });

        html += '<li><a class="btn btn-checkout clearboth" href="' + base_url + 'pedidos/ver">Realizar pedido</a></li>';

        $('#listado_cesta').html(html);
        $('.shopping-cart-total').html(respuesta['quedan_cesta']);

    }
}


/*
 * IBOX SETTINGS
 */
iBox.inherit_frames = false;
iBox.close_label = 'cerrar';
iBox.default_width = 400;

