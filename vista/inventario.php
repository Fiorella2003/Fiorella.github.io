<?php
require_once('../modelo/clsCategoria.php');
$objCat = new clsCategoria();

$arrayCategoria = $objCat->listarCategoria('',1);
$arrayCategoria = $arrayCategoria->fetchAll(PDO::FETCH_NAMED);

?>
<section class="content-header">
     <div class="container-fluid">
		<div class="card card-primary">
			<div class="card-header" style="background-color: #2F46CF; color: #FFFFFF;">
				<h3 class="card-title">Listado de Productos</h3>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-md-4">
						<div class="input-group mb-3">
							<div class="input-group-prepend">
							<span class="input-group-text">Categoria</span>
							</div>
							<select class="form-control" name="cboBusquedaCategoria" id="cboBusquedaCategoria" onchange="verListado()">
								<option value="">- Todos -</option>
								<?php foreach($arrayCategoria as $k=>$v){ ?>
								<option value="<?php echo $v['idcategoria']; ?>"><?php echo $v['nombre']; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<div class="input-group mb-3">
							<div class="input-group-prepend">
							<span class="input-group-text">Codigo</span>
							</div>
							<input type="text" class="form-control" name="txtBusquedaCodigo" id="txtBusquedaCodigo" onkeyup="if(event.keyCode=='13'){ verListado(); }">
						</div>
					</div>
					<div class="col-md-4">
						<div class="input-group mb-3">
							<div class="input-group-prepend">
							<span class="input-group-text">Producto</span>
							</div>
							<input type="text" class="form-control" name="txtBusquedaNombre" id="txtBusquedaNombre" onkeyup="if(event.keyCode=='13'){ verListado(); }">
						</div>
					</div>
					<div class="col-md-4" style="display: none">
						<div class="input-group mb-3">
							<div class="input-group-prepend">
							<span class="input-group-text">Estado</span>
							</div>
							<select class="form-control" name="cboBusquedaEstado" id="cboBusquedaEstado" onchange="verListado()">
								<option value="">- Todos -</option>
								<option value="1" selected>Activos</option>
								<option value="0">Anulados</option>
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<div class="input-group mb-3">
							<div class="input-group-prepend">
							<span class="input-group-text">Filtrar</span>
							</div>
							<select class="form-control" name="cboBusquedaFiltro" id="cboBusquedaFiltro" onchange="verListado()">
								<option value="">- Todos -</option>
								<option value="PCS">Productos con Stock</option>
								<option value="PSS">Productos sin Stock</option>
								<option value="PSM">Productos con Stock < Stock de Seguridad</option>
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<button type="button" class="btn btn-primary" onclick="verListado()"><i class="fa fa-search"></i> Buscar</button>
					</div>
				</div>
			</div>
		</div>
		<div class="card card-success">
			<div class="card-body">
				<div class="row">
					<div class="col-md-12" id="divListadoProducto">
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
			url: 'vista/inventario_listado.php',
			data:{
				nombre: $('#txtBusquedaNombre').val(),
				estado: $('#cboBusquedaEstado').val(),
				codigo: $('#txtBusquedaCodigo').val(),
				idcategoria: $('#cboBusquedaCategoria').val(),
				filtro: $('#cboBusquedaFiltro').val()
			}
		})
		.done(function(resultado){
			
			$('#divListadoProducto').html(resultado);
		});
	}

	verListado();

</script>