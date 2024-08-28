<?php
require_once('../modelo/clsCategoria.php');

$objCat = new clsCategoria();

$nombre = $_POST['nombre'];
$estado = $_POST['estado'];


$dataCategoria = $objCat->listarCategoria($nombre, $estado);
$dataCategoria = $dataCategoria->fetchAll(PDO::FETCH_NAMED);

// echo '<pre>';
// print_r($dataCategoria);
// echo '</pre>';

?>
<div class="table-responsive">
<table class="table table-hover text-nowrap table-striped table-sm" id="tablaCategoria">
	<thead>
		<tr>
			<th>COD</th>
			<th>DESCRIPCION</th>
			<th>ESTADO</th>
			<th>EDITAR</th>
			<th>ANULAR</th>
			<th>ELIMINAR</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($dataCategoria as $k=>$v){ 
			if($v['estado']==1){
				$estado = "Activo";
				$class = "";
			}else{
				$estado = 'Anulado';
				$class = "text-danger";
			}
		?>
		<tr class="<?php echo $class; ?>">
			<td><?php echo $v['idcategoria']; ?></td>
			<td><?php echo $v['nombre']; ?></td>
			<td><?php echo $estado; ?></td>
			<td>
				<button type="button" class="btn btn-info btn-sm" data-toggle="tooltip" title="Editar Categoria" onclick="editarCategoria(<?php echo $v['idcategoria']; ?>)" ><i class="fa fa-edit"></i> </button>
			</td>
			<td>
				<?php if($v['estado']==1){ ?>
				<button type="button" class="btn btn-warning btn-sm" data-toggle="tooltip" title="Anular Categoria" onclick="cambiarEstadoCategoria(<?php echo $v['idcategoria']; ?>,0)"><i class="fa fa-trash"></i> </button>
				<?php }else{ ?>
				<button type="button" class="btn btn-success btn-sm" data-toggle="tooltip" title="Activar Categoria" onclick="cambiarEstadoCategoria(<?php echo $v['idcategoria']; ?>,1)"><i class="fa fa-check"></i> </button>
				<?php } ?>
			</td>
			<td>
				<button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" title="Eliminar Categoria" onclick="cambiarEstadoCategoria(<?php echo $v['idcategoria']; ?>,2)"><i class="fa fa-times"></i> </button>
			</td>
		</tr>
		<?php } ?>
	</tbody>
</table>
</div>
<script>
	
	function editarCategoria(id){
		abrirModal('vista/categorias_formulario','accion=ACTUALIZAR&&idcategoria='+id,'divmodal1','Editar Categoria');
	}

	function cambiarEstadoCategoria(idcategoria, estado){
		proceso = new Array('ANULAR','ACTIVAR','ELIMINAR');
		mensaje = "¿Estás Seguro de "+proceso[estado]+" la Categoria?";
		accion = "EjecutarCambiarEstadoCategoria("+idcategoria+","+estado+")";

		mostrarModalConfirmacion(mensaje, accion);
	}

	function EjecutarCambiarEstadoCategoria(idcategoria, estado) {

		$.ajax({
			method: 'POST',
			url: 'controlador/contCategoria.php',
			data: {
				accion: 'CAMBIAR_ESTADO_CATEGORIA',
				idcategoria: idcategoria,
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

	$("#tablaCategoria").DataTable({
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
    }).buttons().container().appendTo('#tablaCategoria_wrapper .col-md-6:eq(0)');

</script>