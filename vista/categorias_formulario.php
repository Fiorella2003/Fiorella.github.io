<?php
	require_once('../modelo/clsCategoria.php');

	$objCat = new clsCategoria();
	$accion = $_GET['accion'];
	$id = 0;
	if($accion=='ACTUALIZAR'){
		$id = $_GET['idcategoria'];
		$categoria = $objCat->consultarCategoriaPorId($id);
		$categoria = $categoria->fetch(PDO::FETCH_NAMED);
	}

?>
<form name="formCategoria" id="formCategoria">
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<label for="nombre">Categoria</label>
				<input type="text" class="form-control" id="nombre" name="nombre" value="<?php if($accion=='ACTUALIZAR'){ echo $categoria['nombre']; } ?>">
				<input type="hidden" class="form-control" id="idcategoria" name="idcategoria" value="<?= $id ?>">
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
			<button type="button" class="btn btn-primary" onclick="registrarCategoria()"><i class="fa fa-save"></i> Registrar</button>
		</div>
	</div>
</form>
<script>

	<?php if($accion=='ACTUALIZAR'){ ?>
		$('#estado').val('<?php echo $categoria['estado'] ?>');
	<?php } ?>

	function registrarCategoria(){
		if(verificarFormulario()){
			var datax = $('#formCategoria').serializeArray();
			var idcategoria = $('#idcategoria').val();
			datax.push({ name: 'accion', value: '<?php echo $accion; ?>' });
			
			$.ajax({
				method: 'POST',
				url: 'controlador/contCategoria.php',
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
			toastError('Ingrese el nombre de la Categoria');
			correcto = false;
		}

		return correcto;
	}

</script>