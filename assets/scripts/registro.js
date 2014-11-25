$(document).ready(inicio);
function inicio()
{
    var paisSelec = $('#country').val();

    cargarPaises(paisSelec);
    $('#country').change(cargarCiudades);
    $('#city').change(comprobar_ciudades);
    $('#name').blur(function() { 
        comprobar_nombre();
    });
    $('#repassword').blur(function() { 
        var contrasenya=$('#password').val();
        var recontrasenya=$('#repassword').val();
        
        passwords_identicos(contrasenya,recontrasenya);
    });
    $('#address').blur(function() { 
        comprobar_direccion();
    });
    
    $('#postcode').blur(function() { 
        comprobar_cp();
    });


    $("#submit").click(function() {
        result_nombre = comprobar_nombre();
        result_pais = comprobar_paises();
        result_ciudad = comprobar_ciudades();
        result_email = comprobar_email_valido();//valida formato i que no estiga buit
        result_pass = comprobar_password_registro();
        result_direccion=comprobar_direccion();
        result_cp=comprobar_cp();

        //validem que no existisca el email
        $.post(base_url + "usuarios/ajax_comprobar_email", "email=" + $('#email').val(), function(datos)
        {
            
            result_repetido=true;
            if (datos == 0)           
            {
                $("#error-email").html('El email esta repetido');
                $("#error-email").show();
                result_repetido = false;
            }
        });
        
        
        if (result_nombre && result_pais && result_ciudad && result_email && result_pass && result_direccion && result_cp && result_repetido)
        {
            $("#registrar-form").submit();
        }
        else
        {
            return false;
        }
    });

}//fin de inicio
function comprobar_cp()
{
    var cp=$('#postcode').val();
    if (cp === "")
    {
        $("#error-cp").html("El código postal es obligatorio");
        $("#error-cp").show();
        return false;
    }
    else if(!isInt(cp)){
        $("#error-cp").html("El código postal debe de ser numérico.");
        $("#error-cp").show();
        return false;
    }
    else
    {
        $("#error-cp").hide();
        return true;
    }
}

function comprobar_direccion()
{
    if ($('#address').val() == "")
    {
        $("#error-address").html("La dirección no puede estar vacía.");
        $("#error-address").show();
        return false;
    }
    else {
        $("#error-address").hide();
        return true;
    }
}
function comprobar_nombre()
{
    if ($('#name').val() == "")
    {
        $("#error-name").html("El nombre no puede estar vacío.");
        $("#error-name").show();
        return false;
    }
    else {
        $("#error-name").hide();
        return true;
    }
}

function comprobar_password_registro()
{
    var password = $('#password').val();
    var repassword = $('#repassword').val();
    if (password == "")
    {
        $("#error-password").html("La contraseña no puede estar vacía.");
        $("#error-password").show();
        return false;
    }
    else
    {
        if (validar_formato_password(password))
        {
            return passwords_identicos(password, repassword);
        }
        else
        {
            $("#error-password").html("La contraseña debe tener entre 5 y 10 caracteres.");
            $("#error-password").show();
            return false;
        }
    }

}
function passwords_identicos(password, repassword)
{
    if (password !== repassword)
    {
        $("#error-repassword").html("Las contraseñas deben de ser idénticas.");
        $("#error-repassword").show();
        $("#error-password").hide();
        return false;
    }
    else
    {
        $("#error-password").hide();
        $("#error-repassword").hide();
        return true;
    }
}

function comprobar_paises()
{
    if ($('#country').val() == 0)
    {
        $("#error-country").html("El país no puede estar vacío.");
        $("#error-country").show();
        return false;
    }
    else {
        return true;
    }
}

function comprobar_ciudades()
{
    if ($('#city').val() == 0)
    {
        $("#error-city").html("La ciudad no puede estar vacía.");
        $("#error-city").show();
        return false;
    }
    else {
        $("#error-city").hide();
        return true;
    }
}

function comprobar_email_valido()
{
    var email = $('#email').val();
    var error = true;
    if (email == "")
    {
        $("#error-email").html("El email no puede estar vacío.");
        $("#error-email").show();
        error = false;
    }
    else if (!validarEmail(email))
    {
        $("#error-email").html("El email no tiene el formato correcto.");
        $("#error-email").show();
        error = false;
    }

    return error;

}
function comprobar_email()
{


    var email = $('#email').val();
    var error = "";
    if (email == "")
    {
        $("#error-email").html("El email no puede estar vacío.");
        $("#error-email").show();
        error = false;
    }
    else if (!validarEmail(email))
    {
        $("#error-email").html("El email no tiene el formato correcto.");
        $("#error-email").show();
        error = false;
    }
    else
    {
        $.post(base_url + "usuarios/ajax_comprobar_email", "email=" + email, function(datos)
        {
            if (datos == 1)
            {
                
                $("#error-email").hide();
                error = true;
                
            }
            else
            {
                $("#error-email").html('El email esta repetido');
                $("#error-email").show();
                error = false;
            }
        });
    }
    // alert("3- :"+error);
    return error;

}

function cargarPaises(pais)
{
//    var pais=$('#country').val();
    // alert(pais);
    $.post(base_url + "usuarios/ajax_get_paises", "pais=" + pais, function(respuesta)
    {
        $('#country').html(respuesta);
        paisSelec = $('#country').val();
    });
}

function cargarCiudades()
{
    var pais = $('#country').val();
    if (pais != 0)
    {
        $("#error-country").hide();
    }
    else
    {
        $("#error-country").html("El país no puede estar vacío.");
        $("#error-country").show();
    }
    $.post(base_url + "usuarios/ajax_get_ciudades", "pais=" + pais, function(respuesta)
    {
        $('#city').html(respuesta);
    });
}

