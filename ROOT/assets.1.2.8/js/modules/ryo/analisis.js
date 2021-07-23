$(document).ready(function() {
   
    $(".select2").select2();
});
function Submit() {
	myform = document.forms.f1;
	myform.submit();
}

function updateCondicionOportunidad() {
	viabilidad = document.getElementById("viabilidad").value;
	rentabilidad = document.getElementById("rentabilidad").value;
	document.getElementById("condicion").value = get_condicion_oportunidad(viabilidad * rentabilidad);
}
function updateCondicion() {
	probabilidad = document.getElementById("probabilidad").value;
	impacto = document.getElementById("impacto").value;
	document.getElementById("condicion").value = get_condicion(probabilidad * impacto);
}
function get_condicion_oportunidad(condicion) {
    if(condicion <= 5) return "Trivial";
    if(condicion > 5 && condicion <= 10) return "Viable";
    if(condicion > 10 && condicion <= 15) return "Factible";
    if(condicion > 15) return "Prioritario";
}
function get_condicion(condicion)
{
	if (condicion <= 5) return "Riesgo Minimo";
	if (condicion > 5 && condicion <= 10) return "Riesgo Bajo";
	if (condicion > 10 && condicion <= 15) return "Riesgo Medio";
	if (condicion > 15) return "Riesgo Alto";
}
function analizar(codigo) {
	cerrar();
	//Realiza una peticion de contenido a la contenido.php
	$.post("../promts/risk/analizar.php", { codigo: codigo }, function (data) {
		// Ponemos la respuesta de nuestro script en el DIV recargado
		$("#Pcontainer").html(data);
	});
	abrirModal();
    setTimeout(function () {
		$(".select2").select2();
	}, 250);
}

function analizarOportunidad(codigo, proceso) {
	cerrar();
	//Realiza una peticion de contenido a la contenido.php
	$.post("../promts/risk/analizar_oportunidad.php", { codigo: codigo, proceso: proceso }, function (data) {
		// Ponemos la respuesta de nuestro script en el DIV recargado
		$("#Pcontainer").html(data);
	});
	abrirModal();
    setTimeout(function () {
		$(".select2").select2();
	}, 250);
}