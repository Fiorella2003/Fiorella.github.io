<?php
require_once('../modelo/clsVenta.php');
require_once('../modelo/clsUsuario.php');

// $objVenta = new clsVenta();
$objUsu = new clsUsuario();

$arrayComprobante = $objVenta->consultarComprobante();
$arrayComprobante = $arrayComprobante->fetchAll(PDO::FETCH_NAMED);

$listaUsuario = $objUsu->listarUsuario('',1,'');
$listaUsuario = $listaUsuario->fetchAll(PDO::FETCH_NAMED);

$mesactual = date('Y-m');
$fechadesde = strtotime('-6 months', strtotime($mesactual));
$fechadesde = date('Y-m-01', $fechadesde);



?>
<section class="content-header">
     <div class="container-fluid">
		<div class="card card-success">
			<div class="card-header" style="background-color: #2F46CF; color: #FFFFFF;">
				<h3 class="card-title">Listado de Ventas</h3>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-md-4">
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<span class="input-group-text">Desde</span>
							</div>
							<input type="date" class="form-control" name="txtBusquedaFechaDesde" id="txtBusquedaFechaDesde" value="<?= $fechadesde ?>" autocomplete="off">
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
							<span class="input-group-text">Comprobante</span>
							</div>
							<select class="form-control" name="cboBusquedaComprobante" id="cboBusquedaComprobante" onchange="verListado()">
								<option value="">- Todos -</option>
								<?php foreach($arrayComprobante as $k=>$v){ ?>
								<option value="<?= $v['idtipocomprobante'] ?>"><?= $v['nombre'] ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<div class="input-group mb-3">
							<div class="input-group-prepend">
							<span class="input-group-text">Vendedor</span>
							</div>
							<select class="form-control" name="cboBusquedaVendedor" id="cboBusquedaVendedor" onchange="verListado()">
								<option value="">- Todos -</option>
								<?php foreach($listaUsuario as $k=>$v){ ?>
								<option value="<?= $v['idusuario'] ?>"><?= $v['nombre'] ?></option>
								<?php } ?>
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
					<div class="col-md-12" id="divListadoVenta">
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
			url: 'vista/reportes_listado.php',
			data:{
				desde: $('#txtBusquedaFechaDesde').val(),
				hasta: $('#txtBusquedaFechaHasta').val(),
				idtipocomprobante: $('#cboBusquedaComprobante').val(),
				idusuario: $('#cboBusquedaVendedor').val()
			}
		})
		.done(function(resultado){
			$('#divListadoVenta').html(resultado);
		});
	}

	verListado();

</script>