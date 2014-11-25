<?php   
    if($this->session->flashdata('RequiredLog')): ?>
        <p class="error"><?php echo $this->session->flashdata('RequiredLog'); ?></p>
    <?php endif;  ?>
        
<div class="new-users f-left">
    <h2>Nuevos clientes</h2>
    <p>Al registrarse en nuestra tienda, agilizará el proceso de compra, podrá añadir múltiples direcciones de envío, ver y hacer un seguimiento de sus pedidos, y mucho más.</p>
    <?php echo anchor('usuarios/addform', 'Crear cuenta', 'class="btn"'); ?>        
</div>


<div class="registered-users f-right">
    <h2>Clientes registrados</h2>
    <p>Si usted tiene una cuenta con nosotros, por favor acceda.</p>
   <?php   
    if($this->session->userdata('error')): ?>
        <p class="error"><?php echo $this->session->userdata('error'); ?></p>
        <?php $this->session->unset_userdata('error');?>
    <?php endif;  ?>
<div class="error-login error" style="display: none;"></div>
<?php

    echo form_open('usuarios/loginform', 'id="login-form"');
    ?>
        <div class="field">
            <label class="required" for="username">Dirección de email <em>*</em></label>
            <div class="input-box">
                <input type="email" title="Dirección de email" id="username" value="<?php echo set_value('username')?>" name="username">

            </div>
        </div>
        <div class="field">
            <label class="required" for="contrasenya">Contraseña <em>*</em></label>
            <div class="input-box">
                <input type="password" title="Contraseña" id="contrasenya" name="contrasenya" value="<?php echo set_value('contrasenya')?>">
            </div>
        </div>
    

    <p class="required">* Campos Obligatorios</p>
    <div class="buttons-set">
        <?php echo anchor('#', '¿Ha olvidado su contraseña?','class="f-left"')?>
        
        <input type="submit" id="loggin" class="btn f-right" name="send" value="Acceder" />
    </div>
    <?php
    echo form_close();
    ?>

</div>









