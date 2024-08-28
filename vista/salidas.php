<?php
require_once('../modelo/clsSalida.php');
require_once('../modelo/clsUsuario.php');

$objSalida = new clsSalida();
$objUsu = new clsUsuario();

$listaUsuario = $objUsu->listarUsuario('', 1, '');
$listaUsuario = $listaUsuario->fetchAll(PDO::FETCH_NAMED);


?>

<section class="content-header">
	<div class="container-fluid">
		<div class="card card-primary">
			<div class="card-header" style="background-color: #2F46CF; color: #FFFFFF;">
				<h3 class="card-title">Listado de Salidas</h3>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-md-4">
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<span class="input-group-text">Desde</span>
							</div>
							<input type="date" class="form-control" name="txtBusquedaFechaDesde" id="txtBusquedaFechaDesde" value="<?= date('Y-m-01') ?>" autocomplete="off">
						</div>
					</div>
					<div class="col-md-4">
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<span class="input-group-text">Hasta</span>
							</div>
							<input type="date" class="form-control" name="txtBusquedaFechaHasta" id="txtBusquedaFechaHasta" autocomplete="off">
						</div>
					</div>
					<div class="col-md-4">
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<span class="input-group-text">Cliente</span>
							</div>
							<input type="text" class="form-control" name="txtBusquedaCliente" id="txtBusquedaCliente" onkeyup="if(event.keyCode=='13'){ verListado(); }">
						</div>
					</div>
					<div class="col-md-4">
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<span class="input-group-text">Vendedor</span>
							</div>
							<select class="form-control" name="cboBusquedaVendedor" id="cboBusquedaVendedor" onchange="verListado()">
								<option value="">- Todos -</option>
								<?php foreach ($listaUsuario as $k => $v) { ?>
									<option value="<?= $v['idusuario'] ?>"><?= $v['nombre'] ?></option>
								<?php } ?>
							</select>
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
						<button type="button" class="btn btn-success" onclick="NuevaSalida()"><i class="fa fa-plus"></i> Nuevo</button>
					</div>
				</div>
			</div>
		</div>
		<div class="card card-success">
			<div class="card-body">
				<div class="row">
					<div class="col-md-12" id="divListadoSalida">
						CONTENEDOR TABLA
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<script>
	function verListado() {
		$.ajax({
				method: 'POST',
				url: 'vista/salidas_listado.php',
				data: {
					desde: $('#txtBusquedaFechaDesde').val(),
					hasta: $('#txtBusquedaFechaHasta').val(),
					cliente: $('#txtBusquedaCliente').val(),
					idusuario: $('#cboBusquedaVendedor').val(),
					estado: $('#cboBusquedaEstado').val()
				}
			})
			.done(function(resultado) {
				$('#divListadoSalida').html(resultado);
			});
	}

	verListado();


	function NuevaSalida() {
		$.ajax({
			method: "POST",
			url: "vista/salidas_formulario.php",
			data: {
				'proceso': "NUEVO"
			}
		}).done(function(resultado) {
			console.log("Resultado del servidor:", resultado); // Agrega esta línea

			$("#divPrincipal").html(resultado);
		});
	}
</script>