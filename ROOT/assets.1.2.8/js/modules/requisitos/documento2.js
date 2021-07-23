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

function Deshabilitar(codigo) {
	swal({
		text: "\u00BFDesea quitar este registro del listado?, no prodr\u00E1 ser usado despu\u00E9s...",
		icon: "warning",
		buttons: {
			cancel: "Cancelar",
			ok: { text: "Aceptar", value: true },
		}
	}).then((value) => {
		switch (value) {
			case true:
				cambioSituacion(codigo, 0);
				break;
			default:
				return;
		}
	});
}


function printTable() {
	contenedor = document.getElementById("result");

	loadingCogs(contenedor);
	/////////// POST /////////
	var http = new FormData();
	http.append("request", "tabla_documentos");
	var request = new XMLHttpRequest();
	request.open("POST", "ajax_fns_documentos2.php");
	request.send(http);
	request.onreadystatechange = function () {
		//console.log(request);
		if (request.readyState != 4) return;
		if (request.status === 200) {
		//	console.log( request.responseText );
			resultado = JSON.parse(request.responseText);
			if (resultado.status !== true) {
				//console.log(resultado);
				contenedor.innerHTML = '...';
				//swal("Informaci\u00F3n", resultado.message, "info");
				return;
			}
			//tabla
			var data = resultado.tabla;
			contenedor.innerHTML = data;
			$('#tabla').DataTable({
				pageLength: 50,
				responsive: true,
				dom: '<"html5buttons"B>lTfgitp',
				buttons: [
					{ extend: 'copy' },
					{ extend: 'csv' },
					{ extend: 'excel', title: 'Tabla de Actividades' },
					{ extend: 'pdf', title: 'Tabla de Actividades' },
					{
						extend: 'print',
						customize: function (win) {
							$(win.document.body).addClass('white-bg');
							$(win.document.body).css('font-size', '10px');
							$(win.document.body).find('table')
								.addClass('compact')
								.css('font-size', 'inherit');
						}, title: 'Tabla de Actividades'
					}
				]
			});
		}
	};
}

function Seleccionar(codigo) {
	contenedor = document.getElementById("result");
	loadingCogs(contenedor);
	/////////// POST /////////
	var http = new FormData();
	http.append("request", "get");
	http.append("codigo", codigo);
	var request = new XMLHttpRequest();
	request.open("POST", "ajax_fns_documentos2.php");
	request.send(http);
	request.onreadystatechange = function () {
		//console.log( request );
		if (request.readyState != 4) return;
		if (request.status === 200) {
			resultado = JSON.parse(request.responseText);
			if (resultado.status !== true) {
				return;
			}
			var data = resultado.data;
			//console.log(data);
			//set
			document.getElementById("codigo").value = data.codigo;
			document.getElementById('titulo').value = data.titulo;
			document.getElementById('tipo').value = data.tipo;
			document.getElementById('entidad').value = data.entidad;
		//	setSelect2("sistema", data.sistema);
			document.getElementById('vigencia').value = data.vigencia;
			situacion = document.getElementById('situacion').value = Number(data.situacion)+1;
			//AutoGrowTextArea(descripcion);
			//tabla
			console.log("situacion= "+situacion);
			var tabla = resultado.tabla;
			contenedor.innerHTML = tabla;
			$('#tabla').DataTable({
				pageLength: 50,
				responsive: true
			});
			//botones
			document.getElementById("btn-grabar").className = "btn btn-primary btn-sm hidden";
			document.getElementById("btn-modificar").className = "btn btn-primary btn-sm";
		}
	};
}

function Grabar() {
	contenedor = document.getElementById("result");
	info = contenedor.innerHTML;
	loadingCogs(contenedor);
	//--
	//codigo = document.getElementById('codigo');
	titulo = document.getElementById('titulo');
	tipo = document.getElementById('tipo');
	entidad = document.getElementById('entidad');
//	sistema = document.getElementById('sistema');
	vigencia = document.getElementById('vigencia');

	/*console.log('codigo =' + codigo.value + 'titulo = ' + titulo.value + 'tipo = ' + tipo.value + 'entidad = ' + entidad.value + 'sistema = ' + 'vigencia = ' + vigencia.value );*/

	if (titulo.value !== "" && tipo.value !== "" && entidad.value !== ""  && vigencia.value !== "") {
		/////////// POST /////////
		var boton = document.getElementById("btn-grabar");
		loadingBtn(boton);
		var http = new FormData();
		http.append("request", "grabar_documento");
	//	http.append("codigo",codigo.value);
		http.append("titulo", titulo.value);
		http.append("tipo", tipo.value);
		http.append("entidad", entidad.value);
	//	http.append("sistema", sistema.value);
		http.append("vigencia", vigencia.value);
		var request = new XMLHttpRequest();
		request.open("POST", "ajax_fns_documentos2.php");
		request.send(http);
		request.onreadystatechange = function () {
			// console.log(request.responseText);
			if (request.readyState != 4) return;
			if (request.status === 200) {
				resultado = JSON.parse(request.responseText);
				//console.log(resultado);
				if (resultado.status !== true) {
					contenedor.innerHTML = info;
					swal("Error", resultado.message, "error").then((value) => { deloadingBtn(boton, '<i class="fa fa-save"></i> Grabar'); });
					return;
				}
				//	console.log( resultado );
				swal("Excelente!", resultado.message, "success").then((value) => {
					deloadingBtn(boton, '<i class="fa fa-save"></i> Grabar');
					tipo.value = "";
					entidad.value = "";
					titulo.value = "";
					vigencia.value = "";
				//tabla
				//	console.log(resultado.data);
					var tabla = resultado.data;
					contenedor.innerHTML = tabla;
					$('#tabla').DataTable({
						pageLength: 50,
						responsive: true
					});
				});
			}
		};
	} else {
		contenedor.innerHTML = info;
		swal("Ohoo!", "Debe llenar los Campos Obligatorios...", "error");
	}
	if (titulo.value === "") {
		titulo.classList.add("is-invalid");
	} else {
		titulo.classList.remove("is-invalid");
	}
	if (tipo.value === "") {
		tipo.classList.add("is-invalid");
	} else {
		tipo.classList.remove("is-invalid");
	}
	if (entidad.value === "") {
		entidad.classList.add("is-invalid");
	} else {
		entidad.classList.remove("is-invalid");
	}
/*	if (sistema.value === "") {
		sistema.parentNode.classList.add('has-error');
	} else {
		sistema.parentNode.classList.remove('has-error');
	}   */

	if (vigencia.value === "") {
		vigencia.classList.add("is-invalid");
	} else {
		vigencia.classList.remove("is-invalid");
	}
}

