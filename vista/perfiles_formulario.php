<?php
	require_once('../modelo/clsPerfil.php');

	$objPer = new clsPerfil();
	$accion = $_GET['accion'];
	$id = 0;
	if($accion=='ACTUALIZAR'){
		$id = $_GET['idperfil'];
		$perfil = $objPer->consultarPerfilPorId($id);
		$perfil = $perfil->fetch(PDO::FETCH_NAMED);
	}

?>
<form name="formPerfil" id="formPerfil">
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<label for="nombre">Perfil</label>
				<input type="text" class="form-control" id="nombre" name="nombre" value="<?php if($accion=='ACTUALIZAR'){ echo $perfil['nombre']; } ?>">
				<input type="hidden" class="form-control" id="idperfil" name="idperfil" value="<?= $id ?>">
			</div>
			<div class="form-group" style="display: none;">
				<label for="estado">Estado</label>
				<select class="form-control" name="estado" id="estado">
					<option value="1">ACTIVO</option>
					<option value="0">ANULADO</option>
				</select>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 text-center">
			<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>
			<button type="button" class="btn btn-primary" onclick="registrarPerfil()"><i class="fa fa-save"></i> Registrar</button>
		</div>
	</div>
</form>
<script>

	<?php if($accion=='ACTUALIZAR'){ ?>
		$('#estado').val('<?php echo $perfil['estado'] ?>');
	<?php } ?>

	function registrarPerfil(){
		if(verificarFormulario()){
			var datax = $('#formPerfil').serializeArray();
			datax.push({ name: 'accion', value: '<?php echo $accion; ?>' });
			
			$.ajax({
				method: 'POST',
				url: 'controlador/contPerfil.php',
				data: datax,
				dataType: 'json'
			})
			.done(function(resultado){
				if(resultado.correcto==1){
					toastCorrecto(resultado.mensaje);
					CloseModal('divmodal1');
					verListado();
				}else{
					toastError(resultado.mensaje);
				}
			})
		}
	}

	function verificarFormulario(){
		correcto = true;

		if($('#nombre').val()==""){
			toastError('Ingrese el nombre del perfil');
			correcto = false;
		}

		return correcto;
	}

</script>