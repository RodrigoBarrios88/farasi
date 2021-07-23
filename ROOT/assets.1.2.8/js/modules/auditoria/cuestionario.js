//funciones javascript y validaciones

	$(document).ready(function(){
		printTable('');
		$(".select2").select2();
	});
	
	function Limpiar(){
		swal({
			text: "\u00BFDesea Limpiar la p\u00E1gina?, si a\u00FAn no a grabado perdera los datos escritos...",
			icon: "info",
			buttons: {
				cancel: "Cancelar",
				ok: { text: "Aceptar", value: true,},
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
	
	function Submit(){
		myform = document.forms.f1;
		myform.submit();
	}
	
	function printTable(codigo){
		contenedor = document.getElementById("result");
		loadingCogs(contenedor);
		/////////// POST /////////
		var http = new FormData();
		http.append("request","tabla");
		http.append("codigo",codigo);
		var request = new XMLHttpRequest();
		request.open("POST", "ajax_fns_cuestionario.php");
		request.send(http);
		request.onreadystatechange = function(){
			//console.log( request );
			if(request.readyState != 4) return;
			if(request.status === 200){
				//console.log( request.responseText );
				resultado = JSON.parse(request.responseText);
				if(resultado.status !== true){
					//console.log( resultado );
					contenedor.innerHTML = '...';
					console.log( resultado.message );
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
						{extend: 'copy'},
						{extend: 'csv'},
						{extend: 'excel', title: 'Tabla de Cuestionarios'},
						{extend: 'pdf', title: 'Tabla de Cuestionarios'},
						{extend: 'print',
							customize: function (win){
								$(win.document.body).addClass('white-bg');
								$(win.document.body).css('font-size', '10px');
								$(win.document.body).find('table')
										.addClass('compact')
										.css('font-size', 'inherit');
							}, title: 'Tabla de Cuestionarios'
						}
					]
				});
			}
		};     
	}
	
	
	function seleccionarCuestionario(codigo){
		contenedor = document.getElementById("result");
		loadingCogs(contenedor);
		/////////// POST /////////
		var http = new FormData();
		http.append("request","get");
		http.append("codigo",codigo);
		var request = new XMLHttpRequest();
		request.open("POST", "ajax_fns_cuestionario.php");
		request.send(http);
		request.onreadystatechange = function(){
			//console.log( request );
			if(request.readyState != 4) return;
			if(request.status === 200){
				resultado = JSON.parse(request.responseText);
				if(resultado.status !== true){
					swal("Error", resultado.message , "error");
					return;
				}
				var data = resultado.data;
				//console.log( data );
				//set
				document.getElementById("codigo").value = data.codigo;
				document.getElementById("categoria").value = data.categoria;
				document.getElementById("nombre").value = data.nombre;
				document.getElementById("pondera").value = data.pondera;
				document.getElementById("criterio").value = data.criterio;
				document.getElementById("objetivo").value = data.objetivo;
				AutoGrowTextArea(document.getElementById("objetivo"));
				document.getElementById("riesgo").value = data.riesgo;
				AutoGrowTextArea(document.getElementById("riesgo"));
				document.getElementById("alcance").value = data.alcance;
				AutoGrowTextArea(document.getElementById("alcance"));
				//tabla
				var tabla = resultado.tabla;
				contenedor.innerHTML = tabla;
				$('#tabla').DataTable({
					pageLength: 50,
					responsive: true
				});
				$(".select2").select2();
				//botones
				document.getElementById("nombre").focus(); 
				document.getElementById("btn-grabar").className = "btn btn-primary btn-sm hidden";
				document.getElementById("btn-modificar").className = "btn btn-primary btn-sm";
				//--
			}
		};     
	}
						
	function Grabar(){
		categoria = document.getElementById("categoria");
		criterio = document.getElementById("criterio");
		nombre = document.getElementById('nombre');
		pondera = document.getElementById('pondera');
		objetivo = document.getElementById('objetivo');
		riesgo = document.getElementById('riesgo');
		alcance = document.getElementById('alcance');
		//---
		selectcategoria = document.getElementById("select2-categoria-container");
		selectpondera = document.getElementById("select2-pondera-container");
		
		if(categoria.value !== "" && nombre.value !== "" && pondera.value !== "" && criterio.value !== ""){
			/////////// POST /////////
			var boton = document.getElementById("btn-grabar");
			loadingBtn(boton);
			var http = new FormData();
			http.append("request","grabar");
			http.append("categoria", categoria.value);
			http.append("criterio", criterio.value);
			http.append("nombre", nombre.value);
			http.append("pondera", pondera.value);
			http.append("objetivo", objetivo.value);
			http.append("riesgo", riesgo.value);
			http.append("alcance", alcance.value);
			var request = new XMLHttpRequest();
			request.open("POST", "ajax_fns_cuestionario.php");
			request.send(http);
			request.onreadystatechange = function(){
			   //console.log( request );
			   if(request.readyState != 4) return;
			   if(request.status === 200){
				resultado = JSON.parse(request.responseText);
					if(resultado.status !== true){
						console.log( resultado.sql );
						swal("Error", resultado.message , "error").then((value) => { deloadingBtn(boton,'<i class="fa fa-save"></i> Grabar'); });
						return;
					}
					//console.log( resultado );
					swal("Excelente!", resultado.message, "success").then((value) => {
						window.location.reload();
					});
				}
			};     
		}else{
			if(categoria.value === ""){
				selectcategoria.className = "select-danger select2-selection__rendered";
			}else{
				selectcategoria.classList.remove("is-invalid");
			}
			if(criterio.value === ""){
				criterio.classList.add("is-invalid");
			}else{
				criterio.classList.add("is-invalid");
			}
			if(nombre.value === ""){
				nombre.classList.add("is-invalid");
			}else{
				nombre.classList.add("is-invalid");
			}
			if(pondera.value === ""){
				selectpondera.className = "select-danger select2-selection__rendered";
			}else{
				selectpondera.classList.remove("is-invalid");
			}
			swal("Ohoo!", "Debe llenar los Campos Obligatorios...", "error");
		}
	}
	
	function Modificar(){
		codigo = document.getElementById("codigo");
		categoria = document.getElementById("categoria");
		criterio = document.getElementById("criterio");
		nombre = document.getElementById('nombre');
		pondera = document.getElementById('pondera');
		objetivo = document.getElementById('objetivo');
		riesgo = document.getElementById('riesgo');
		alcance = document.getElementById('alcance');
		//---
		selectcategoria = document.getElementById("select2-categoria-container");
		selectpondera = document.getElementById("select2-pondera-container");
		
		if(codigo.value !== "" && categoria.value !== "" && nombre.value !== "" && pondera.value !== "" && criterio.value !== ""){
			/////////// POST /////////
			var boton = document.getElementById("btn-modificar");
			loadingBtn(boton);
			var http = new FormData();
			http.append("request","modificar");
			http.append("codigo", codigo.value);
			http.append("categoria", categoria.value);
			http.append("criterio", criterio.value);
			http.append("nombre", nombre.value);
			http.append("pondera", pondera.value);
			http.append("objetivo", objetivo.value);
			http.append("riesgo", riesgo.value);
			http.append("alcance", alcance.value);
			var request = new XMLHttpRequest();
			request.open("POST", "ajax_fns_cuestionario.php");
			request.send(http);
			request.onreadystatechange = function(){
			   //console.log( request );
			   if(request.readyState != 4) return;
			   if(request.status === 200){
				resultado = JSON.parse(request.responseText);
					if(resultado.status !== true){
						console.log( resultado.sql );
						swal("Error", resultado.message , "error").then((value) => { deloadingBtn(boton,'<i class="fa fa-save"></i> Grabar'); });
						return;
					}
					//console.log( resultado );
					swal("Excelente!", resultado.message, "success").then((value) => {
						window.location.reload();
					});
				}
			};     
		}else{
			if(categoria.value === ""){
				selectcategoria.className = "select-danger select2-selection__rendered";
			}else{
				selectcategoria.classList.remove("is-invalid");
			}
			if(criterio.value === ""){
				criterio.classList.add("is-invalid");
			}else{
				criterio.classList.add("is-invalid");
			}
			if(nombre.value === ""){
				nombre.classList.add("is-invalid");
			}else{
				nombre.classList.add("is-invalid");
			}
			if(pondera.value === ""){
				selectpondera.className = "select-danger select2-selection__rendered";
			}else{
				selectpondera.classList.remove("is-invalid");
			}
			swal("Ohoo!", "Debe llenar los Campos Obligatorios...", "error");
		}
	}
	
	function deshabilitarCuestionario(codigo){
		swal({
			title: "\u00BFEsta Seguro?",
			text: "\u00BFDesea quitar a este cuestionario del listado?, no prodr\u00E1 ser usada despu\u00E9s...",
			icon: "warning",
			buttons: {
				cancel: "Cancelar",
				ok: { text: "Aceptar", value: true },
			}
		}).then((value) => {
			switch (value) {
				case true:
					cambioSituacion(codigo,0);
					break;
				default:
				  return;
			}
		});
	}
	
	function cambioSituacion(codigo,situacion){
		/////////// POST /////////
		var http = new FormData();
		http.append("request","situacion");
		http.append("codigo",codigo);
		http.append("situacion",situacion);
		var request = new XMLHttpRequest();
		request.open("POST", "ajax_fns_cuestionario.php");
		request.send(http);
		request.onreadystatechange = function(){
			//console.log( request );
			if(request.readyState != 4) return;
			if(request.status === 200){
				resultado = JSON.parse(request.responseText);
				if(resultado.status !== true){
					//console.log( resultado.sql );
					swal("Error", resultado.message , "error");
					return;
				}
				swal("Excelente!", "Registro eliminado satisfactorio!!!", "success").then((value)=>{ window.location.reload(); });
			}
		};     
	}
	
	
	/////////////////// REPORTES ////////////////////////
	
	function verParticipantes(codigo){
		cerrar();
		//Realiza una peticion de contenido a la contenido.php
		$.post("../promts/auditoria/participantes.php",{codigo:codigo}, function(data){
		// Ponemos la respuesta de nuestro script en el DIV recargado
		$("#Pcontainer").html(data);
		});
		abrirModal();
   }
   
   function verActividades(codigo){
		cerrar();
		//Realiza una peticion de contenido a la contenido.php
		$.post("../promts/auditoria/actividades.php",{codigo:codigo}, function(data){
		// Ponemos la respuesta de nuestro script en el DIV recargado
		$("#Pcontainer").html(data);
		});
		abrirModal();
   }
