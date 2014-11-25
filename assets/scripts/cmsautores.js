
$(document).ready(function() {
    /*
     * Autocomplete AUTORES
     */
    $('#autor-search').autocomplete({
        minLength: 1,
        //request: informacion que vamos a  buscar
        source: function(request, response) {
            var term = request.term;
            $.post(base_url + "/actions/autores_buscar.php",
                    request,
                    function(data)
                    {

                        response(data);
                    }
            , 'json');
        }

    });

    /*
     * buscador en ajax autores
     */
    $("#form-search-autor button").click(function() {

        var cadena = $("#autor-search").val();

        $.post(base_url + "/actions/autores_buscar.php", "term=" + cadena, printAutores, 'json');

    });


    $('#form-search-autor').submit(function() {
        var cadena = $("#autor-search").val();
        $.post(base_url + "/actions/autores_buscar.php", "term=" + cadena, printAutores, 'json');
        $("#searchclear").css('display', 'block');
        return false;
    });

    function printAutores(respuesta)
    {

        var html = "<thead><tr><th>nombre</th><th></th></tr></thead><tbody>";
        var numAutores = 0;
        if (respuesta != null)
        {
            $.each(respuesta, function(index, element) {
                html += "<tr><td>" + element['label'] + "</td><td><a href='actions/autores_guardar.php?id=" + element['id'] + "' class='tooltipt transition editar-autor' title='Editar'><span class='glyphicon glyphicon-edit btn-lg'></span></a><a href='' class='tooltipt transition eliminar-autor' data-toggle='modal' data-target='#myModal' title='Eliminar'><span class='glyphicon glyphicon-remove btn-lg'></span></a></td></tr>";
                numAutores++;
            });
        }
        else
        {
            html += "<tr><td>No hemos encontrado ninguna coincidencia con el término buscado.</td><td></td></tr>";
        }


        html += "</tbody>";
        
        var htmlNumAutores="<h1 class='pull-left'><span class='fa fa-user'></span>Autores <span class='badge'>"+numAutores+"</span></h1>"

        $('#listado-autores').html(html);
        $('.wrapper-pager p').html(numAutores + " autores");
        $('h1').html(htmlNumAutores);
    }

    /*
     * limpiar input autores
     */
    $('#autor-search').blur(function()
    {
        if ($(this).val()) {
            $("#searchclear").css('display', 'block');
        }
    });

    $("#searchclear").click(function() {
        $("#autor-search").val('');
        $("#searchclear").css('display', 'none');
        $(window).attr("location", base_url + "/autores.php");
    });

    /*
     * Ordenar listado de autores
     */
    $("#listado-autores").tablesorter();

    $("th.header").click(function() {
        if ($("th.header").hasClass('headerSortDown'))
        {
            $('.headerSortDown span').removeClass();
            $('.headerSortDown span').addClass('fa fa-sort-desc');
        }
        else if ($("th.header").hasClass('headerSortUp'))
        {
            $('.headerSortUp span').removeClass();
            $('.headerSortUp span').addClass('fa fa-sort-asc');
        }
        else
        {
            $('.header span').removeClass();
            $('.header span').addClass('fa fa-sort-asc');
        }


    });
    function getURLParameter(url, name) {
        return (RegExp(name + '=' + '(.+?)(&|$)').exec(url) || [, null])[1];
    }

    /*
     * Eliminar autores
     */
    $('#listado-autores a.eliminar-autor').click(function() {

        var url = $(this).parent().find(".editar-autor").attr('href');
        var autor = $(this).parent().parent().find("span.nomAutor").html();
        //alert(url+'/'+autor);

        var texto = "¿Estás seguro que deseas eliminar el autor <strong>" + autor + "</strong>?";
        // alert(texto);
        var id = getURLParameter(url, 'id');
        //alert(id);
        $('a.elimina-este').attr('href', 'actions/autores_eliminar.php?id=' + id);
        $('.modalDelAutor .modal-body').html(texto);
    });

    /*
     * Nuevo autor
     */
    $('.add-este-autor').click(function() {
        $.ajax({
            type: "POST",
            url: base_url + "/actions/autores_guardar.php",
            data: $('form.formAddAutor').serialize(),
            success: function() {
                alert("autor insertado correctamente!");
                $("#addAuthor").modal('hide');
                location.reload();
            },
            error: function() {

                $("#error-autor").html("<div class='alert alert-danger alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button><i class='fa fa-exclamation-triangle'></i><strong>Error:</strong> El nombre del autor es obligatorio.</div>");
            }
        });

        return false;
    });

    $('#addAuthor .modal-footer .btn-default').click(function() {
        $("#error-autor").empty();
    });



    /*
     * Editar autor inline
     */
    function editarAutor(existe)
    {
        //Ponemos el foco en el ultimo caracer del autor
        var SearchInput = $('#editbox');
        var strLength = SearchInput.val().length;
        SearchInput.focus();
        SearchInput[0].setSelectionRange(strLength, strLength);

        $('a.save-author').click(function() {
            submitAutor(existe);
        });

        $('.formAddAutores').submit(function() {
            submitAutor(existe);
            alert("stop");//sino no se para
            return false;
        });

    }
    var existe;
    function existeAutor()
    {
        $('#listado-autores tr td.edit').each(function() {
            if ($(this).attr('class') == 'edit editing') {
                existe = 1;
                return false;
            }
            else
            {
                existe = 0;
                return true;
            }
        });

        return existe;
    }

    function cancelEditAutor(contenido)
    {
        $('button.cancel-author').click(function() {
            var id = $(this).parent().parent().attr("id");
            var idAutor = id.substring(5);
            $('#edit-' + idAutor).html('<span class="nomAutor">' + contenido + '</span>');
            $('td.edit').removeClass('editing');
            $('.eliminar-autor').removeClass('disabled');
        });
    }
    $('td.edit').dblclick(function() {

        var existe = existeAutor();

        if (existe === 0)
        {
            $('.eliminar-autor').addClass('disabled');

            $(this).addClass('editing');

            var contenido = $(this).find("span").text();
            $(this).html('<form class="formAddAutores"><input id="editbox" class="form-control" value="' + contenido + '" type="text" name="editedNombre"><button type="button" class="btn btn-default btn-xs cancel-author">Cancelar</button><a class="btn btn-primary btn-xs save-author">Guardar</a></form>');

            editarAutor(existe);
            cancelEditAutor(contenido);

        }
    });

    $('td a.editar-autor').click(function() {

        var existe = existeAutor();
        if (existe === 0)
        {

            $(this).parent().parent().find('.edit').addClass('editing');
            var contenido = $(this).parent().parent().find('.edit').find("span").text();
            $(this).parent().parent().find('.edit').html('<form class="formAddAutores"><input id="editbox" class="form-control" value="' + contenido + '" type="text" name="editedNombre"><button type="button" class="btn btn-default btn-xs cancel-author">Cancelar</button><a class="btn btn-primary btn-xs save-author">Guardar</a></form>');
            editarAutor(existe);
            cancelEditAutor(contenido);
//            
        }
        return false;
    });
    function submitAutor()
    {

        var id = $('a.save-author').parent().parent().attr("id");
        var idAutor = id.substring(5);
        $.ajax({
            type: "POST",
            url: base_url + "/actions/autores_guardar.php?id=" + idAutor,
            data: $('form.formAddAutores').serialize(),
            success: function() {
                alert("autor editado correctamente!");
//                $("#addAuthor").modal('hide');
                existe = 0;
                $('td.edit').removeClass('editing');
                location.reload();
            },
            error: function() {
                existe = 1;
                alert("El nombre es obligatorio");

//                $("#error-autor").html("<div class='alert alert-danger alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button><i class='fa fa-exclamation-triangle'></i><strong>Error:</strong> El nombre del autor es obligatorio.</div>");
            }

        });

        return false;

    }
    
    
});