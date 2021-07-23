$(document).ready(function() {
   
    $(".select2").select2();
});

function Submit() {
	myform = document.forms.f1;
	myform.submit();
}

function valorizar(codigo) {
	cerrar();
	//Realiza una peticion de contenido a la contenido.php
	$.post("../promts/risk/valorizar.php", { codigo: codigo }, function (data) {
		// Ponemos la respuesta de nuestro script en el DIV recargado
		$("#Pcontainer").html(data);
	});
	abrirModal();
    setTimeout(function () {
		$(".select2").select2();
	}, 250);
}

function valorizarOportunidad(codigo) {
	cerrar();
	//Realiza una peticion de contenido a la contenido.php
	$.post("../promts/risk/valorizar_oportunidad.php", { codigo: codigo }, function (data) {
		// Ponemos la respuesta de nuestro script en el DIV recargado
		$("#Pcontainer").html(data);
	});
	abrirModal();
    setTimeout(function () {
		$(".select2").select2();
	}, 250);
}