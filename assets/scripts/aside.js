$(document).ready(function() {
    cargarTweets();
    setInterval(function() {
        $('#mis-tweets').html('<h4>Cargando tweets...</h4>');
        cargarTweets();
    }, 60000);
});

function cargarTweets()
{
    $.get(
        base_url + "libros/mistweets_ajax",
        null,
        function(data) 
        {
            
            if (data.error === null || data.error === undefined || data.error === '') {
                var html = '';
                html += '<div class="tweet-header">';
                //html += '<img src="' + data.avatar + '" alt="Twitter Imagen Perfil">';
                html += '<h3> Últimos Tweets <a href="http://www.twitter.com/' + data.twitter_name + '" target="_blank">@' + data.twitter_name + '</a></h3>'; 
                html += '</div>';
                html += '<div class="last-tweets">';
                          
                if (data.tweets.length > 0) {
                   // html+='hay:'+data.tweets.length+' tweets';
                    for (i = 0; i < data.tweets.length; i++) {
                        if(i<=2)
                        {
                        var date = new Date(data.tweets[i].created_at);
                        
                        html += '<div class="tweet-record">';
                        html += '<p>' + data.tweets[i].text+'. ';
                        html += '<span class="tweet-date">Publicado el ';
                        
                        var day = date.getDate();
                        var month = parseInt(date.getMonth()) + 1;
                        var year=date.getFullYear();
                        var hora=date.getHours();
                        var minutos=date.getMinutes();
                        var segundos=date.getSeconds();
                        html+=day+'/'+month+'/'+year+' a las '+hora+':'+minutos+':'+segundos+'</span></p>';
                         

                        html += '</div>';
                    }
                    }
                } else {
                    html += '<p>No hay tweets</p>';
                }
                html += '</div>';
                $('#mis-tweets').html(html);
            } else {
                var html = '';
                html += '<div class="last-tweets">';
                html += '<h4>&Uacute;ltimos tweets</h4>';  
                html += '<p>' + data.error + '</p>';
                html += '</div>';
                $('#mis-tweets').html(html);
            }
        },
        'json'
    );
}

