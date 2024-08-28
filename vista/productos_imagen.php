<?php
	require_once('../modelo/clsProducto.php');

	$objPro = new clsProducto();

	$id = $_GET['idproducto'];
	$producto = $objPro->consultarProductoPorId($id);
	$producto = $producto->fetch(PDO::FETCH_NAMED);
	

?>
<form name="formProducto" id="formProducto">
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<label for="nombre">Producto</label>
				<textarea class="form-control" readonly id="nombre" name="nombre" rows="2"><?php echo $producto['nombre'];  ?></textarea>
				<input type="hidden" class="form-control" id="idproducto" name="idproducto" value="<?= $id ?>">
			</div>
			<div class="form-group">
				<label for="urlimagen">URL Imagen</label>
				<input type="text" autocomplete="off" readonly class="form-control" id="urlimagen" name="urlimagen" value="<?php echo $producto['urlimagen'];  ?>">
			</div>
			<input name="uploadFile" id="uploadFile" class="file-loading" type="file" multiple data-min-file-count="1">
		</div>
	</div>
</form>
<script>

$("#uploadFile").fileinput({
	language: 'es',
	showRemove: false,
	uploadAsync: true,
	uploadExtraData: {
		accion: 'SUBIR_ARCHIVO', 
		idproducto: $('#idproducto').val()
	},
	uploadUrl: 'controlador/contProducto.php',
	maxFileCount: 1,
	autoReplace: true, 
	maxFileSize: 200,
	allowedFileExtensions: ['jpg','png','jpeg']
}).on('fileuploaded', function(event, data, id, index) {
	verListado();
  	CloseModal('divmodal1');
});


</script>