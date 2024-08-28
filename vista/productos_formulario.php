<?php
	require_once('../modelo/clsProducto.php');
	require_once('../modelo/clsCategoria.php');

	$objPro = new clsProducto();
	$objCat = new clsCategoria();
	$accion = $_GET['accion'];
	$id = 0;

	$listaUnidad = $objPro->consultarUnidad();
	$listaUnidad = $listaUnidad->fetchAll(PDO::FETCH_NAMED);

	$listaCategoria = $objCat->listarCategoria('',1);
	$listaCategoria = $listaCategoria->fetchAll(PDO::FETCH_NAMED);


	if($accion=='ACTUALIZAR'){
		$id = $_GET['idproducto'];
		$producto = $objPro->consultarProductoPorId($id);
		$producto = $producto->fetch(PDO::FETCH_NAMED);
	}

?>
<form name="formProducto" id="formProducto">
	<div class="row">
		<div class="col-md-4">
			<div class="form-group">
				<label for="codigobarra">Codigo de Barra</label>
				<input type="text" class="form-control" id="codigobarra" name="codigobarra" autocomplete="off" mensaje="" value="<?php if($accion=='ACTUALIZAR'){ echo $producto['codigobarra']; } ?>">
				<input type="hidden" class="form-control" id="idproducto" name="idproducto" value="<?= $id ?>">
			</div>
			<div class="form-group">
				<label for="nombre">Producto</label>
				<!-- <input type="text" autocomplete="off" class="form-control" id="nombre" name="nombre" value="<?php if($accion=='ACTUALIZAR'){ echo $producto['nombre']; } ?>"> -->
				<textarea class="form-control obligatorio" id="nombre" name="nombre" rows="2"><?php if($accion=='ACTUALIZAR'){ echo $producto['nombre']; } ?></textarea>
			</div>
			<div class="form-group">
				<label for="idunidad">Unidad</label>
				<select class="form-control obligatorio" name="idunidad" id="idunidad">
					<option value="">- Seleccione -</option>
					<?php foreach($listaUnidad as $k=>$v){ ?>
					<option value="<?= $v['idunidad'] ?>"><?= $v['descripcion'] ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="form-group">
				<label for="idcategoria">Categoria</label>
				<select class="form-control obligatorio" name="idcategoria" id="idcategoria">
					<option value="">- Seleccione -</option>
					<?php foreach($listaCategoria as $k=>$v){ ?>
					<option value="<?= $v['idcategoria'] ?>"><?= $v['nombre'] ?></option>
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
		<div class="col-md-4">
			<div class="form-group">
				<label for="pventa">Precio de Venta</label>
				<input type="number" step="0.01" placeholder="0.00" autocomplete="off" class="form-control obligatorio" id="pventa" name="pventa" value="<?php if($accion=='ACTUALIZAR'){ echo $producto['pventa']; } ?>">
			</div>
			<div class="form-group">
				<label for="pcompra">Precio de Compra</label>
				<input type="number" step="0.01" placeholder="0.00" autocomplete="off" class="form-control" id="pcompra" name="pcompra" value="<?php if($accion=='ACTUALIZAR'){ echo $producto['pcompra']; } ?>">
				<br>
			</div>
			<div class="form-group">
				<label for="stock">Stock</label>
				<input type="number" step="0.01" autocomplete="off" class="form-control obligatorio" id="stock" name="stock" value="<?php if($accion=='ACTUALIZAR'){ echo $producto['stock']; } ?>">
			</div>
			<div class="form-group">
				<label for="stockseguridad">Stock de seguridad</label>
				<input type="number" step="0.01" autocomplete="off" class="form-control obligatorio" id="stockseguridad" name="stockseguridad" value="<?php if($accion=='ACTUALIZAR'){ echo $producto['stockseguridad']; } ?>">
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				<label for="idafectacion">Afectacion IGV</label>
				<select class="form-control obligatorio" name="idafectacion" id="idafectacion">
					<option value="">- Seleccione -</option>
					<?php foreach($listaAfectacion as $k=>$v){ ?>
					<option value="<?= $v['idafectacion'] ?>"><?= $v['descripcion'] ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="form-group">
				<label for="afectoicbper">¿Es Afecto al ICBPER?</label>
				<select class="form-control" name="afectoicbper" id="afectoicbper">
					<option value="1">SI</option>
					<option value="0" selected>NO</option>
				</select>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 text-center">
			<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>
			<button type="button" class="btn btn-primary" onclick="registrarProducto()"><i class="fa fa-save"></i> Registrar</button>
		</div>
	</div>
</form>
<script>

	<?php if($accion=='ACTUALIZAR'){ ?>
		$('#estado').val('<?php echo $producto['estado'] ?>');
		$('#idunidad').val('<?= $producto['idunidad'] ?>');
		$('#idcategoria').val('<?= $producto['idcategoria'] ?>');
		$('#idafectacion').val('<?= $producto['idafectacion'] ?>');
		$('#afectoicbper').val('<?= $producto['afectoicbper'] ?>');
	<?php } ?>

	function registrarProducto(){
		if(verificarFormulario()){
			var datax = $('#formProducto').serializeArray();
			datax.push({ name: 'accion', value: '<?php echo $accion; ?>' });
			
			$.ajax({
				method: 'POST',
				url: 'controlador/contProducto.php',
				data: datax,
				dataType: 'json'
			})
			.done(function(resultado){
				if(resultado.correcto==1){
					toastCorrecto(resultado.mensaje);
					CloseModal('divmodal');
					verListado();
				}else{
					toastError(resultado.mensaje);
				}
			})
		}else{
			toastError('Existen errores en su formulario.');
		}
	}

	function verificarFormulario(){
		correcto = true;

		$(".obligatorio").each(function(){
			$(this).removeClass('is-invalid');
			if($(this).val()=="" || $(this).val()=="0"){
				$(this).addClass('is-invalid');
				correcto = false;
			}
		})

		return correcto;
	}

</script>