<section class="content-header">
     <div class="container-fluid">
		<div class="card card-primary">
			<div class="card-header" style="background-color: #2F46CF; color: #FFFFFF;">
				<h3 class="card-title">Listado de Categorias</h3>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-md-4">
						<div class="input-group mb-3">
							<div class="input-group-prepend">
							<span class="input-group-text">Categoria</span>
							</div>
							<input type="text" class="form-control" name="txtBusquedaNombre" id="txtBusquedaNombre" onkeyup="if(event.keyCode=='13'){ verListado(); }">
						</div>
					</div>
					<div class="col-md-4">
						<div class="input-group mb-3">
							<div class="input-group-prepend">
							<span class="input-group-text">Estado</span>
							</div>
							<select class="form-control" name="cboBusquedaEstado" id="cboBusquedaEstado" onchange="verListado()">
								<option value="">- Todos -</option>
								<option value="1">Activos</option>
								<option value="0">Anulados</option>
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<button type="button" class="btn btn-primary" onclick="verListado()"><i class="fa fa-search"></i> Buscar</button>
						<button type="button" class="btn btn-success" onclick="nuevaCategoria()"><i class="fa fa-plus"></i> Nuevo</button>
						<button type="button" class="btn btn-info" onclick="importarCategoria()"><i class="fa fa-upload"></i> Importar</button>
						<a type="button" class="btn bg-maroon" href="files/importar_categoria.xlsx"><i class="fa fa-download"></i> Descargar Formato</a>
					</div>
				</div>
			</div>
		</div>
		<div class="card card-success">
			<div class="card-body">
				<div class="row">
					<div class="col-md-12" id="divListadoCategoria">
						CONTENEDOR TABLA
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<script>
	function verListado(){
		$.ajax({
			method: 'POST',
			url: 'vista/categorias_listado.php',
			data:{
				nombre: $('#txtBusquedaNombre').val(),
				estado: $('#cboBusquedaEstado').val()
			}
		})
		.done(function(resultado){
			$('#divListadoCategoria').html(resultado);
		});
	}

	verListado();

	// function openModal(){
	// 	$('#tituloModal').html('REGISTRAR CATEGORIA');
	// 	$('#formCategoria').trigger('reset');
	// 	$('#modalCategoria').modal('show');
	// 	$('#idcategoria').val('');
	// }


	function nuevaCategoria(){
		abrirModal('vista/categorias_formulario','accion=NUEVO','divmodal1','Registro de Categoria');
	}

	function importarCategoria(){
		abrirModal('vista/categorias_importar','accion=IMPORTAR','divmodal1','Importar Categoria');
	}

</script>