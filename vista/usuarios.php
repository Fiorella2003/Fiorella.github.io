<?php
require_once('../modelo/clsPerfil.php');

$objPer = new clsPerfil();

$arrayPerfil = $objPer->listarPerfil('',1);
$arrayPerfil = $arrayPerfil->fetchAll(PDO::FETCH_NAMED);

?>
<section class="content-header">
     <div class="container-fluid">
		<div class="card card-primary">
			<div class="card-header" style="background-color: #2F46CF; color: #FFFFFF;">
				<h3 class="card-title">Listado de Usuarios</h3>
			</div>
			<div class="card-body">
				<div class="row">
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
							<span class="input-group-text">Perfil</span>
							</div>
							<select class="form-control" name="cboBusquedaPerfil" id="cboBusquedaPerfil" onchange="verListado()">
								<option value="">- Todos -</option>
								<?php foreach($arrayPerfil as $k=>$v){ ?>
								<option value="<?= $v['idperfil'] ?>"><?= $v['nombre'] ?></option>
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
						<button type="button" class="btn btn-success" onclick="nuevoUsuario()"><i class="fa fa-plus"></i> Nuevo</button>
					</div>
				</div>
			</div>
		</div>
		<div class="card card-success">
			<div class="card-body">
				<div class="row">
					<div class="col-md-12" id="divListadoUsuario">
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
			url: 'vista/usuarios_listado.php',
			data:{
				nombre: $('#txtBusquedaNombre').val(),
				estado: $('#cboBusquedaEstado').val(),
				idperfil: $('#cboBusquedaPerfil').val()
			}
		})
		.done(function(resultado){
			$('#divListadoUsuario').html(resultado);
		});
	}

	verListado();

	
	function nuevoUsuario(){
		abrirModal('vista/usuarios_formulario','accion=NUEVO','divmodal1','Registro de Usuario');
	}

</script>