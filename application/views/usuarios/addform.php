<script type="text/javascript" src="<?php echo base_url()?>assets/scripts/registro.js"></script>
<h2>Formulario de registro de Clientes</h2>
<p>Todos los campos son obligatorios <em>*</em></p>
<?php
echo form_open('usuarios/addform', 'id="registrar-form"');
?>

<div class="field">
    <?php echo form_error('name')?>
    <div id="error-name" class="error" style="display: none;"></div>
    <label for="name">Nombre completo <em>*</em></label>
    <div class="input-box">
        <input type="text" placeholder="Nombre completo" id="name" value="<?php echo set_value('name')?>" name="name" onblur="comprobar_nombre()">
    </div>
</div>
<div class="field">
    <?php echo form_error('email')?>
    <div id="error-email" class="error" style="display: none;"></div>
    <label for="email">Email <em>*</em></label>
    <div class="input-box">
        <input type="email" placeholder="Correo electrónico" id="email" value="<?php echo set_value('email')?>" name="email" onblur="comprobar_email()">
    </div>
</div>
<div class="field">
    <?php echo form_error('password')?>
    <div id="error-password" class="error" style="display: none;"></div>
    <label for="password">Contraseña <em>*</em></label>
    <span id="error-password"></span>
    <div class="input-box">
        <input type="password" placeholder="Contraseña" id="password" value="" name="password" onblur="comprobar_password_registro()">  
    </div>
</div>
<div class="field">
    <?php echo form_error('repassword')?>
    <div id="error-repassword" class="error" style="display: none;"></div>
    <label for="repassword">Confirmar contraseña <em>*</em></label>
    <div class="input-box">
        <input type="password" placeholder="Confirmar contraseña" id="repassword" value="" name="repassword">
    </div>
</div>

<div class="field">
    <?php echo form_error('address')?>
    <div id="error-address" class="error" style="display: none;"></div>
    <label for="address">Dirección <em>*</em></label>
    <div class="input-box">
        <input type="text" placeholder="Dirección" id="address" value="<?php echo set_value('address')?>" name="address">
    </div>
</div>
<div class="field">
    <?php echo form_error('country')?>
    <div id="error-country" class="error" style="display: none;"></div>
    <label for="country">País <em>*</em></label>
    <div class="input-box">
        <select id="country" name="country">
           <option selected="selected" value="0">- Selecciona un país -</option>
            <?php
          /*  foreach ($paises as $pais)
            {
                $selected="";
                if($pais->id==set_value('country'))
                {

                    $selected="selected";
                }
                
                ?>
            <option value="<?php echo $pais->id?>" <?php echo $selected;?>><?php echo $pais->nombre?></option>
            <?php
            }*/
            ?>
        </select>
    </div>
</div>
<div class="field">
    <?php echo form_error('city')?>
    <div id="error-city" class="error" style="display: none;"></div>
    <label for="city">Ciudad <em>*</em></label>
    <div class="input-box">
        <select id="city" name="city">
            <option selected="selected" value="0">- Selecciona una ciudad -</option>   
            <?php
          /*  foreach ($ciudades as $ciudad)
            {
                $selected="";
                if($ciudad->id==set_value('city'))
                {

                    $selected="selected";
                }
                ?>
            <option value="<?php echo $ciudad->id?>" <?php echo $selected;?>><?php echo $ciudad->nombre?></option>
            <?php
            }*/
            ?>
        </select>
    </div>
</div>
<div class="field">
    <?php echo form_error('postcode')?>
    <div id="error-cp" class="error" style="display: none;"></div>
    <label for="postcode">Código postal <em>*</em></label>
    <div class="input-box">
        <input type="text" placeholder="Código postal" id="postcode" value="<?php echo set_value('postcode')?>" name="postcode">
    </div>
</div>

<input type="submit" id="submit" value="Enviar" name="submit" class="btn clearboth" />
    

<?php
echo form_close();


?>
<a href="javascript:history.go(-1)"><small>« </small>Volver atrás</a>
    


    