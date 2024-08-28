<?php
require_once('../modelo/clsCliente.php');
require_once('../modelo/clsCategoria.php'); // codigo para ejemplo de combos que depende uno del otro

$objCli = new clsCliente();
$objCat = new clsCategoria();

$arrayTipoDoc = $objCli->listaTipoDocumento();
$arrayTipoDoc = $arrayTipoDoc->fetchAll(PDO::FETCH_NAMED);


$arrayCategoria = $objCat->listarCategoria('',1);
$arrayCategoria = $arrayCategoria->fetchAll(PDO::FETCH_NAMED);

?>
<section class="content-header">
     <div class="container-fluid">
		<div class="card card-primary">
			<div class="card-header" style="background-color: #2F46CF; color: #FFFFFF;">
				<h3 class="card-title">Listado de Trabajadores</h3>
			</div>Registro Trabajadores
			<div class="card-body">
				<div class="row">
					<div class="col-md-4">
						<div class="input-group mb-3">
							<div class="input-group-prepend">
							<span class="input-group-text">Tipo Doc.</span>
							</div>
							<select class="form-control" name="cboBusquedaTipoDoc" id="cboBusquedaTipoDoc" onchange="verListado()">
								<option value="">- Todos -</option>
								<?php foreach($arrayTipoDoc as $k=>$v){ ?>
								<option value="<?= $v['idtipodocumento'] ?>"><?= $v['nombre'] ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<div class="input-group mb-3">
							<div class="input-group-prepend">
							<span class="input-group-text">Nro Doc</span>
							</div>
							<input type="text" class="form-control" name="txtBusquedaDocumento" id="txtBusquedaDocumento" onkeyup="if(event.keyCode=='13'){ verListado(); }">
						</div>
					</div>
					<div class="col-md-4">
						<div class="input-group mb-3">
							<div class="input-group-prepend">
							<span class="input-group-text">Nombre</span>
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
					<!-- CODIGO DE PRUEBA PARA COMBOS DE 2 NIVELES -->
					<!-- INICIO -->
					<div class="col-md-4" style="display: none">
						<div class="input-group mb-3">
							<div class="input-group-prepend">
							<span class="input-group-text">Categoria</span>
							</div>
							<select class="form-control" name="cboBusquedaCategoria" id="cboBusquedaCategoria" onchange="obtenerProductos()">
								<option value="">- Todos -</option>
								<?php foreach($arrayCategoria as $k=>$v){ ?>
								<option value="<?= $v['idcategoria'] ?>"><?= $v['nombre'] ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="col-md-4" style="display: none">
						<div class="input-group mb-3">
							<div class="input-group-prepend">
							<span class="input-group-text">PRODUCTOS</span>
							</div>
							<select class="form-control" name="cboBusquedaProducto" id="cboBusquedaProducto" onchange="">
								<option value="">- Todos -</option>
							</select>
						</div>
					</div>
					<!-- FIN -->
					<div class="col-md-4">
						<button type="button" class="btn btn-primary" onclick="verListado()"><i class="fa fa-search"></i> Buscar</button>
						<button type="button" class="btn btn-success" onclick="nuevoCliente()"><i class="fa fa-plus"></i> Nuevo</button>
					</div>
				</div>
			</div>
		</div>
		<div class="card card-success">
			<div class="card-body">
				<div class="row">
					<div class="col-md-12" id="divListadoCliente">
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
			url: 'vista/clientes_listado.php',
			data:{
				nombre: $('#txtBusquedaNombre').val(),
				estado: $('#cboBusquedaEstado').val(),
				idtipodocumento: $('#cboBusquedaTipoDoc').val(),
				documento: $('#txtBusquedaDocumento').val()
			}
		})
		.done(function(resultado){
			$('#divListadoCliente').html(resultado);
		});
	}

	verListado();

	
	function nuevoCliente(){
		abrirModal('vista/clientes_formulario','accion=NUEVO','divmodal1','Registro de Trabajadores');
	}

	function obtenerProductos(){
		$.ajax({
			method: 'POST',
			url: 'controlador/contCliente.php',
			data: {
				accion: 'OBTENER_PRODUCTOS',
				idcategoria: $('#cboBusquedaCategoria').val()
			}
		})
		.done(function(resultado){
			$('#cboBusquedaProducto').html(resultado);
		})
	}

</script>