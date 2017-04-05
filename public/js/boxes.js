/**
* Function to validate email and password to login
* 
* @returns {Boolean} false if not email or password is blank
*/
function validateLogin() {
	$("#loginErrorMsg").addClass("hide");
	$("#username").parent().removeClass("has-error");
	$("#password").parent().removeClass("has-error");
	var re = /[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/;
	if (!re.test($("#username").val())) {
		state = false;
		$("#loginErrorMsg").removeClass("hide bg-danger");
		$("#loginErrorMsg").addClass("bg-warning box-shadow");
		$("#username").parent().addClass("has-error");
		$("#loginErrorMsg").html(
			"Por favor ingrese un correo electr&oacute;nico v&aacute;lido");
		return false;
	}
	if ($("#password").val() == '') {
		state = false;
		$("#loginErrorMsg").removeClass("hide bg-danger");
		$("#loginErrorMsg").addClass("bg-warning box-shadow");
		$("#password").parent().addClass("has-error");
		$("#loginErrorMsg").html("Por favor ingrese la contrase&ntilde;a");
		return false;
	}
	return true;
}
/**
* Function to redirect
* 
* @param action:
*            url de redireccion (/index/index)
* @param params:
*            vars in get format (dat1=xxx&dat2=yyy&dat3=zzz)
* @returns {Boolean}
*/
function redirect(action, params) {
	params = params.split('&');
	var form = $(document.createElement('form')).attr('action', action);
	form.attr('method', 'POST');
	$('body').append(form);
	for ( var i in params) {
		var tmp = params[i].split('=');
		var key = tmp[0], value = tmp[1];
		$(document.createElement('input')).attr('type', 'hidden').attr('name', key).attr('value', value).appendTo(form);
	}
	$(form).submit();
	return false;
};

$(document).ready(function() {
	var d = new Date();
	var year = d.getFullYear();
	year = year + 5;
	$(".dates").datepicker(
	{
        changeMonth: true,
        changeYear: true,
		yearRange : "1910:" + year,
		dateFormat : 'yy-mm-dd',
		dayNamesMin : [ "D", "L", "M", "Mi", "J", "V",
		"S" ],
		monthNamesShort : [ "Ene", "Feb", "Mar", "Abr",
		"May", "Jun", "Jul", "Ago", "Sep",
		"Oct", "Nov", "Dic" ],
		beforeShow : function() {
			$(".ui-datepicker").css('font-size', 13);
		}
	});
	$('[data-toggle="tooltip"]').tooltip();
	$(document).on('click','.redirect', function(e) {
		e.preventDefault();
        if (!$(this).hasClass("disabled")) {
            var dir = $(this).attr("dir");
            dir = dir.split('_');
            if ($(this).hasClass("var")) {
                redirect('/' + dir[0] + '/' + dir[1], 'id='
                    + $(this).attr('cod'));
            } else {
                redirect('/' + dir[0] + '/' + dir[1], '');
            }
        }
	});

var myLanguage = {
	errorTitle : 'Falla en proceso de formulario!',
	requiredFields : 'Debe ingresar todos los campos requeridos',
	badTime : 'Debe ingresar la hora correctamente',
	badEmail : 'Debe ingresar una direcci&oacute;n de correo v&aacute;lida',
	badTelephone : 'Debe ingresar un n&uacute;mero de tel&eacute;fono',
	badSecurityAnswer : 'Debe ingresar un valor para la respuesta a la pregunta de seguridad',
	badDate : 'Debe ingresar una fecha v&aacute;lida',
	tooLongStart : 'Debe ingresar un dato mas largo que ',
	tooLongEnd : ' caracteres',
	tooShortStart : 'Ha ingresado una respuesta mas corta que ',
	tooShortEnd : ' caractares',
	badLength : 'Debe ingresar un valor entre ',
	notConfirmed : 'Valores no pueden ser confirmados',
	badDomain : 'Dominio incorrecto',
	badUrl : 'Debe ingresar un URL v&aacute;lido',
	badCustomVal : 'Respuesta incorrecta',
	badInt : 'El valor ingresado no es correcto',
	badSecurityNumber : 'N&uacute;mero de seguro social es incorrecto',
	badUKVatAnswer : 'N&uacute;mero de UK VAT Incorrecto',
	badStrength : 'La contrase&ntilde;a no es suficientemente fuerte',
	badNumberOfSelectedOptionsStart : 'Debe seleccionar al menos ',
	badNumberOfSelectedOptionsEnd : ' opci&oacute;n',
	badAlphaNumeric : 'El campo debe contener solo caracteres alpha-n&uacute;mericos ',
	badAlphaNumericExtra : ' y ',
	wrongFileSize : 'El archivo que intenta cargar es demasiado grande',
	wrongFileType : 'El archivo que intenta cargar es de formato incorrecto',
	groupCheckedRangeStart : 'Por favor seleccione entre ',
	groupCheckedTooFewStart : 'Por favor seleccione al menos ',
	groupCheckedTooManyStart : 'Por favor seleccione un m&aacute;ximo de ',
	groupCheckedEnd : ' item(s)'
};