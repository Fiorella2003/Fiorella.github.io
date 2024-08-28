<?php
include_once("../modelo/clsProducto.php");
include_once("../modelo/clsSalida.php");
include_once("../modelo/clsCliente.php");

$objSalida = new clsSalida();
$objClie = new clsCliente();

$documentos = $objClie->listaTipoDocumento();


$objPro = new clsProducto();
$productos = $objPro->listarProducto("", 1, "", "");

if (isset($_GET['limpiar_sesion'])) {
    if ($_GET['limpiar_sesion'] == 1) {
        $_SESSION['carrito'] = array();
    }
}

$idsalida = 0;
if (isset($_POST['idsalida'])) {
    $idsalida = $_POST['idsalida'];
}

$textoBoton = "Guardar";
$salida = NULL;
$cliente = NULL;
$idcliente = 0;
if ($idsalida > 0) {
    $textoBoton = "Actualizar";
    $salida = $objSalida->consultarSalida($idsalida);
    if ($salida->rowCount() > 0) {
        $salida = $salida->fetch(PDO::FETCH_NAMED);
        $idcliente = $salida['idcliente'];
    }
    $cliente = $objClie->consultarClientePorId($idcliente);
    if ($cliente->rowCount() > 0) {
        $cliente = $cliente->fetch(PDO::FETCH_NAMED);
    } else {
        $cliente = NULL;
    }
}

?>

<style>
    /* Mover el contenedor de búsqueda a la izquierda */
    #tablaProducto_wrapper .dataTables_filter {
        float: left; /* Mueve el contenedor a la izquierda */
        margin-left: 0; /* Ajusta el margen para mover más a la izquierda */
        margin-bottom: 10px; /* Añadir margen inferior si es necesario */
    }

    #tablaProducto_wrapper .dataTables_filter {
        float: none;
        position: relative;
        left: -250px; /* Ajusta este valor según sea necesario */
    }
</style>

