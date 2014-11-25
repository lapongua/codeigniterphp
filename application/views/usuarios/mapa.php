<h2>Mapa de usuarios registrados</h2>
<div id="mapa" style="width: 960px; height: 450px"></div> 
<script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>
        <script type="text/javascript">
            function ponerPunto(direccion,rol)
            {
                geocoder.geocode({'address': direccion}, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        //alert(direccion+"-"+rol);
                        crearPunto(results[0].geometry.location, direccion,rol); //creamos el marcador y pasamos informacion del mismo
                    } else {
                        alert(direccion + " no encontrado, error:" + status);
                    }
                });
            }

            function crearPunto(posicion, info,rol)
            { //Creamos un punto: 
                var punto="";
                if(rol=="comprador")
                {
                    punto = new google.maps.Marker({position: posicion, map: mapa, icon: base_url +'assets/styles/images/maps/users.png'});
                }
                else
                {
                    punto = new google.maps.Marker({position: posicion, map: mapa, icon: base_url +'assets/styles/images/maps/admin.png'});
                }
               
                //Informacion del marcador
                var infoWin = new google.maps.InfoWindow({content: 'direccion: ' + info});
                //Asociar el infoWin al click sobre el marcador
                google.maps.event.addListener(punto, 'click', function() {
                    infoWin.open(mapa, punto);
                });
            }

//            function leerPuntos(response)
//            {
//               // alert(response);
//               //var xmlDoc = $.parseXML(data);
//               var $xml = $(response);
//               $xml.find('usuario').each(function() {
//                 alert( $(this).text() );
//               });
////                puntos = response.getElementsByTagName("usuario");
////
////                for (var i = 0; i < puntos.length; i++)
////                {
////                    if (puntos[i].getAttribute("direccion") != null) {
////                        direccion = puntos[i].getAttribute("direccion");
////                        rol = puntos[i].getAttribute("rol");
////                        ponerPunto(direccion,rol);
////                    }
////                }
//            }

            //creamos un mapa y lo asociamos al div 'mapa'
            var mapa = new google.maps.Map(document.getElementById("mapa"));
            var miPosicion = new google.maps.LatLng(40.414567, -3.695673); //coordenadas, en este caso Alicante
            mapa.setCenter(miPosicion);
            mapa.setZoom(6); //zoom
            mapa.setMapTypeId(google.maps.MapTypeId.ROADMAP); //poner el tipo ROADMAP, SATELLITE ... 
            var geocoder = new google.maps.Geocoder();

            // Leer del XML los puntos a mostrar
          //  $.get(base_url + 'usuarios/mapa_ajax', null, leerPuntos,xml);
           $(document).ready(function(){
               //$.post(base_url + 'usuarios/mapa_ajax', null, leerPuntos);
              
                $.ajax({
                    type: 'GET',
                    url: base_url + 'usuarios/mapa_ajax',
                    dataType: 'xml',
                    success: function(xmlDoc) {
                        var $xml = $(xmlDoc);
                        $xml.find('usuario').each(function() {
                            var direccion= $(this).attr('direccion');
                            var rol=$(this).attr('rol');                  
                            ponerPunto(direccion,rol);
                        });
                    }
                });
                
           });
           
           

        </script>
