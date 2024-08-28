<?php
require_once('../modelo/clsUsuario.php');

$objUsu = new clsUsuario();

$nombre = $_POST['nombre'];
$estado = $_POST['estado'];
$idperfil = $_POST['idperfil'];


$dataUsuario = $objUsu->listarUsuario($nombre, $estado, $idperfil);
$dataUsuario = $dataUsuario->fetchAll(PDO::FETCH_NAMED);

?>
<div class="table-responsive">
<table class="table table-hover text-nowrap table-striped table-sm" id="tablaUsuario">
	<thead>
		<tr>
			<th>COD</th>
			<th>NOMBRE</th>
			<th>USUARIO</th>
			<th>PERFIL</th>
			<th>ESTADO</th>
			<th>EDITAR</th>
			<th>ANULAR</th>
			<th>ELIMINAR</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($dataUsuario as $k=>$v){ 
			if($v['estado']==1){
				$estado = "Activo";
				$class = "";
			}else{
				$estado = 'Anulado';
				$class = "text-danger";
			}
		?>
		<tr class="<?php echo $class; ?>">
			<td><?php echo $v['idusuario']; ?></td>
			<td><?php echo $v['nombre']; ?></td>
			<td><?php echo $v['usuario']; ?></td>
			<td><?php echo $v['perfil']; ?></td>
			<td><?php echo $estado; ?></td>
			<td>
				<button type="button" class="btn btn-info btn-sm" data-toggle="tooltip" title="Editar Usuario" onclick="editarUsuario(<?php echo $v['idusuario']; ?>)" ><i class="fa fa-edit"></i> </button>
			</td>
			<td>
				<?php if($v['estado']==1){ ?>
				<button type="button" class="btn btn-warning btn-sm" data-toggle="tooltip" title="Anular Usuario" onclick="cambiarEstadoUsuario(<?php echo $v['idusuario']; ?>,0)"><i class="fa fa-trash"></i> </button>
				<?php }else{ ?>
				<button type="button" class="btn btn-success btn-sm" data-toggle="tooltip" title="Activar Usuario" onclick="cambiarEstadoUsuario(<?php echo $v['idusuario']; ?>,1)"><i class="fa fa-check"></i> </button>
				<?php } ?>
			</td>
			<td>
				<button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" title="Eliminar Usuario" onclick="cambiarEstadoUsuario(<?php echo $v['idusuario']; ?>,2)"><i class="fa fa-times"></i> </button>
			</td>
		</tr>
		<?php } ?>
	</tbody>
</table>
</div>
<script>
	
	function editarUsuario(id){
		abrirModal('vista/usuarios_formulario','accion=ACTUALIZAR&&idusuario='+id,'divmodal1','Editar Usuario');
	}

	function cambiarEstadoUsuario(idusuario, estado){
		proceso = new Array('ANULAR','ACTIVAR','ELIMINAR');
		mensaje = "¿Estás Seguro de "+proceso[estado]+" el Perfil?";
		accion = "EjecutarCambiarEstadoUsuario("+idusuario+","+estado+")";

		mostrarModalConfirmacion(mensaje, accion);
	}

	function EjecutarCambiarEstadoUsuario(idusuario, estado) {

		$.ajax({
			method: 'POST',
			url: 'controlador/contUsuario.php',
			data: {
				accion: 'CAMBIAR_ESTADO_USUARIO',
				idusuario: idusuario,
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

	$("#tablaUsuario").DataTable({
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
    }).buttons().container().appendTo('#tablaUsuario_wrapper .col-md-6:eq(0)');

</script>