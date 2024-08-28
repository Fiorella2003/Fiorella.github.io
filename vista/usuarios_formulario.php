<?php
	require_once('../modelo/clsUsuario.php');
	require_once('../modelo/clsPerfil.php');

	$objUsu = new clsUsuario();
	$objPer = new clsPerfil();
	$accion = $_GET['accion'];
	$id = 0;

	$listaPerfil = $objPer->listarPerfil('',1);
	$listaPerfil = $listaPerfil->fetchAll(PDO::FETCH_NAMED);

	if($accion=='ACTUALIZAR'){
		$id = $_GET['idusuario'];
		$usuario = $objUsu->consultarUsuarioPorId($id);
		$usuario = $usuario->fetch(PDO::FETCH_NAMED);
	}

?>
<form name="formUsuario" id="formUsuario">
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<label for="nombre">Nombre</label>
				<input type="text" class="form-control" id="nombre" name="nombre" value="<?php if($accion=='ACTUALIZAR'){ echo $usuario['nombre']; } ?>">
				<input type="hidden" class="form-control" id="idusuario" name="idusuario" value="<?= $id ?>">
			</div>
			<div class="form-group">
				<label for="usuario">Usuario</label>
				<input type="text" class="form-control" id="usuario" name="usuario" value="<?php if($accion=='ACTUALIZAR'){ echo $usuario['usuario']; } ?>">
			</div>
			<div class="form-group">
				<label for="clave">Clave</label>
				<input type="text" class="form-control" id="clave" name="clave" value="">
			</div>
			<div class="form-group">
				<label for="idperfil">Perfil</label>
				<select class="form-control" name="idperfil" id="idperfil">
					<option value="">- SELECCIONE -</option>
					<?php foreach($listaPerfil as $k=>$v){ ?>
					<option value="<?= $v['idperfil'] ?>"><?= $v['nombre'] ?></option>
					<?php } ?>
				</select>
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
			<button type="button" class="btn btn-primary" onclick="registrarUsuario()"><i class="fa fa-save"></i> Registrar</button>
		</div>
	</div>
</form>
<script>

	<?php if($accion=='ACTUALIZAR'){ ?>
		$('#estado').val('<?php echo $usuario['estado'] ?>');
		$('#idperfil').val('<?php echo $usuario['idperfil'] ?>');
	<?php } ?>

	function registrarUsuario(){
		if(verificarFormulario()){
			var datax = $('#formUsuario').serializeArray();
			datax.push({ name: 'accion', value: '<?php echo $accion; ?>' });
			
			$.ajax({
				method: 'POST',
				url: 'controlador/contUsuario.php',
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
			toastError('Ingrese el nombre del usuario');
			correcto = false;
		}

		return correcto;
	}

</script>