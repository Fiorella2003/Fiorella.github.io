<?php
require_once('../modelo/clsProducto.php');

$objPro = new clsProducto();

$nombre = $_POST['nombre'];
$estado = $_POST['estado'];
$codigo = $_POST['codigo'];
$idcategoria = $_POST['idcategoria'];


$dataProducto = $objPro->listarProducto($nombre, $estado, $codigo, $idcategoria);
$dataProducto = $dataProducto->fetchAll(PDO::FETCH_NAMED);


?>
<div class="table-responsive">
<table class="table table-hover text-nowrap table-striped table-sm" id="tablaProducto">
	<thead>
		<tr>
			<th>COD</th>
			<th>IMAGEN</th>
			<th>CODIGO</th>
			<th>PRODUCTO</th>
			<th>UNIDAD</th>
			<th>CATEGORIA</th>
			<th>ESTADO</th>
			<th>IMAGEN</th>
			<th>EDITAR</th>
			<th>ANULAR</th>
			<th>ELIMINAR</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($dataProducto as $k=>$v){ 
			if($v['estado']==1){
				$estado = "Activo";
				$class = "";
			}else{
				$estado = 'Anulado';
				$class = "text-danger";
			}
		?>
		<tr class="<?php echo $class; ?>">
			<td><?php echo $v['idproducto']; ?></td>
			<td>
				<img src="<?php echo $v['urlimagen']; ?>" style="width: 40px; height: 40px;">
			</td>
			<td><?php echo $v['codigobarra']; ?></td>
			<td><?php echo $v['nombre']; ?></td>
			<td><?php echo $v['unidad']; ?></td>
			<td><?php echo $v['categoria']; ?></td>
			<td><?php echo $estado; ?></td>
			<td>
				<button type="button" class="btn btn-secondary btn-sm" onclick="subirImagen(<?php echo $v['idproducto']; ?>)"></i> Subir</button>
			</td>
			<td>
				<button type="button" class="btn btn-info btn-sm" data-toggle="tooltip" title="Editar Producto" onclick="editarProducto(<?php echo $v['idproducto']; ?>)" ><i class="fa fa-edit"></i> </button>
			</td>
			<td>
				<?php if($v['estado']==1){ ?>
				<button type="button" class="btn btn-warning btn-sm" data-toggle="tooltip" title="Anular Producto" onclick="cambiarEstadoProducto(<?php echo $v['idproducto']; ?>,0)"><i class="fa fa-trash"></i> </button>
				<?php }else{ ?>
				<button type="button" class="btn btn-success btn-sm" data-toggle="tooltip" title="Activar Producto" onclick="cambiarEstadoProducto(<?php echo $v['idproducto']; ?>,1)"><i class="fa fa-check"></i> </button>
				<?php } ?>
			</td>
			<td>
				<button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" title="Eliminar Producto" onclick="cambiarEstadoProducto(<?php echo $v['idproducto']; ?>,2)"><i class="fa fa-times"></i> </button>
			</td>
		</tr>
		<?php } ?>
	</tbody>
</table>
</div>
<script>
	
	function editarProducto(id){
		abrirModal('vista/productos_formulario','accion=ACTUALIZAR&&idproducto='+id,'divmodal','Editar Producto');
	}

	function cambiarEstadoProducto(idproducto, estado){
		proceso = new Array('ANULAR','ACTIVAR','ELIMINAR');
		mensaje = "¿Estás Seguro de "+proceso[estado]+" el Producto?";
		accion = "EjecutarCambiarEstadoProducto("+idproducto+","+estado+")";

		mostrarModalConfirmacion(mensaje, accion);
	}

	function EjecutarCambiarEstadoProducto(idproducto, estado) {

		$.ajax({
			method: 'POST',
			url: 'controlador/contProducto.php',
			data: {
				accion: 'CAMBIAR_ESTADO_PRODUCTO',
				idproducto: idproducto,
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

	$("#tablaProducto").DataTable({
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
    }).buttons().container().appendTo('#tablaProducto_wrapper .col-md-6:eq(0)');

    function subirImagen(id){
		abrirModal('vista/productos_imagen','idproducto='+id,'divmodal1','Subir Imagen Producto');
	}

</script>