$(document).ready(function() {
    
    /*
     * Validar comentarios o viceversa
     */
    $('.comentarioValidado').click(function() {
        var $this = $(this);
        var valor=$this.html();
        var ids=$(this).parent().find('.idcomentario').html();
        $.ajax({
            url: base_url + "/actions/comentarios_validar.php",
            type: "POST",
            data: "id="+ids,
            success: function(data)
            {
                if(data=="1"){
                    $this.addClass("valido");
                    $this.removeClass("noValido");
                    //$this.html("1");
                    $this.html('<span class="fa fa-check-square-o btn-lg"></span>');
                }
                else{
                    $this.addClass("noValido");
                    $this.removeClass("valido");
                    $this.html('<span class="fa fa-square-o btn-lg"></span>');
                    //$this.html("0");
                }
                
            },
            error: function()
            {
                
            }
        });
    });
    
    /*
     * buscador en ajax autores
     */
    $("#form-search-comentarios button").click(function() {

        var cadena = $("#comentarios-search").val();
        var validado = $("#esValido").val();

        $.post(base_url + "/actions/comentarios_buscar.php", "q=" + cadena+"&validado="+validado, printComentarios, 'json');

    });


    $('#form-search-comentarios').submit(function() {
        var cadena = $("#comentarios-search").val();
        var validado = $("#esValido").val();
        
        $.post(base_url + "/actions/comentarios_buscar.php", "q=" + cadena+"&validado="+validado, printComentarios, 'json');
        $("#searchclear").css('display', 'block');
        return false;
    });

    function printComentarios(respuesta)
    {

        var html = "<thead><tr><th>id</th><th>comentario</th><th>validado</th><th></th></tr></thead><tbody>";
        var numComentarios = 0;
        if (respuesta != null)
        {
          // alert(respuesta);
            $.each(respuesta, function(index, element) {
                
                var miclase,miicono;
                if(element['validado']=='0')
                {
                    miclase='noValido';
                    miicono='<span class="fa fa-square-o btn-lg"></span>';
                }
                else
                {
                    miclase='valido';
                    miicono='<span class="fa fa-check-square-o btn-lg"></span>';
                }
               
                html +="<tr><td class='idcomentario'>"+element['idc']+"</td>";
                html +="<td><h2><span class='comenta'>"+element['autor']+"</span> <span class='comenta-text'>comenta <a href='libros_ficha.php?id="+element['id']+"'>"+element['nomllibre']+"</a></span></h2><p>"+element['descripcion']+"</p></td>";
                html +="<td class='comentarioValidado "+miclase+"'>"+miicono+"</td>";
                html +="<td><a href='' class='tooltipt transition eliminar-comentario' data-toggle='modal' data-target='#deleteComment' title='Eliminar'><span class='glyphicon glyphicon-remove btn-lg'></span></a></td></tr>";
                
                numComentarios++;
            });
        }
        else
        {
            html += "<tr><td>No hemos encontrado ninguna coincidencia con el término buscado.</td><td></td><td></td><td></td></tr>";
        }


        html += "</tbody>";
        
        var htmlNumComentarios="<h1 class='pull-left'><span class='fa fa-user'></span>Comentarios <span class='badge'>"+numComentarios+"</span></h1>"

        $('#listado-comentarios').html(html);
        $('.wrapper-pager p').html(numComentarios + " comentarios");
        $('h1').html(htmlNumComentarios);
    }
    
     /*
     * limpiar input autores
     */
    $('#comentarios-search').blur(function()
    {
        if ($(this).val()) {
            $("#searchclear").css('display', 'block');
        }
    });

    $("#searchclear").click(function() {
        $("#comentarios-search").val('');
        $("#searchclear").css('display', 'none');
        $(window).attr("location", base_url + "/comentarios.php");
    });
    
    /*
     * Eliminar comentarios
     */
    
    $('#listado-comentarios a.eliminar-comentario').click(function() {
        var libro=$(this).parent().parent().find(".comenta-text a").html();
        var autor = $(this).parent().parent().find("span.comenta").html();
        var id = $(this).parent().parent().find(".idcomentario").html();
        
        alert(id);

        var texto = "¿Estás seguro que deseas eliminar el comentario de <strong>" + autor + "</strong> sobre "+libro+"?";
        // alert(texto);
        
       // alert(id);
        $('a.elimina-este').attr('href', 'actions/comentarios_eliminar.php?id=' + id);
        $('.modalDelComentario .modal-body').html(texto);
    });

});