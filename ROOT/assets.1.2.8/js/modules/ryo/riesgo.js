function identificar(codigo, proceso) {
	cerrar();
	//Realiza una peticion de contenido a la contenido.php
	$.post("../promts/risk/identificar.php", { codigo: codigo, proceso: proceso }, function (data) {
		// Ponemos la respuesta de nuestro script en el DIV recargado
		$("#Pcontainer").html(data);
	});
	abrirModal();
    setTimeout(function () {
		$(".select2").select2();
	}, 250);
}


function update(elemento, campo) {
	codigo = document.getElementById("codigo");
	var http = new FormData();
	http.append("request", "update");
	http.append("codigo", codigo.value);
	http.append("campo", campo);
	http.append("valor", elemento.value);
	var request = new XMLHttpRequest();
	request.open("POST", "ajax_fns_ryo.php");
	request.send(http);
	request.onreadystatechange = function () {
		//console.log(request.readyState);
		if (request.readyState != 4) return;
		if (request.status === 200) {
			// console.log(request.responseText);
			resultado = JSON.parse(request.responseText);
			//console.log(resultado);
			if (resultado.status !== true) {
				console.log(resultado.message);
				return;
			}
			// console.log(resultado.message);
		}
	};
}