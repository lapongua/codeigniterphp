<?php
header("Content-Type: text/xml");
echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";?>
<rss version="2.0">
    <channel>
        <title><?php echo $titulo?></title>
        <link><?php echo $feed_url?></link>  
        <description><?php echo $description?></description>
        <language>es-es</language>
        <copyright>Copyright READ.ME SL</copyright>
        <ttl>15</ttl>
        <?php foreach ($posts as $post): 
            $autoreslibro="";
            foreach($post->autores as $autor)
            {
                $autoreslibro.=$autor->nombre.',';
            }

            $autoreslibro=  rtrim($autoreslibro,',');
            ?>
            <item>        
                <title><![CDATA[<?php echo $post->titulo;?>]]></title>
                <link><![CDATA[<?php echo $feed_url.''.$post->id;?>]]></link>
                <description>
                    <![CDATA[
                        Autor:<?php echo $autoreslibro."<br/>"; ?>
                        Editorial: <?php echo $post->editorial."<br/>"; ?>
                        Precio: <?php echo $post->precio."<br/>"; ?>
                        <?php echo character_limiter($post->descripcion,100)."<br/>";?>
                        <img width="50" src="<?php echo base_url().''.$post->portada; ?>" alt="<?php echo $post->titulo;?>"/>
                    ]]>
                </description>
            </item>    
        <?php endforeach;?>
    </channel>
</rss>

