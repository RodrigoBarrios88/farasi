//funciones javascript y validaciones
	$(document).ready(function(){
		$(".select2").select2();
			
		$('#range .input-daterange').datepicker({
			keyboardNavigation: false,
			forceParse: false,
			autoclose: true,
			format: "dd/mm/yyyy"
		});
		
		calendarioMenu();
	});

	function calendarioMenu(){
		sede = document.getElementById("sede");
		departamento = document.getElementById("departamento");
		categoria = document.getElementById('categoria');
		desde = document.getElementById("desde");
		hasta = document.getElementById("hasta");
		//--
		contenedor = document.getElementById("calendarContainer");
		loadingCogs(contenedor);
		/////////// POST /////////
		var http = new FormData();
		http.append("request","calendario_auditoria");
		var request = new XMLHttpRequest();
		request.open("POST", "ajax_fns.php");
		http.append("sede", sede.value);
		http.append("departamento", departamento.value);
		http.append("categoria", categoria.value);
		http.append("fini", desde.value);
		http.append("ffin", hasta.value);
		request.send(http);
		request.onreadystatechange = function(){
			//console.log( request );
			if(request.readyState != 4) return;
			if(request.status === 200){
				resultado = JSON.parse(request.responseText);
				if(resultado.status !== true){
					contenedor.innerHTML = '...';
					console.log( "Error: ", resultado.message, ';', request.responseText );
					console.log( request.responseText );
					return;
				}
				//data
				let data = resultado.data;
				//console.log( resultado.parametros );
				//console.log( data );
				contenedor.innerHTML = '';
				var fullCalendar = document.createElement("div");
				fullCalendar.setAttribute("id", "fullCalendar");
				contenedor.appendChild(fullCalendar);
				////////////////// CALENDARIO ///////////////////////
				$calendar = $('#fullCalendar');
				today = new Date();
				y = today.getFullYear();
				m = today.getMonth();
				d = today.getDate();
				
				$calendar.fullCalendar({
					viewRender: function(view, element) {
						// We make sure that we activate the perfect scrollbar when the view isn't on Month
						if (view.name != 'month') {
							$(element).find('.fc-scroller').perfectScrollbar();
						}
					},
					header: {
						left: 'title',
						center: 'month,agendaWeek,agendaDay',
						right: 'prev,next,today'
					},
					defaultDate: today,
					selectable: false,
					selectHelper: false,
					editable: false,
					eventLimit: true, // allow "more" link when too many events
					// color classes: [ event-blue | event-azure | event-green | event-orange | event-red ]
					events: data
				});
			}
		}; 
	}
	

	function listFallas(activo,falla){
		cerrar();
		//Realiza una peticion de contenido a la contenido.php
		$.post("promts/activos/historial_fallas.php",{activo:activo, falla:falla}, function(data){
		// Ponemos la respuesta de nuestro script en el DIV recargado
		$("#Pcontainer").html(data);
		});
		abrirModal();
	}