<section class="content mt-2">
    <div class="container-fluid">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">NUEVA SALIDA</h3>
            </div>
            <div class="card-body">
                <form id="frmSalida" name="frmSalida">
                    <div class="row">
                        <div class="col-4">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Fecha</span>
                                </div>
                                <input type="date" class="form-control" id="fecha" name="fecha" value="<?= $idsalida > 0 ? $salida['fecha'] : date('Y-m-d') ?>" />
                                <input type="hidden" id="idsalida" name="idsalida" value="<?= $idsalida ?>" />
                            </div>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Motivo</span>
                                </div>
                                <input type="text" class="form-control" id="motivo" name="motivo" value="" />
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Tipo Doc</span>
                                </div>
                                <select class="form-control" name="idtipodocumento" id="idtipodocumento">
                                    <?php foreach ($documentos as $k => $v) { ?>
                                        <option value="<?= $v['idtipodocumento']; ?>"><?= $v['nombre'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Nro Doc</span>
                                </div>
                                <input type="text" class="form-control" id="nrodocumento" name="nrodocumento" onblur="BuscarClienteDocumento()" />
                                <span class="input-group-append">
                                    <button type="button" class="btn btn-primary" onclick="BuscarClienteDocumento()">
                                        <span class="fas fa-search"></span>
                                    </button>
                                </span>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Cliente</span>
                                </div>
                                <input type="text" class="form-control" id="nombre" name="nombre" />
                            </div>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Dirección</span>
                                </div>
                                <input type="text" class="form-control" id="direccion" name="direccion" />
                            </div>
                        </div>
                    </div>
                </form>
                <div class="row">
                    <div class="col-6 mt-4">
                        <table class="table table-bordered table-hover table-sm text-sm table-striped"
                            id="tablaProducto">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Producto</th>
                                    <th>Stock</th>
                                    <th>Agregar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($productos as $k => $v) { ?>
                                    <tr>
                                        <td><?= $v['codigobarra'] ?></td>
                                        <td><?= $v['nombre'] ?></td>
                                        <td><?= $v['stock'] ?></td>
                                        <td>
                                            <button
                                                type="button"
                                                class="btn btn-xs btn-warning"
                                                onclick="AgregarProducto(<?= $v['idproducto'] ?>)">Agregar</button>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-6">
                        <div id="divCarritoVenta" style="margin-left: 60px; margin-top: 75px; ">

                        </div>
                        <div align="right">
                            <button type="button" class="btn bg-maroon" onclick="LimpiarDetalleSesion()">Limpiar</button>
                        </div>
                    </div>
                    <div class="col-12" align="center">
                        <button type="button" class="btn btn-primary" onclick="GuardarSalida()"><?= $textoBoton ?></button>
                        <button type="button" class="btn btn-default" onclick="CancelarSalida()">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</div>

<!-- /.modal -->

<div class="modal fade" id="modalItem">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title">Categoría</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="frmItem" name="frmItem">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group d-none">
                                <label>Código</label>
                                <input type="text" class="form-control" name="item_codigo" id="item_codigo" readonly />
                                <input type="hidden" name="item" id="item" />
                            </div>
                            <div class="form-group">
                                <label>Producto</label>
                                <input type="text" class="form-control" name="item_nombre" id="item_nombre" readonly />
                            </div>
                            <div class="form-group">
                                <label>Cantidad (Stock <span id='spanStock'></span>)</label>
                                <input type="text" class="form-control" name="item_cantidad" id="item_cantidad" />
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="ActualizarItem()">Aceptar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->

    <script>
        <?php if ($idsalida > 0) { ?>
            $("#motivo").val('<?= $salida['motivo'] ?>');
            <?php if ($cliente) { ?>
                $("#idtipodocumento").val("<?= $cliente['idtipodocumento'] ?>");
                $("#nrodocumento").val("<?= $cliente['nrodocumento'] ?>");
                $("#nombre").val("<?= $cliente['nombre'] ?>");
                $("#direccion").val("<?= $cliente['direccion'] ?>");
            <?php } ?>

        <?php } ?>



        function BuscarClienteDocumento() {
            if ($("#nrodocumento").val() != "") {
                $.ajax({
                    method: "POST",
                    url: "controlador/contCliente.php",
                    data: {
                        accion: "CONSULTAR_DATOS_WS",
                        idtipodocumento: $("#idtipodocumento").val(),
                        nrodocumento: $("#nrodocumento").val()
                    },
                    dataType: "json"
                }).done(function(resultado) {
                    if (resultado["nombre"] != "") {
                        toastCorrecto("Cliente localizado");
                        $("#idtipodocumento").val(resultado['idtipodocumento']);
                        $("#nombre").val(resultado['nombre']);
                        $("#direccion").val(resultado['direccion']);
                    } else {
                        msjError = "No se localizó cliente."
                        toastError(msjError);
                    }
                });
            }
        }

        $('#tablaProducto').DataTable({
            "paging": false,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "order": [
                [1, 'asc']
            ],
            "info": true,
            "autoWidth": true,
            "responsive": true
        });

        function AgregarProducto(idproducto) {
            $.ajax({
                method: "POST",
                url: "controlador/contSalida.php",
                data: {
                    "accion": "AGREGAR_PRODUCTO",
                    "idproducto": idproducto
                }
            }).done(function(resultado) {
                verCarrito();
            });
        }

        function verCarrito(idsalida = 0) {
            $.ajax({
                method: "POST",
                url: "controlador/contSalida.php",
                data: {
                    "accion": "VER_CARRITO",
                    "idsalida": idsalida
                }
            }).done(function(resultado) {
                $("#divCarritoVenta").html(resultado);
            });
        }

        verCarrito(<?= $idsalida ?>);

        function LimpiarDetalleSesion() {
            $.ajax({
                method: "POST",
                url: "controlador/contSalida.php",
                data: {
                    "accion": "ELIMINAR_CARRITO"
                }
            }).done(function(resultado) {
                verCarrito();
            });
        }

        function ActualizarItem() {
            $.ajax({
                method: "POST",
                url: "controlador/contSalida.php",
                data: {
                    "accion": "ACTUALIZAR_ITEM",
                    "item": $("#item").val(),
                    "cantidad": $("#item_cantidad").val(),
                }
            }).done(function(resultado) {
                verCarrito();
                $("#modalItem").modal('hide');
            });
        }

        function EliminarItem(item) {
            $.ajax({
                method: "POST",
                url: "controlador/contSalida.php",
                data: {
                    "accion": "ELIMINAR_ITEM",
                    "item": item
                }
            }).done(function(resultado) {
                verCarrito();
            });
        }

        function EditarItem(item) {
            $.ajax({
                method: "POST",
                url: "controlador/contSalida.php",
                data: {
                    "accion": "OBTENER_ITEM",
                    "item": item
                },
                dataType: "json"
            }).done(function(resultado) {
                $("#item").val(item);
                $("#item_codigo").val(resultado.codigo);
                $("#item_nombre").val(resultado.nombre);
                $("#item_cantidad").val(resultado.cantidad);
                $("#spanStock").html(resultado.stock);
                $("#modalItem").modal('show');
            });
        }

        function CancelarSalida() {
            <?php if ($idsalida > 0) { ?>
                AbrirPagina('vista/salidas.php');
            <?php } else { ?>
                AbrirPagina('vista/salidas_formulario.php?limpiar_sesion=1');
            <?php } ?>
        }

        function GuardarSalida() {

            var datos_formulario = $("#frmSalida").serializeArray();

            if ($("#idsalida").val() != "" && $("#idsalida").val() != "0") {
                datos_formulario.push({
                    name: "accion",
                    value: "ACTUALIZAR"
                });
            } else {
                datos_formulario.push({
                    name: "accion",
                    value: "NUEVO"
                });
            }

            $.ajax({
                method: "POST",
                url: "controlador/contSalida.php",
                data: datos_formulario,
                dataType: 'json'
            }).done(function(resultado) {
                console.log(resultado)
                if (resultado.codigoError == 1) {
                    toastCorrecto("Registro satisfactorio");
                    CancelarSalida();
                } else if (resultado.codigoError == 99) {
                    toastError("Existe problemas de stock de algunos productos");
                    for (i = 0; i < resultado.problemasStock.length; i++) {
                        $("#trcarrito" + resultado.problemasStock[i].item).addClass("bg-danger");
                    }
                } else {
                    msjError = resultado == 2 ? "Salida duplicada" : "No se pudo registrar la salida.";
                    toastError(msjError);
                }
            });
        }
    </script>