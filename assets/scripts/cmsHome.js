$(document).ready(function() {

    /*
     * Validar comentarios o viceversa
     */
    $('.comentarioValidado').click(function() {
        var $this = $(this);
        var valor = $this.html();
        var ids = $(this).parent().find('.idcomentario').html();
        $.ajax({
            url: base_url + "/actions/comentarios_validar.php",
            type: "POST",
            data: "id=" + ids,
            success: function(data)
            {
                if (data == "1") {
                    $this.addClass("valido");
                    $this.removeClass("noValido");
                    //$this.html("1");
                    $this.html('<span class="fa fa-check-square-o btn-lg"></span>');
                }
                else {
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
    
    
     $(".clickableRow").click(function() {
        window.document.location = $(this).data("url");
    });


    /*
     * cargar en ajax el chart
     */
    
    $.ajax({
        url: base_url + "/actions/pedidos_estadistica.php",
        type: "POST",
        dataType: "json",
        success: function(data)
        {
            var pagados = data.pagados;
            var total = data.total;
            var porcentajePag, porcentajeNoPag;
            porcentajePag = parseFloat(pagados * 100 / total);
            porcentajeNoPag = parseFloat(100 - porcentajePag);
            
            porcentajePag=parseFloat(porcentajePag.toFixed(2));
            porcentajeNoPag=parseFloat(porcentajeNoPag.toFixed(2));
            
            
            
            var datas = [
                ['Pagados', porcentajePag],
                ['No pagados', porcentajeNoPag]
            ];

            RenderPieChart('container-chart', datas);
        }
    });


    function RenderPieChart(elementId, dataList) {
        new Highcharts.Chart({
            chart: {
                renderTo: elementId,
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            }, title: {
                text: 'Estado de los Pedidos'
            },
            tooltip: {
                formatter: function() {
                    return '<b>' + this.point.name + '</b>: ' + this.percentage + ' %';
                }
                
                
            },  
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        color: '#000000',
                        connectorColor: '#000000',
                        formatter: function() {
                            return '<b>' + this.point.name + '</b>: ' + this.percentage + ' %';
                        }
                    },
                    showInLegend: true
                }
            },
            colors:['#00C73D', '#FF0000'],
            series: [{
                    type: 'pie',
                    name: 'Browser share',
                    data: dataList
                }]
        });
    }
    ;




});