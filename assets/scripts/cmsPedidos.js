$(document).ready(function() {
    $("#searchclearpedido").click(function() {
        $(window).attr("location", base_url + "/pedidos.php");
    });

    $(".clickableRow").click(function() {
        window.document.location = $(this).data("url");
    });




    /*
     * cargar en ajax el chart
     */

    $.ajax({
        url: base_url + "/actions/pedidosmes_estadistica.php",
        type: "POST",
        dataType: "json",
        success: function(data)
        {
            if (data != null)
            {   
                var datas = new Array();
                for (var i = 0; i < 12; i++) datas[i] =0;
                var totalPagado=0, totalPedidosPagados=0;
                $.each(data, function(index, element) {   
                    totalPagado += parseInt(element['total']);
                    totalPedidosPagados += parseInt(element['filas']);
                    datas[element['mes'] - 1] = parseInt(element['filas']);
                });
                 
            }
            $("#TotalPagado").append(totalPagado.toFixed(2)+" €");
            $("#PedidosPagados").append(totalPedidosPagados);
            RenderColumnChart('chart-pedidos', datas);
        }
    });


    function RenderColumnChart(elementId, dataList) {
     
      //alert(dataList[1]);
        new Highcharts.Chart({
            chart: {
                renderTo: elementId,
                type: 'column'
            },
            title: {
                text: 'Estado de los Pedidos'
            },
            xAxis: {
                categories: [
                    'Ene',
                    'Feb',
                    'Mar',
                    'Abr',
                    'May',
                    'Jun',
                    'Jul',
                    'Ago',
                    'Sep',
                    'Oct',
                    'Nov',
                    'Dec'
                ]
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Cantidad'
                }
            },
            tooltip: {
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            colors: ['#00C73D', '#FF0000'],
            series: [{
                    type: 'column',
                    name: 'Total pedidos',
                    data: dataList
                }]
        });
    }
    ;

//    $('#chart-pedidos').highcharts({
//        chart: {
//            type: 'column'
//        },
//        title: {
//            text: 'Últimos pedidos'
//        },
//        subtitle: {
//            text: 'read.me'
//        },
//        xAxis: {
//            categories: [
//                'Ene',
//                'Feb',
//                'Mar',
//                'Abr',
//                'May',
//                'Jun',
//                'Jul',
//                'Ago',
//                'Sep',
//                'Oct',
//                'Nov',
//                'Dec'
//            ]
//        },
//        yAxis: {
//            min: 0,
//            title: {
//                text: 'Cantidad'
//            }
//        },
//        tooltip: {
//            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
//            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
//                    '<td style="padding:0"><b>{point.y:.1f} mm</b></td></tr>',
//            footerFormat: '</table>',
//            shared: true,
//            useHTML: true
//        },
//        plotOptions: {
//            column: {
//                pointPadding: 0.2,
//                borderWidth: 0
//            }
//        },
//        series: [{
//                name: 'Total Pedidos',
//                data: [49.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4]
//
//            }]
//    });


});