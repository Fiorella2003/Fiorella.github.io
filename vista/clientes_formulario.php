<?php
	require_once('../modelo/clsCliente.php');

	$objCli = new clsCliente();
	$accion = $_GET['accion'];
	$id = 0;

	$arrayTipoDoc = $objCli->listaTipoDocumento();
	$arrayTipoDoc = $arrayTipoDoc->fetchAll(PDO::FETCH_NAMED);

	if($accion=='ACTUALIZAR'){
		$id = $_GET['idcliente'];
		$cliente = $objCli->consultarClientePorId($id);
		$cliente = $cliente->fetch(PDO::FETCH_NAMED);
	}

?>
<form name="formCliente" id="formCliente">
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<label for="idtipodocumento">Tipo de Documento</label>
				<select class="form-control" name="idtipodocumento" id="idtipodocumento">
					<option value="">- SELECCIONE -</option>
					<?php foreach($arrayTipoDoc as $k=>$v){ ?>
					<option value="<?= $v['idtipodocumento'] ?>"><?= $v['nombre'] ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="form-group">
				<label for="nrodocumento">Numero de Documento</label>
				<div class="input-group">
					<input type="text" class="form-control" id="nrodocumento" name="nrodocumento" value="<?php if($accion=='ACTUALIZAR'){ echo $cliente['nrodocumento']; } ?>">
					<div class="input-group-prepend">
						<span style="button" class="input-group-text btn" onclick="consultarDatoCliente()">
							<i class="fas fa-search"></i>
						</span>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label for="nombre">Nombre</label>
				<input type="text" class="form-control" id="nombre" name="nombre" value="<?php if($accion=='ACTUALIZAR'){ echo $cliente['nombre']; } ?>">
				<input type="hidden" class="form-control" id="idcliente" name="idcliente" value="<?= $id ?>">
			</div>
			<div class="form-group">
				<label for="direccion">Dirección</label>
				<textarea class="form-control" name="direccion" id="direccion"><?php if($accion=='ACTUALIZAR'){ echo $cliente['direccion']; } ?></textarea>
			</div>
			<div class="form-group" style="">
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
			<button type="button" class="btn btn-primary" onclick="registrarCliente()"><i class="fa fa-save"></i> Registrar</button>
		</div>
	</div>
</form>

<script>

	<?php if($accion=='ACTUALIZAR'){ ?>
		$('#estado').val('<?php echo $cliente['estado'] ?>');
		$('#idtipodocumento').val('<?php echo $cliente['idtipodocumento'] ?>');
	<?php } ?>

	function registrarCliente(){
		if(verificarFormulario()){
			var datax = $('#formCliente').serializeArray();
			datax.push({ name: 'accion', value: '<?php echo $accion; ?>' });
			
			$.ajax({
				method: 'POST',
				url: 'controlador/contCliente.php',
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


	function consultarDatoCliente(){
		$('#formCliente').LoadingOverlay('show');
		$.ajax({
			method: 'POST',
			url: 'controlador/contCliente.php',
			data: {
				accion: 'CONSULTAR_DATOS_WS',
				idtipodocumento: $('#idtipodocumento').val(),
				nrodocumento: $('#nrodocumento').val()
			},
			dataType: 'json'
		})
		.done(function(resultado){
			$('#formCliente').LoadingOverlay('hide');
			$('#nombre').val(resultado.nombre);
			$('#direccion').val(resultado.direccion);
		})
	}

</script>