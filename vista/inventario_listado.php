<?php
require_once('../modelo/clsProducto.php');

$objPro = new clsProducto();

$nombre = $_POST['nombre'];
$estado = $_POST['estado'];
$codigo = $_POST['codigo'];
$idcategoria = $_POST['idcategoria'];
$filtro = $_POST['filtro'];


$dataProducto = $objPro->listarProducto($nombre, $estado, $codigo, $idcategoria, $filtro);
$dataProducto = $dataProducto->fetchAll(PDO::FETCH_NAMED);


?>
<div class="table-responsive">
<table class="table table-hover text-nowrap table-striped table-sm" id="tablaProducto">
	<thead>
		<tr>
			<th>ID</th>
			<th>CODIGO</th>
			<th>PRODUCTO</th>
			<th>UNIDAD</th>
			<th>CATEGORIA</th>
			<th class="bg-maroon">STOCK</th>
			<th class="bg-maroon">STOCK SEGURIDAD</th>
			<th>ESTADO</th>
			<th>#</th>
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
			<td><?php echo $v['codigobarra']; ?></td>
			<td><?php echo $v['nombre']; ?></td>
			<td><?php echo $v['unidad']; ?></td>
			<td><?php echo $v['categoria']; ?></td>
			<td class="bg-maroon text-bold text-right"><?php echo $v['stock']; ?></td>
			<td class="bg-maroon text-bold text-right"><?php echo $v['stockseguridad']; ?></td>
			<td class="text-center">
				<?php
					if($v['stock']>=$v['stockseguridad']){
						echo '<i class="fa fa-thumbs-up fa-2x text-success"></i>';
					}else{
						echo '<i class="fa fa-thumbs-down fa-2x text-danger"></i>';
					}

				?>

				
			</td>
			<td>
				<button type="button" class="btn btn-info btn-sm" onclick="kardexProducto(<?php echo $v['idproducto']; ?>)" >Kardex</button>
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

    function kardexProducto(id){
		abrirModal('vista/inventario_kardex','idproducto='+id,'divmodal','Kardex del Producto');
	}

</script>