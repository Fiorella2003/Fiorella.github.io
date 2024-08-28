<?php
	require_once('../modelo/clsProducto.php');

	$objPro = new clsProducto();

	$id = $_GET['idproducto'];
	$producto = $objPro->consultarProductoPorId($id);
	$producto = $producto->fetch(PDO::FETCH_NAMED);

	$detalle = $objPro->detalleVentaPorProducto($id);
	$detalle = $detalle->fetchAll(PDO::FETCH_NAMED);
	

?>
<form name="formProducto" id="formProducto">
	<div class="row">
		<div class="col-md-12">
			<p><strong>CODIGO: </strong><?= $producto['codigobarra'] ?>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<strong>NOMBRE: </strong><?= $producto['nombre'] ?>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<strong>STOCK: </strong><?= $producto['stock'] ?></p>
		</div>
		<div class="col-md-12">
			<table class="table table-hover text-nowrap table-striped table-sm">
				<thead>
					<tr class="bg-maroon">
						<th>FECHA</th>
						<th>DOCUMENTO</th>
						<th>CLIENTE</th>
						<th>CANTIDAD</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($detalle as $k=>$v){ ?>
					<tr>
						<td><?= $v['fecha'] ?></td>
						<td>
							<?= $v['documento'].' '.$v['serie'].'-'.$v['correlativo'] ?>	
						</td>
						<td><?= $v['cliente'] ?></td>
						<td><?= $v['cantidad'] ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</form>
