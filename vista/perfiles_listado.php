<?php
require_once('../modelo/clsPerfil.php');

$objPer = new clsPerfil();

$nombre = $_POST['nombre'];
$estado = $_POST['estado'];


$dataPerfil = $objPer->listarPerfil($nombre, $estado);
$dataPerfil = $dataPerfil->fetchAll(PDO::FETCH_NAMED);

?>
<div class="table-responsive">
<table class="table table-hover text-nowrap table-striped table-sm" id="tablaPerfil">
	<thead>
		<tr>
			<th>COD</th>
			<th>DESCRIPCION</th>
			<th>ESTADO</th>
			<th>PERMISOS</th>
			<th>EDITAR</th>
			<th>ANULAR</th>
			<th>ELIMINAR</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($dataPerfil as $k=>$v){ 
			if($v['estado']==1){
				$estado = "Activo";
				$class = "";
			}else{
				$estado = 'Anulado';
				$class = "text-danger";
			}
		?>
		<tr class="<?php echo $class; ?>">
			<td><?php echo $v['idperfil']; ?></td>
			<td><?php echo $v['nombre']; ?></td>
			<td><?php echo $estado; ?></td>
			<td>
				<button type="button" class="btn bg-maroon btn-sm" data-toggle="tooltip" title="Asignar Permisos" onclick="asignarPermisos(<?php echo $v['idperfil']; ?>)" ><i class="fa fa-lock"></i> Permisos</button>
			</td>
			<td>
				<button type="button" class="btn btn-info btn-sm" data-toggle="tooltip" title="Editar Perfil" onclick="editarPerfil(<?php echo $v['idperfil']; ?>)" ><i class="fa fa-edit"></i> </button>
			</td>
			<td>
				<?php if($v['estado']==1){ ?>
				<button type="button" class="btn btn-warning btn-sm" data-toggle="tooltip" title="Anular Perfil" onclick="cambiarEstadoPerfil(<?php echo $v['idperfil']; ?>,0)"><i class="fa fa-trash"></i> </button>
				<?php }else{ ?>
				<button type="button" class="btn btn-success btn-sm" data-toggle="tooltip" title="Activar Perfil" onclick="cambiarEstadoPerfil(<?php echo $v['idperfil']; ?>,1)"><i class="fa fa-check"></i> </button>
				<?php } ?>
			</td>
			<td>
				<button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" title="Eliminar Perfil" onclick="cambiarEstadoPerfil(<?php echo $v['idperfil']; ?>,2)"><i class="fa fa-times"></i> </button>
			</td>
		</tr>
		<?php } ?>
	</tbody>
</table>
</div>
<script>
	
	function editarPerfil(id){
		abrirModal('vista/perfiles_formulario','accion=ACTUALIZAR&&idperfil='+id,'divmodal1','Editar Perfil');
	}

	function cambiarEstadoPerfil(idperfil, estado){
		proceso = new Array('ANULAR','ACTIVAR','ELIMINAR');
		mensaje = "¿Estás Seguro de "+proceso[estado]+" el Perfil?";
		accion = "EjecutarCambiarEstadoPerfil("+idperfil+","+estado+")";

		mostrarModalConfirmacion(mensaje, accion);
	}

	function EjecutarCambiarEstadoPerfil(idperfil, estado) {

		$.ajax({
			method: 'POST',
			url: 'controlador/contPerfil.php',
			data: {
				accion: 'CAMBIAR_ESTADO_PERFIL',
				idperfil: idperfil,
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

	$("#tablaPerfil").DataTable({
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
    }).buttons().container().appendTo('#tablaPerfil_wrapper .col-md-6:eq(0)');

    function asignarPermisos(id){
		abrirModal('vista/perfiles_permiso','idperfil='+id,'divmodal1','Asignar Permisos');
	}

</script>