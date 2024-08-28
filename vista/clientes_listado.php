<?php
require_once('../modelo/clsCliente.php');

$objCli = new clsCliente();

$nombre = $_POST['nombre'];
$estado = $_POST['estado'];
$idtipodocumento = $_POST['idtipodocumento'];
$documento = $_POST['documento'];

$dataCliente = $objCli->listarCliente($nombre, $estado, $idtipodocumento, $documento);
$dataCliente = $dataCliente->fetchAll(PDO::FETCH_NAMED);


?>

<table class="table table-hover text-nowrap table-striped table-sm" id="tablaCliente">
	<thead>
		<tr>
			<th>COD</th>
			<th>NOMBRE</th>
			<th>NRO. DOC.</th>
			<th>DIRECCION</th>
			<th>ESTADO</th>
			<th>EDITAR</th>
			<th>ANULAR</th>
			<th>ELIMINAR</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($dataCliente as $k=>$v){ 
			if($v['estado']==1){
				$estado = "Activo";
				$class = "";
			}else{
				$estado = 'Anulado';
				$class = "text-danger";
			}
		?>
		<tr class="<?php echo $class; ?>">
			<td><?php echo $v['idcliente']; ?></td>
			<td><?php echo $v['nombre']; ?></td>
			<td><?= $v['nrodocumento'] ?></td>
			<td><?= $v['direccion'] ?></td>
			<td><?php echo $estado; ?></td>
			<td>
				<button type="button" class="btn btn-info btn-sm" data-toggle="tooltip" title="Editar Cliente" onclick="editarCliente(<?php echo $v['idcliente']; ?>)" ><i class="fa fa-edit"></i></button>
			</td>
			<td>
				<?php if($v['estado']==1){ ?>
				<button type="button" class="btn btn-warning btn-sm" data-toggle="tooltip" title="Anular Categoria" onclick="cambiarEstadoCliente(<?php echo $v['idcliente']; ?>,0)"><i class="fa fa-trash"></i> </button>
				<?php }else{ ?>
				<button type="button" class="btn btn-success btn-sm" data-toggle="tooltip" title="Activar Categoria" onclick="cambiarEstadoCliente(<?php echo $v['idcliente']; ?>,1)"><i class="fa fa-check"></i> </button>
				<?php } ?>
			</td>
			<td>
				<button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" title="Eliminar Categoria" onclick="cambiarEstadoCliente(<?php echo $v['idcliente']; ?>,2)"><i class="fa fa-times"></i> </button>
			</td>
		</tr>
		<?php } ?>
	</tbody>
</table>

<script>
	
	function editarCliente(id){
		abrirModal('vista/clientes_formulario','accion=ACTUALIZAR&&idcliente='+id,'divmodal1','Editar Cliente');
	}

	function cambiarEstadoCliente(idcliente, estado){
		proceso = new Array('ANULAR','ACTIVAR','ELIMINAR');
		mensaje = "¿Estás Seguro de "+proceso[estado]+" el Cliente?";
		accion = "EjecutarCambiarEstadoCliente("+idcliente+","+estado+")";

		mostrarModalConfirmacion(mensaje, accion);
	}

	function EjecutarCambiarEstadoCliente(idcliente, estado) {

		$.ajax({
			method: 'POST',
			url: 'controlador/contCliente.php',
			data: {
				accion: 'CAMBIAR_ESTADO_CLIENTE',
				idcliente: idcliente,
				estado: estado
			},
			dataType: 'json'
		})
		.done(function(resultado){
			if(resultado.correcto==1){
				toastCorrecto(resultado.mensaje);
				verListado();
			}else{
				toastError(resultado.mensaje);
			}
		})
	}

	$("#tablaCliente").DataTable({
      "responsive": true, 
      "lengthChange": true, 
      "autoWidth": false,
      "ordering": true,
      "searching": false,
      "lengthMenu": [[10,25,50,100,-1],[10,25,50,100,'Todos']],
      "language": {
			"decimal":        "",
		    "emptyTable":     "Sin datos",
		    "info":           "Del _START_ al _END_ de _TOTAL_ filas",
		    "infoEmpty":      "Del 0 a 0 de 0 filas",
		    "infoFiltered":   "(filtro de _MAX_ filas totales)",
		    "infoPostFix":    "",
		    "thousands":      ",",
		    "lengthMenu":     "Ver _MENU_ filas",
		    "loadingRecords": "Cargando...",
		    "processing":     "Procesando...",
		    "search":         "Buscar:",
		    "zeroRecords":    "No se encontraron resultados",
		    "paginate": {
		        "first":      "Primero",
		        "last":       "Ultimo",
		        "next":       "Siguiente",
		        "previous":   "Anterior"
		    },
		    "aria": {
		        "sortAscending":  ": orden ascendente",
		        "sortDescending": ": orden descendente"
		    }
		},
		"buttons": ["excel", "pdf"]
    }).buttons().container().appendTo('#tablaCliente_wrapper .col-md-6:eq(0)');

</script>