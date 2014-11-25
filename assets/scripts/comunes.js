function isInt(n) {
    return n % 1 == 0;
}

function validar_formato_password(password)
{
    var regex = /^[a-z0-9_-]{5,10}$/;
    return regex.test(password);
}

/*Valida el email con expresiones regulares*/
function validarEmail(email)
{
    var regex = /^[a-z0-9]+([\.]?[a-z0-9_-]+)*@[a-z0-9]+([\.-]+[a-z0-9]+)*\.[a-z]{2,}$/;
    return regex.test(email);
}


