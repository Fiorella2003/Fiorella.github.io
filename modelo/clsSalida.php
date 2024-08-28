<?php
require_once('conexion.php');

class clsSalida
{

    function listarSalida($desde, $hasta, $cliente, $idusuario, $estado)
    {
        $sql = "SELECT
                    sa.idsalida,
                    DATE_FORMAT(sa.fecha,'%d/%m/%Y') as fecha,
                    cl.nombre as cliente,
                    us.nombre as trabajador,
                    sa.estado
                    -- GROUP_CONCAT('[',tbx.cantidad,']',' ',tbx.nombre SEPARATOR '<br>') as 'producto'

                FROM
                    salida sa
                LEFT JOIN cliente cl ON sa.idcliente = cl.idcliente
                INNER JOIN usuario us ON sa.idusuario = us.idusuario
                -- INNER JOIN (SELECT idsalida, desa.cantidad, pr.nombre FROM detallesa desa INNER JOIN producto pr ON desa.idproducto = pr.idproducto WHERE desa.estado<2) tbx ON tbx.idsalida = sa.idsalida

                WHERE
                    sa.estado < 2";
        $parametros = array();

        if ($desde != "") {
            $sql .= " AND sa.fecha >= :desde ";
            $parametros[':desde'] = $desde;
        }

        if ($hasta != "") {
            $sql .= " AND sa.fecha <= :hasta ";
            $parametros[':hasta'] = $hasta;
        }

        if ($cliente != "") {
            $sql .= " AND cl.nombre LIKE :cliente ";
            $parametros[':cliente'] = '%' . $cliente . '%';
        }

        if ($idusuario != "") {
            $sql .= " AND sa.idusuario = :idusuario ";
            $parametros[':idusuario'] = $idusuario;
        }

        if ($estado != "") {
            $sql .= " AND sa.estado = :estado ";
            $parametros[':estado'] = $estado;
        }

        $sql .= " ORDER BY sa.fecha DESC";

        global $cnx;
        $pre = $cnx->prepare($sql);
        $pre->execute($parametros);
        return $pre->fetchAll(PDO::FETCH_ASSOC);
    }

    function consultarSalida($idsalida)
    {
        $sql = "SELECT * FROM salida WHERE idsalida=? ";
        global $cnx;
        $pre = $cnx->prepare($sql);
        $pre->execute(array($idsalida));
        return $pre;
    }

    function consultarDetalleSalida($idsalida)
    {
        $sql = "SELECT d.*, p.nombre 
            FROM detallesa d INNER JOIN producto p ON d.idproducto=p.idproducto
            WHERE d.idsalida=? AND d.estado<>2 ORDER BY d.iddetalle ASC";
        global $cnx;
        $pre = $cnx->prepare($sql);
        $pre->execute(array($idsalida));
        return $pre;
    }

    function consultarSalidaExistente($idsalida = 0)
    {
        $sql = "SELECT * FROM salida WHERE idsalida<>?";
        global $cnx;
        $pre = $cnx->prepare($sql);
        $pre->execute(array($idsalida));
        return $pre;
    }

    function insertar($salida)
    {
        $sql = "INSERT INTO 
                salida(idsalida, fecha, idcliente, motivo, idusuario, estado) 
                VALUES (NULL,:fecha, :idcliente, :motivo, :idusuario, :estado)";
        global $cnx;
        $parametros = array(
            ":fecha"            => $salida["fecha"],
            ":idcliente"        => $salida["idcliente"],
            ":motivo"            => $salida["motivo"],
            ":idusuario"        => $_SESSION["idusuario"],
            ":estado"           => 1
        );
        $pre = $cnx->prepare($sql);
        $pre->execute($parametros);
        return $pre;
    }

    function insertarDetalle($detalle)
    {
        $sql = "INSERT INTO detallesa(idsalida, idproducto, cantidad, estado)
                VALUES (:idsalida, :idproducto, :cantidad, :estado)";
        global $cnx;
        $pre = $cnx->prepare($sql);

        foreach ($detalle as $k => $v) {
            $parametros = array(
                ":idsalida"      => $v["idsalida"],
                ":idproducto"   => $v["idproducto"],
                ":cantidad"     => $v["cantidad"],
                ":estado"       => 1
            );
            $pre->execute($parametros);
        }
    }

