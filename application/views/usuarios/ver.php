<?php if ($this->session->flashdata('updateCorrect')): ?>
    <p class="successful"><?php echo $this->session->flashdata('updateCorrect'); ?></p>
    <?php //$this->session->unset_userdata('updateCorrect'); ?>
<?php endif; ?>
    
<?php
echo form_open('usuarios/ver', 'id="update-user-account" class="update-user"');
?>
<h2>Perfil de usuario</h2>
<table>
    <tr>
        <td><label for="name">Nombre completo <em>*</em></label></td>
        <td><?php echo form_error('name')?><input type="text" placeholder="Nombre completo" id="name" value="<?php echo set_value('name',$usuario->nombre)?>" name="name"></td>
    </tr>
    <tr><td><label for="email">Email <em>*</em></label></td>
        <td><?php echo form_error('email')?><input type="email" placeholder="Correo electrónico" id="email" value="<?php echo set_value('email',$usuario->email)?>" name="email"></td>
    </tr>
    <tr>
        <td><label for="address">Dirección <em>*</em></label></td>
        <td><?php echo form_error('address')?><input type="text" placeholder="Dirección" id="address" value="<?php echo set_value('address',$usuario->direccion)?>" name="address"></td>
    </tr>
    <tr>
        <td><label for="country">País <em>*</em></label></td>
        <td>
            <?php echo form_error('country')?>
            <select id="country" name="country">
                <option value="0">- Selecciona un país -</option>
                <?php
               
                foreach ($paises as $pais)
                {
                    $selected="";
                    $aux=set_value('country');
                    if($aux=="")
                    {
                        if($pais->id==$usuario->pais[0]->id)
                        {

                            $selected="selected";
                        }
                    }
                    else
                    {
                        if($pais->id==set_value('country'))
                        {

                            $selected="selected";
                        }
                    }
                    
                    ?>
                <option value="<?php echo $pais->id?>" <?php echo $selected;?>><?php echo $pais->nombre?></option>
                <?php
                }
                ?>
            </select>
        </td>
    </tr>
    <tr>
        <td><label for="city">Ciudad <em>*</em></label></td>
        <td>
            <?php echo form_error('city')?>
            <select id="city" name="city"> 
                <option value="0">- Selecciona una ciudad -</option>   
                <?php
                foreach ($ciudades as $ciudad)
                {
                    $selected="";
                    $auxi=set_value('city');
                    if($auxi=="")
                    {
                        if($ciudad->nombre==$usuario->ciudad)
                        {

                            $selected="selected";
                        }
                    }
                    else
                    {
                        if($ciudad->id==set_value('city'))
                        {

                            $selected="selected";
                        }
                    }
                    ?>
                <option value="<?php echo $ciudad->id?>" <?php echo $selected;?>><?php echo $ciudad->nombre?></option>
                <?php
                }
                ?>
            </select>
        </td>
    </tr>
    <tr>
        <td><label for="postcode">Código postal <em>*</em></label></td>
        <td><?php echo form_error('postcode')?>
    <input type="text" placeholder="Código postal" id="postcode" value="<?php echo set_value('postcode',$usuario->cp)?>" name="postcode">
        </td>
    </tr>
    <tr><td colspan="2"><h3>Cambiar la contraseña de la cuenta</h3></td></tr>
    <tr>
        <td><label for="password">Contraseña anterior <em>*</em></label></td>
        <td>
            <?php echo form_error('oldpassword')?>
            <input type="password" placeholder="Contraseña anterior" id="oldpassword" value="" name="oldpassword">
        </td>
    </tr>
    <tr>
        <td><label for="password">Contraseña <em>*</em></label></td>
        <td>
            <?php echo form_error('password')?>
            <input type="password" placeholder="Contraseña" id="password" value="" name="password">
        </td>
    </tr>
    <tr>
        <td><label for="repassword">Confirmar contraseña <em>*</em></label></td>
        <td>
            <?php echo form_error('repassword')?> 
            <input type="password" placeholder="Confirmar contraseña" id="repassword" value="" name="repassword">
        </td>
    </tr>
    <tr>
        <td colspan="2"><input type="submit" id="update-pass" class="btn" value="Actualizar" name="update-pass" /></td>
    </tr>
</table>

<?php
echo form_close();