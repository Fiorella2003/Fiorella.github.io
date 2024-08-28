<?php
require_once('../modelo/clsSalida.php');

$objSalida = new clsSalida();

$desde = $_POST['desde'];
$hasta = $_POST['hasta'];
$cliente = $_POST['cliente'];
$idusuario = $_POST['idusuario'];
$estado = $_POST['estado'];


$dataSalida = $objSalida->listarSalida($desde, $hasta, $cliente, $idusuario, $estado);
// $dataSalida = $dataSalida->fetchAll(PDO::FETCH_NAMED);


?>
<table class="table table-hover text-nowrap table-striped table-sm" id="tablaSalida">
	<thead>
		<th>COD</th>
		<th>FECHA</th>
		<th>CLIENTE</th>
		<th>PRODUCTOS</th>
		<th>ESTADO</th>
		<th>EDITAR</th>
		<th>ANULAR</th>
		<th>ELIMINAR</th>
	</thead>
	<tbody>
		<?php foreach ($dataSalida as $k => $v) {
			if ($v['estado'] == 1) {
				$estado = "Activo";
				$class = "";
			} else {
				$estado = 'Anulado';
				$class = "text-danger";
			}

		?>
			<tr class="<?php echo $class; ?>">
				<td><?php echo $v['idsalida']; ?></td>
				<td><?php echo $v['fecha']; ?></td>
				<td><?= $v['cliente'] ?></td>
				<td>
					<!-- <?php
							$maximo = 15;
							echo substr($v['producto'], 0, $maximo);
							if (strlen($v['producto']) > $maximo) {
								echo '<b>...</b>';
							}


							?> -->
				</td>
				<td><?php echo $estado; ?></td>

				<td class="text-center">
					<button type="button" class="btn btn-info btn-sm" data-toggle="tooltip" title="Editar Salida" onclick="EditarSalida(<?= $v['idsalida']; ?>)"><i class="fa fa-edit"></i> </button>
				</td>
				<td class="text-center">
					<?php if ($v['estado'] == 1) { ?>
						<button type="button" class="btn btn-warning btn-sm" data-toggle="tooltip" title="Anular Salida" onclick="CambiarEstadoSalida(<?= $v['idsalida']; ?>,0)"><i class="fa fa-trash"></i> </button>
					<?php } else { ?>
						<button type="button" class="btn btn-success btn-sm" data-toggle="tooltip" title="Activar Salida" onclick="CambiarEstadoSalida(<?= $v['idsalida']; ?>,1)"><i class="fa fa-check"></i> </button>
					<?php } ?>
				</td>
				<td class="text-center">
					<button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" title="Eliminar Salida" onclick="CambiarEstadoSalida(<?= $v['idsalida']; ?>,2)"><i class="fa fa-times"></i> </button>
				</td>
			</tr>
		<?php } ?>
	</tbody>
</table>
<script>
	function EditarSalida(idsalida) {
		$.ajax({
			method: "POST",
			url: "vista/salidas_formulario.php",
			data: {
				'proceso': "EDITAR",
				'idsalida': idsalida
			}
		}).done(function(resultado) {
			$("#divPrincipal").html(resultado);
		});
	}

	function CambiarEstadoSalida(idsalida, estado) {
		proceso = estado == 0 ? "ANULAR" : (estado == 1 ? "ACTIVAR" : "ELIMINAR");
		mensaje = "¿Esta seguro de <b>" + proceso + "</B> la salida?";
		accion = "EjecutarCambiarEstadoSalida(" + idsalida + ",'" + proceso + "')";
		mostrarModalConfirmacion(mensaje, accion);
	}

	function EjecutarCambiarEstadoSalida(idsalida, proceso) {
		$.ajax({
			method: "POST",
			url: "controlador/contSalida.php",
			data: {
				'accion': proceso,
				'idsalida': idsalida
			}
		}).done(function(resultado) {
			if (resultado == 1) {
				toastCorrecto("Cambio de estado satisfactorio.");
				verListado();
			} else if (resultado == 0) {
				toastError("Problemas en la actualización de estado. Inténtelo nuevamente.");
			} else {
				toastError(resultado);
			}
		});
	}

	$("#tablaSalida").DataTable({
		"responsive": true,
		"lengthChange": true,
		"autoWidth": false,
		"searching": false,
		"ordering": true,
		//Mantener la Cabecera de la tabla Fija
		// "scrollY": '200px',
		// "scrollCollapse": true,
		// "paging": false,
		"lengthMenu": [
			[10, 25, 50, 100, -1],
			[10, 25, 50, 100, "Todos"]
		],
		"language": {
			"decimal": "",
			"emptyTable": "Sin datos",
			"info": "Del _START_ al _END_ de _TOTAL_ filas",
			"infoEmpty": "Del 0 a 0 de 0 filas",
			"infoFiltered": "(filtro de _MAX_ filas totales)",
			"infoPostFix": "",
			"thousands": ",",
			"lengthMenu": "Ver _MENU_ filas",
			"loadingRecords": "Cargando...",
			"processing": "Procesando...",
			"search": "Buscar:",
			"zeroRecords": "No se encontraron resultados",
			"paginate": {
				"first": "Primero",
				"last": "Ultimo",
				"next": "Siguiente",
				"previous": "Anterior"
			},
			"aria": {
				"sortAscending": ": orden ascendente",
				"sortDescending": ": orden descendente"
			}
		},
		"buttons": ["excel", "pdf"]
	}).buttons().container().appendTo('#tablaCategoria_wrapper .col-md-6:eq(0)');
</script>