    function cambiarEstadoDetalle($idsalida, $estado)
    {
        $sql = "UPDATE detallesa SET estado=:estado WHERE idsalida=:idsalida AND estado<>2";
        global $cnx;
        $parametros = array(':idssalida' => $idsalida, ':estado' => $estado);
        $pre = $cnx->prepare($sql);
        $pre->execute($parametros);
        return $pre;
    }

    function actualizarEstado($idsalida, $estado)
    {
        $sql = "UPDATE salida SET estado=:estado WHERE idsalida=:idsalida";
        global $cnx;
        $parametros = array(":idsalida" => $idsalida, ":estado" => $estado);
        $pre = $cnx->prepare($sql);
        $pre->execute($parametros);
        return $pre;
    }

    function actualizar($salida)
    {
        $sql = "UPDATE salida 
                SET fecha=:fecha, idcliente=:idcliente, motivo=:motivo, 
                    idusuario=:idusuario, estado=:estado 
                WHERE idsalida=:idsalida";
        global $cnx;
        $parametros = array(
            ":idsalida"         => $salida["idsalida"],
            ":fecha"            => $salida["fecha"],
            ":idcliente"        => $salida["idcliente"],
            ":motivo"           => $salida["motivo"],
            ":idusuario"        => $_SESSION["idusuario"],
            ":estado"           => $salida["estado"]
        );
        $pre = $cnx->prepare($sql);
        $pre->execute($parametros);
        return $pre;
    }

    function listarSalidasPorProducto($idproducto, $desde, $hasta)
    {
        $sql = "SELECT s.idsalida, DATE_FORMAT(s.fecha,'%d/%m/%Y') fecha,
                        c.nombre cliente, d.cantidad,
                FROM salida s 
                INNER JOIN detallesa d ON s.idsalida=d.idsalida
                LEFT JOIN cliente c ON s.idcliente=c.idcliente
                WHERE s.estado=1 AND d.estado=1 AND d.idproducto=:idproducto ";
        $parametros = array(':idproducto' => $idproducto);
        if ($desde != "") {
            $sql .= " AND s.fecha>=:desde ";
            $parametros[':desde'] = $desde;
        }
        if ($hasta != "") {
            $sql .= " AND s.fecha<=:hasta ";
            $parametros[':hasta'] = $hasta;
        }

        $sql .= " ORDER BY s.fecha DESC";
        global $cnx;
        $pre = $cnx->prepare($sql);
        $pre->execute($parametros);
        return $pre;
    }


    function reporteSalidasPorMes($desde, $hasta, $idusuario)
    {
        $sql = "SELECT YEAR(fecha) as anio, MONTH(fecha) as mes, SUM(total) as total  FROM salida WHERE estado=1 ";
        $parametros = array();
        if ($desde != "") {
            $sql .= " AND fecha>= :desde ";
            $parametros[':desde'] = $desde;
        }

        if ($hasta != "") {
            $sql .= " AND fecha <= :hasta ";
            $parametros[':hasta'] = $hasta;
        }

        if ($idusuario != "") {
            $sql .= " AND idusuario = :idusuario ";
            $parametros[':idusuario'] = $idusuario;
        }

        $sql .= " GROUP BY YEAR(fecha), MONTH(fecha) ORDER BY YEAR(fecha) asc, MONTH(fecha) asc ";

        global $cnx;
        $pre = $cnx->prepare($sql);
        $pre->execute($parametros);
        return $pre;
    }

    function salidasResumenDia()
    {
        $sql = "SELECT COUNT(idsalida) as 'nro_salida'  FROM salida WHERE estado=1 AND fecha= CURDATE()";
        global $cnx;
        $pre = $cnx->query($sql);
        return $pre;
    }

    function productosMasEntregados(){
        $sql = "SELECT pr.nombre, SUM(de.cantidad) as 'cantidad' FROM detallesa de INNER JOIN salida sa ON de.idsalida = sa.idsalida INNER JOIN producto pr ON de.idproducto=pr.idproducto WHERE de.estado=1 AND sa.estado=1 AND sa.fecha = CURDATE() GROUP BY de.idproducto ORDER BY cantidad DESC LIMIT 10";

        global $cnx;
        $pre = $cnx->query($sql);
        return $pre;  
    }
}
