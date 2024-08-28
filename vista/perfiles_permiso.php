<?php
	require_once('../modelo/clsPerfil.php');

	$objPer = new clsPerfil();
	$id = $_GET['idperfil'];
	$perfil = $objPer->consultarPerfilPorId($id);
	$perfil = $perfil->fetch(PDO::FETCH_NAMED);

	$dataOpcion = $objPer->listarOpcion($id);
	$dataOpcion = $dataOpcion->fetchAll(PDO::FETCH_NAMED);
	

?>
<form name="formPerfil" id="formPerfil">
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<label for="nombre">Perfil</label>
				<input type="text" class="form-control" readonly id="nombre" name="nombre" value="<?php echo $perfil['nombre']; ?>">
				<input type="hidden" class="form-control" id="idperfil" name="idperfil" value="<?= $id ?>">
			</div>
		</div>
		<div class="col-md-12">
			<table class="table table-hover text-nowrap table-striped table-sm">
				<thead>
					<tr>
						<th>#</th>
						<th>Opcion del sistema</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($dataOpcion as $k=>$v){ ?>
					<tr>
						<td>
							<input type="checkbox" <?php if($v['acceso']>0){ ?> checked <?php } ?> name="permiso<?= $v['idopcion'] ?>" id="permiso<?= $v['idopcion'] ?>" onclick="verificarPermiso(<?= $v['idopcion'] ?>)">
						</td>
						<td><?= $v['descripcion'] ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</form>
<script>
	function verificarPermiso(idopcion){
		valorcheck = 0;
		if($('#permiso'+idopcion).is(':checked')){
			valorcheck = 1;
		}
		$.ajax({
			method: 'POST',
			url: "controlador/contPerfil.php",
			data:{
				accion: 'VERIFICAR_ACCESO',
				idopcion: idopcion,
				idperfil: <?= $id ?>,
				estado: valorcheck
			},
			dataType: 'json'
		})
		.done(function(resultado){
			if(resultado.correcto==1){
				toastCorrecto(resultado.mensaje);
			}else{
				toastError(resultado.mensaje);
			}
		})
	}

</script>