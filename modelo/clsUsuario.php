<?php
require_once('conexion.php');

class clsUsuario{

	function verificarUsuario($usuario, $clave){
		$sql = "SELECT us.*, pe.nombre perfil FROM usuario us INNER JOIN perfil pe ON us.idperfil=pe.idperfil WHERE us.usuario=:usuario AND us.clave=SHA1(:clave)";
		$parametros = array(':clave'=>$clave,':usuario'=>$usuario);

		global $cnx;
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);

		return $pre;
	}

	function listarUsuario($nombre, $estado, $idperfil){
		$sql = "SELECT us.*, pe.nombre as 'perfil' FROM usuario us INNER JOIN perfil pe ON us.idperfil=pe.idperfil WHERE pe.estado<2";
		$parametros = array();
		
		if($nombre!=""){
			$sql .= " AND pe.nombre LIKE :nombre ";
			$parametros[':nombre'] = '%'.$nombre.'%';
		}

		if($estado!=""){
			$sql .= " AND pe.estado=:estado ";
			$parametros[':estado']=$estado;
		}

		if($idperfil!=""){
			$sql .= " AND pe.idperfil=:idperfil ";
			$parametros[':idperfil']=$idperfil;
		}

		global $cnx;
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);

		return $pre;
	}

	function insertarUsuario($nombre, $usuario, $clave, $idperfil, $estado){
		$sql = "INSERT INTO usuario VALUES(null, :nombre, :usuario, SHA1(:clave), :idperfil, :estado)";
		$parametros = array(':estado'=>$estado, ':nombre'=>$nombre, ':usuario'=>$usuario, ':clave'=>$clave, ':idperfil'=>$idperfil);

		global $cnx;
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);

		return $pre;
	}

	function verificarDuplicado($usuario, $idusuario=0){
		$sql = "SELECT idusuario, nombre FROM usuario WHERE usuario=:usuario AND estado<2 AND idusuario<>:idusuario";
		$parametros = array(':usuario'=>$usuario, ':idusuario'=>$idusuario);

		global $cnx;
		$pre= $cnx->prepare($sql);
		$pre->execute($parametros);

		return $pre;
	}

	function consultarUsuarioPorId($idusuario){
		$sql = "SELECT * FROM usuario WHERE idusuario=:idusuario";
		$parametros = array(':idusuario'=>$idusuario);

		global $cnx;
		$pre= $cnx->prepare($sql);
		$pre->execute($parametros);

		return $pre;
	}

	function actualizarUsuario($nombre, $usuario, $clave, $idperfil, $estado, $idusuario){
		$sql = "UPDATE usuario SET nombre=:nombre, usuario=:usuario, estado=:estado, idperfil=:idperfil ";
		$parametros = array(':nombre'=>$nombre, ':estado'=>$estado, ':idperfil'=>$idperfil, ':usuario'=>$usuario, ':idusuario'=>$idusuario);

		if($clave!=""){
			$sql .= ", clave = SHA1(:clave) ";
			$parametros[':clave'] = $clave;
		}

		$sql .= " WHERE idusuario = :idusuario ";

		global $cnx;
		$pre= $cnx->prepare($sql);
		$pre->execute($parametros);

		return $pre;
	}

	function actualizarEstadoUsuario($idusuario, $estado){
		$sql = "UPDATE usuario SET estado=:estado WHERE idusuario=:idusuario";
		$parametros = array(':estado'=>$estado, ':idusuario'=>$idusuario);

		global $cnx;
		$pre= $cnx->prepare($sql);
		$pre->execute($parametros);

		return $pre;
	}

}


?>