function Modificar() {
	contenedor = document.getElementById("result");
	info = contenedor.innerHTML;
	loadingCogs(contenedor);
	//--
	codigo = document.getElementById('codigo');
	titulo = document.getElementById('titulo');
	tipo = document.getElementById('tipo');
	entidad = document.getElementById('entidad');
	vigencia = document.getElementById('vigencia');
	situacion = document.getElementById('situacion');
	/*
	console.log('campos: codigo, titulo, tipo, entidad, vigencia, situacion = ' + codigo.value + ', ' + titulo.value + ', ' + tipo.value + ', ' + entidad.value + ', ' + vigencia.value + ', ' + situacion.value);*/

	if (codigo.value !== "" && titulo.value !== "" && tipo.value !== "" && entidad.value !== "" && vigencia.value !== "" && situacion.value!== "") {
		/////////// POST /////////
		var boton = document.getElementById("btn-modificar");
		loadingBtn(boton);
		var http = new FormData();
		http.append("request", "modificar_documento");
		http.append("codigo", codigo.value);
		http.append("titulo", titulo.value);
		http.append("tipo", tipo.value);
		http.append("entidad", entidad.value);
		//http.append("sistema", sistema.value);
		http.append("vigencia", vigencia.value);
		http.append("situacion", situacion.value);
		var request = new XMLHttpRequest();
		request.open("POST", "ajax_fns_documentos2.php");
		request.send(http);
		request.onreadystatechange = function () {
			// console.log(request.responseText);
			if (request.readyState != 4) return;
			if (request.status === 200) {
				resultado = JSON.parse(request.responseText);
				if (resultado.status !== true) {
					swal("Error", resultado.message, "error").then((value) => { deloadingBtn(boton, '<i class="fa fa-save"></i> Grabar'); });
					return;
				}
				//console.log( resultado );
				swal("Excelente!", resultado.message, "success").then((value) => {
					deloadingBtn(boton, '<i class="fa fa-save"></i> Modificar');
					tipo.value = "";
					entidad.value = "";
					titulo.value = "";
				//	setSelect2("sistema", "");
					vigencia.value = "";
					//tabla
					var tabla = resultado.data;
					contenedor.innerHTML = tabla;
					$('#tabla').DataTable({
						pageLength: 50,
						responsive: true
					});
					document.getElementById("btn-grabar").className = "btn btn-primary btn-sm";
					document.getElementById("btn-modificar").className = "btn btn-primary btn-sm hidden";
				});
			}
		};
	} else {
		contenedor.innerHTML = info;
		swal("Ohoo!", "Debe llenar los Campos Obligatorios...", "error");
	}
	if (codigo.value === "") {
		codigo.classList.add("is-invalid");
	} else {
		codigo.classList.remove("is-invalid");
	}
	if (titulo.value === "") {
		titulo.classList.add("is-invalid");
	} else {
		titulo.classList.remove("is-invalid");
	}
	if (tipo.value === "") {
		tipo.classList.add("is-invalid");
	} else {
		tipo.classList.remove("is-invalid");
	}
	if (entidad.value === "") {
		entidad.classList.add("is-invalid");
	} else {
		entidad.classList.remove("is-invalid");
	}
	if (vigencia.value === "") {
		vigencia.classList.add("is-invalid");
	} else {
		vigencia.classList.remove("is-invalid");
	}
	if (situacion.value === "") {
		situacion.classList.add("is-invalid");
	} else {
		situacion.classList.remove("is-invalid");
	}
}

function cambioSituacion(codigo, situacion) {
	contenedor = document.getElementById("result");
	loadingCogs(contenedor);
	/////////// POST /////////
	var http = new FormData();
	http.append("request", "situacion");
	http.append("codigo", codigo);
	http.append("situacion", situacion);
	var request = new XMLHttpRequest();
	request.open("POST", "ajax_fns_documentos2.php");
	request.send(http);
	request.onreadystatechange = function () {
		// console.log(request.responseText);
		if (request.readyState != 4) return;
		if (request.status === 200) {
			resultado = JSON.parse(request.responseText);
			if (resultado.status !== true) {
				//console.log( resultado.sql );
				//swal("Informaci\u00F3n", resultado.message, "info");
				return;
			}
			swal("Excelente!", "Registro eliminado satisfactorio!!!", "success").then((value) => {
				tipo.value = "";
				entidad.value = "";
				titulo.value = "";
				setSelect2("sistema", "");
				//tabla
				// console.log(resultado.data);
				var tabla = resultado.data;
				contenedor.innerHTML = tabla;
				$('#tabla').DataTable({
					pageLength: 50,
					responsive: true
				});
			});
		}
	};
}

