//funciones javascript y validaciones
$(document).ready(function () {
	$(".select2").select2();
});

function Submit() {
	myform = document.forms.f1;
	myform.submit();
}

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

function printTable(codigo, indicador, departamento, clasificacion, categoria) {
	contenedor = document.getElementById("result");
	loadingCogs(contenedor);
	/////////// POST /////////
	var http = new FormData();
	http.append("request", "tabla");
	http.append("codigo", codigo);
	http.append("indicador", indicador);
	http.append("departamento", departamento);
	http.append("clasificacion", clasificacion);
	http.append("categoria", categoria);
	var request = new XMLHttpRequest();
	request.open("POST", "ajax_fns_ejecucion.php");
	request.send(http);
	request.onreadystatechange = function () {
		//console.log( request );
		if (request.readyState != 4) return;
		if (request.status === 200) {
			//console.log( request.responseText );
			resultado = JSON.parse(request.responseText);
			if (resultado.status !== true) {
				//console.log( resultado );
				contenedor.innerHTML = '...';
				console.log(resultado.message);
				return;
			}
			//tabla
			var data = resultado.tabla;
			contenedor.innerHTML = data;
			$('#tabla').DataTable({
				pageLength: 50,
				responsive: true,
				dom: '<"html5buttons"B>lTfgitp',
				buttons: [{
					extend: 'copy'
				},
				{
					extend: 'csv'
				},
				{
					extend: 'excel',
					title: 'Tabla de Ejecucion'
				},
				{
					extend: 'pdf',
					title: 'Tabla de Ejecucion'
				},
				{
					extend: 'print',
					customize: function (win) {
						$(win.document.body).addClass('white-bg');
						$(win.document.body).css('font-size', '10px');
						$(win.document.body).find('table')
							.addClass('compact')
							.css('font-size', 'inherit');
					},
					title: 'Tabla de Ejecucion'
				}
				]
			});
		}
	};
}

function Submit() {
	myform = document.forms.f1;
	myform.submit();
}

function cerrarRevision() {
	swal({
		text: "\u00BFDesea finalizar la toma de datos?, verifique bien sus datos y evidencias...",
		icon: "info",
		buttons: {
			cancel: "Cancelar",
			ok: { text: "Aceptar", value: true, },
		}
	}).then((value) => {
		switch (value) {
			case true:
				cambiarSituacion(2);
				break;
			default:
				return;
		}
	});
}

function cambiarSituacion(situacion) {
	codigo = document.getElementById('revision');
	lectura = document.getElementById('lectura');
	evidencia = document.getElementById('evidencia').value;

	if (lectura.value !== "" && codigo.value !== "" && evidencia) {
		/////////// POST /////////
		var boton = document.getElementById("btn-grabar");
		loadingBtn(boton);
		var http = new FormData();
		http.append("request", "situacion");
		http.append("codigo", codigo.value);
		http.append("situacion", situacion);var request = new XMLHttpRequest();
		request.open("POST", "ajax_fns_ejecucion.php");
		request.send(http);
		request.onreadystatechange = function () {
			console.log(request);
			if (request.readyState != 4) return;
			if (request.status === 200) {
				resultado = JSON.parse(request.responseText);
				if (resultado.status !== true) {
					swal("Error", resultado.message, "error").then((value) => {
						deloadingBtn(boton, '<i class="fa fa-save"></i> Grabar');
					});
					return;
				}
				//console.log( resultado );
				swal("Excelente!", resultado.message, "success").then((value) => {
					window.location.href = "FRManotacion.php";
				});
			}
		};
	} else {
		if (lectura.value === "") {
			swal("Ohoo!", "Debe llenar los Campos Obligatorios...", "error");
			lectura.classList.add("is-invalid");
		} else {
			lectura.classList.remove("is-invalid");
		}
		if (!evidencia) {
			swal("Ohoo!", "Debe subir al menos una evidencia...", "error");
		} 
	}
}

function modificar(elemento, campo) {
	codigo = document.getElementById("revision");
	var http = new FormData();
	http.append("request", "modificar");
	http.append("codigo", codigo.value);
	http.append("campo", campo);
	http.append("valor", elemento.value);
	var request = new XMLHttpRequest();
	request.open("POST", "ajax_fns_ejecucion.php");
	request.send(http);
	request.onreadystatechange = function () {
		//console.log(request.readyState);
		if (request.readyState != 4) return;
		if (request.status === 200) {
			console.log(request.responseText);
			resultado = JSON.parse(request.responseText);
			//console.log(resultado);
			if (resultado.status !== true) {
				console.log(resultado.message);
				return;
			}
			console.log(resultado.message);
		}
	};
}
/////////////////////////////////////// Archivos //////////////////////////////
function openInput(id) {
	inpfile = document.getElementById(id);
	inpfile.click();
}

function loadingGif(posicion) {
    document.getElementById("archivo" + posicion).innerHTML = '<img src="../../CONFIG/img/loading.gif" alt="...">';
}

async function upload(archivo, tipo) {
	codigo = document.getElementById("revision");
    loadingGif(tipo); //coloca un gif cargando en la imagen
	//--
	var arrpromises = new Array();
	if (codigo.value !== "") {
		if (archivo.files.length > 0) {
			valida = comprueba_extension(archivo.files[0].name,tipo);
			if (valida !== 1) {
				swal("Ohoo!", "La extension de este archivo no es valida....", "error").then((value) => {
					console.log(value);
				});
				return;
			}
			arrpromises[0] = await new Promise((resolve, reject) => {
				/////////// POST /////////
				let httpArchivo = new FormData();
				httpArchivo.append("nombre", archivo.files[0].name);
				httpArchivo.append("codigo", codigo.value);
				httpArchivo.append("archivo", archivo.files[0]);
				httpArchivo.append("posicion", tipo); // en este caso la posicion es la misma que el tipo		
				let requestArchivo = new XMLHttpRequest();
				requestArchivo.open("POST", "ajax_cargar_archivo.php");
				requestArchivo.onload = () => {
					if (requestArchivo.status >= 200 && requestArchivo.status < 300) {
						//console.log(requestArchivo);
						devuelve = JSON.parse(requestArchivo.response);
						if (devuelve.status === true) {
							resolve(devuelve.message);
						} else {
							reject(devuelve.message);
						}
					} else {
						//console.log( JSON.parse(requestArchivo.response) );
						reject('No se pudo conectar al servidor para realizar la transacci\u00F3n...');
					}
				};
				requestArchivo.onerror = () => reject(requestArchivo.statusText);
				requestArchivo.send(httpArchivo);
			}).catch(e => {
				console.log(e);
			});}await Promise.all(arrpromises).then(values => {
			//console.log(values);
			swal("Excelente!", "Archivo subido satisfactoriamente...", "success").then((value) => {
				window.location.reload();
			});
		}, reason => {
			//console.log(reason);
			swal("Error", "Error en la trasaccion ...", "error").then((value) => {
				cerrar();
			});
		}).catch(e => {
			console.log(e);
		});

	} else {
		swal("Ohoo!", "Debe llenar los Campos Obligatorios...", "error").then((value) => {
			window.location.reload();
		});
	}
}
////////////////////////////// Prompts ///////////////////////////////
function verRevision(codigo) {
    cerrar();
    //Realiza una peticion de contenido a la contenido.php
    $.post("../promts/indicadores/revision.php", { codigo: codigo }, function (data) {
        // Ponemos la respuesta de nuestro script en el DIV recargado
        $("#Pcontainer").html(data);
    });
    abrirModal();
}