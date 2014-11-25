$(document).ready(function() {
    $("#searchclearusuari").click(function() {
        $(window).attr("location", base_url + "/usuarios.php");
    });


    /* PAGINADOR USUARIS */

    function loading_show()
    {
        $('#loading').html("<img alt='loading' src='../assets/styles/images/loading.gif'/>").fadeIn('fast');
    }

    function loading_hide()
    {
        $('#loading').fadeOut();
    }

    function loadData(page)
    {
        loading_show();
        var cadena = $('#search-usuario').val();
        $.ajax
                ({
                    type: "POST",
                    url: base_url + "/actions/usuarios_cargar.php",
                    data: "page=" + page + "&cadena=" + cadena,
                    dataType: "json",
                    success: function(msg)
                    {

                        var html = "<tr><td colspan='3' style='padding: 0;border-top: none;'><div id='loading' style='display: none;'><img alt='loading' src='../assets/styles/images/loading.gif' /></div></td></tr>";
                        if (msg != null)
                        {
                            var numUsuaris = 0;//usuaris per pagina

                            $.each(msg, function(index, element) {
                                html += "<tr><td>" + element['id'] + "</td>";
                                html += "<td><h2>" + element['nombre'] + "</h2>";
                                html += "<h3><a href='mailto:" + element['email'] + "'>" + element['email'] + "</a></h3></td>";
                                html += "<td>" + element['direccion'] + "<br/>" + element['cp'] + "-" + element['ciudad']['nombre'] + " (" + element['provincia']['nombre'] + ")</td>";
                                html += "</tr>";
                                numUsuaris++;
                            });
                        }
                        else
                        {
                            html += "<tr><td>No hay más elementos en esta pagina.</td><td></td><td></td></tr>";
                        }

                        loading_hide();
                        $("#listado-usuarios tbody").html(html);
                        $(".usersencontrados").html(numUsuaris);

                    }//fin del success
                });//fin $.ajax

    }

    loadData(1); // For first time page load default results

    var current = parseInt($('li.active a').attr('p'));
    $('li.next a').attr('p', current + 1);
    if (current > 1)
    {
        $('li.previous a').attr('p', current - 1);
    }
    else
    {
        $('li.previous a').attr('p', '0');
        $('li.previous').addClass('disabled');
    }




    $('.pagination li a').click(function(e) {
        var pageClick = $(this).attr('p');
        var currentpage = $('.active a').attr('p');
        var previouspage = $('.previous a').attr('p');
        var nextpage = $('.next a').attr('p');
        var $first = $('li:first', 'ul'), $last = $('li:last', 'ul');


        switch (true)
        {
            case $(this).parent().hasClass('next'):

                $(this).parent().parent().find('li').removeClass('disabled');

                if ($('.next a').attr('p') > $('.last a').attr('p')) //Estem en el últim
                {
                    $('li.next').addClass('disabled');
                    e.preventDefault();
                }
                else
                {


                    var $next, $selected = $(".active");
                    // get the selected item
                    // If next li is empty , get the first
                    $next = $selected.next('li').length ? $selected.next('li') : $first;
                    $selected.removeClass("active");
                    $next.addClass('active');

                    $('.next a').attr('p', parseInt(currentpage) + 2);
                    $('.previous a').attr('p', parseInt(currentpage));
                    loadData(pageClick);
                }

//                if ($('.previous a').attr('p') < $('.first a').attr('p')) //Estem en el primer
//                {
//                    $('li.previous').addClass('disabled');
//                    e.preventDefault();
//                }


                break;

            case $(this).parent().hasClass('previous'):




                if ($('.previous a').attr('p') < $('.first a').attr('p'))
                {
                    $('li.previous').addClass('disabled');
                    e.preventDefault();
                }
                else
                {
                    var $prev, $selected = $(".active");

                    $prev = $selected.prev('li').length ? $selected.prev('li') : $last;
                    $selected.removeClass("active");
                    $prev.addClass('active');

                    $('.next a').attr('p', parseInt(currentpage));
                    $('.previous a').attr('p', parseInt(currentpage) - 2);
                    loadData(pageClick);
                }




                break;
            default:

                $(this).parent().parent().find('li').removeClass('active');
                $(this).parent().parent().find('li').removeClass('disabled');
                $(this).parent().addClass('active');


                $('.next a').attr('p', parseInt(pageClick) + 1);
                $('.previous a').attr('p', parseInt(pageClick) - 1);
                if ($('.next a').attr('p') > $('.last a').attr('p'))
                {
                    $('li.next').addClass('disabled');
                    e.preventDefault();
                }

                if ($('.previous a').attr('p') < $('.first a').attr('p'))
                {
                    $('li.previous').addClass('disabled');
                    e.preventDefault();
                }

                loadData(pageClick);
                break;
        }


//        if ($(this).parent().hasClass('next'))//estem en els paginadors
//        {
//
//            //alert('next');
//            if (currentpage == pageClick) //estem al final
//            {
//                $('li.next').addClass('disabled');
//
//            }
//            else
//            {
//
//
//
//                if ($('.last a').attr('p') < $('.next a').attr('p'))
//                {
//                    $('li.next').addClass('disabled');
//                }
//                else
//                {
//                    $(this).parent().parent().find('li').removeClass('disabled');
//
//
//                    var $next,
//                            $selected = $(".active");
//                    // get the selected item
//                    // If next li is empty , get the first
//                    $next = $selected.next('li').length ? $selected.next('li') : $first;
//                    $selected.removeClass("active");
//                    $next.addClass('active');
//
//                    $('.next a').attr('p', parseInt(currentpage) + 2);
//                    $('.previous a').attr('p', parseInt(currentpage));
//                    if ($('.last a').attr('p') < $('.next a').attr('p'))
//                    {
//                        $('li.next').addClass('disabled');
//                    }
//                    loadData(pageClick);
//                }
//
//
//            }
//        }
//        else if ($(this).parent().hasClass('previous'))
//        {
//            alert('previous');
//            if ($(this).parent().hasClass('disabled'))
//            {
//                e.preventDefault();
//            }
//            else
//            {
//
//                if (currentpage == pageClick)
//                {
//                    $('li.previous').addClass('disabled');
//
//                }
//                else
//                {
//
//                    var $prev, $selected = $(".active");
//
//                    $prev = $selected.prev('li').length ? $selected.prev('li') : $last;
//                    $selected.removeClass("active");
//                    $prev.addClass('active');
//
//
//                    // $(this).parent().parent().find('li').removeClass('active');
//                    $(this).parent().parent().find('li').removeClass('disabled');
//                    
//                    $('.previous a').attr('p', parseInt(currentpage) -1);
//                    
//                    if ($('.first a').attr('p') == $('.previous a').attr('p'))
//                    {
//                        $('li.previous').addClass('disabled');
//                    }
//                    loadData(pageClick);
//                }
//            }
//
//        }
//        else
//        {
//
//
//            $(this).parent().parent().find('li').removeClass('active');
//            $(this).parent().parent().find('li').removeClass('disabled');
//            $(this).parent().addClass('active');
//
//            if (pageClick == previouspage)
//            {
//                $('li.previous').addClass('disabled');
//
//            }
//
//            if (pageClick == nextpage)
//            {
//                $('li.next').addClass('disabled');
//            }
//
//            if (pageClick > currentpage)
//            {
//                $('.next a').attr('p', parseInt(pageClick) + 1);
//                $('.previous a').attr('p', parseInt(pageClick)-1);
//            }
//
//            if (pageClick < currentpage)
//            {
//                $('.next a').attr('p', parseInt(currentpage));
//                $('.previous a').attr('p', parseInt(pageClick)-1);
//            }
//            
//            if ($('.previous a').attr('p')==0)
//            {
//                $('li.previous').addClass('disabled');
//            }



//            loadData(pageClick);
//
//        }
    });



});


