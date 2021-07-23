//funciones javascript y validaciones
$(document).ready(function () {
	$(".select2").select2();
});

function Limpiar() {
	swal({
		text: "\u00BFDesea Limpiar la p\u00E1gina?, si a\u00FAn no a grabado perdera los datos escritos...",
		icon: "info",
		buttons: {
			cancel: "Cancelar",
			ok: { text: "Aceptar", value: true, },
		}
	}).then((value) => {
		switch (value) {
			case true:
				window.location.reload();
				break;
			default:
				return;
		}
	});
}

function Submit() {
	myform = document.forms.f1;
	myform.submit();
}

function responder(revision, lista, pregunta, respuesta) {
	/////////// POST /////////
	var http = new FormData();
	http.append("request", "responder");
	http.append("revision", revision);
	http.append("lista", lista);
	http.append("pregunta", pregunta);
	http.append("respuesta", respuesta);
	var request = new XMLHttpRequest();
	request.open("POST", "ajax_fns_revision.php");
	request.send(http);
	request.onreadystatechange = function () {
		//console.log( request );
		if (request.readyState != 4) return;
		if (request.status === 200) {
			resultado = JSON.parse(request.responseText);
			if (resultado.status !== true) {
				//console.log( resultado.sql );
				//swal("Informaci\u00F3n", resultado.message, "info");ss
				cerrar();
			}
		}
	};
}

function cerrarRevision() {
	revision = document.getElementById("revision");
	observacion = document.getElementById("observacion");
	if (revision.value != "") {
		/////////// POST /////////
		var http = new FormData();
		http.append("request", "cerrar");
		http.append("codigo", revision.value);
		http.append("observacion", observacion.value);
		var request = new XMLHttpRequest();
		request.open("POST", "ajax_fns_revision.php");
		request.send(http);
		request.onreadystatechange = function () {
			//console.log( request );
			if (request.readyState != 4) return;
			if (request.status === 200) {
				resultado = JSON.parse(request.responseText);
				if (resultado.status !== true) {
					//console.log( resultado.sql );
					//swal("Informaci\u00F3n", resultado.message, "info");
					cerrar();
					return;
				}
				swal("Excelente!", "Checklist cerrada satisfactoriamente...", "success").then((value) => {
					window.location.href = "FRMejecutar.php"
				});
			}
		};
	}
}

function addFirma() {
	swal("Opci\u00F3n NO v\u00E1lida para la web", "Esta opci\u00F3n esta disponible \u00FAnicamente en la aplicaci\u00F3n m\u00F3vil (iOs o Android)", "info");
}

function FotoJs() {
	inpfile = document.getElementById("foto");
	inpfile.click();
}


async function subirImagen() {
	revision = document.getElementById("revision");
	foto = document.getElementById("foto");
	return new Promise((resolve, reject) => {
		/////////// POST /////////
		var http = new FormData();
		http.append("revision", revision.value);
		http.append("foto", foto.files[0]);
		//console.log(foto.files[0])
		var request = new XMLHttpRequest();
		request.open("POST", "EXEcarga_foto.php");
		request.onload = () => {
			if (request.status >= 200 && request.status < 300) {
				//console.log(request.response);
				resolve(JSON.parse(request.response));
			} else {
				reject(request.statusText);
			}
		};
		request.onerror = () => reject(request.statusText);
		request.send(http);
	});
}

async function cargarFoto() {
	foto = document.getElementById("foto");
	revision = document.getElementById("revision");
	if (revision.value !== "" && foto.value !== "") {
		extension = comprueba_extension(foto.value,1);
		if (extension === 1) {
			/////////// POST /////////
			var boton = document.getElementById("btn-foto");
			var contenedor = document.getElementById("div-imagen");
			loadingBtn(boton);
			loadingDiv(contenedor);
			let resultImg = await subirImagen();
			if (resultImg.status) {
				deloadingBtn(boton, '<i class="fa fa-camera"></i> Agregar Imagen');
				deloadingDiv(contenedor, resultImg.imagen);
			} else {
				swal("Error", resultImg.message, "error").then((value) => {
					console.log(value);
					window.location.reload();
				});
				return;
			}
		} else {
			swal("Alto!", "Este archivo no es extencion .jpg \u00F3 .png", "error");
		}
	} else {
		swal("Error", "Uno de los parametros est\u00E1 vacio, por favor refreseque e intente de nuevo...", "error");
	}
}

function verFirma(codigo) {
	cerrar();
	//Realiza una peticion de contenido a la contenido.php
	$.post("../promts/checklist/firma.php", { codigo: codigo }, function (data) {
		// Ponemos la respuesta de nuestro script en el DIV recargado
		$("#Pcontainer").html(data);
	});
	abrirModal();
}

function verFotos(codigo) {
	cerrar();
	//Realiza una peticion de contenido a la contenido.php
	$.post("../promts/checklist/foto.php", { codigo: codigo }, function (data) {
		// Ponemos la respuesta de nuestro script en el DIV recargado
		$("#Pcontainer").html(data);
	});
	abrirModal();
}


function cambiarTipo(tipo) {
	document.getElementById('tipo').value = tipo;
	Submit();
}
