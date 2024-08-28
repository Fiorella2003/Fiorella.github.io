<?php
session_start();
if(!isset($_SESSION['idusuario'])){
	if($_POST['accion']!='INICIAR_SESION'){
		header('Location: ./'); //Regresa a la raiz
	}
}
	
$manejador = "mysql";
$servidor = "localhost";
$usuario = "root";
$pass = "";
$base = "pos";

$cadena = "$manejador:host=$servidor;dbname=$base";

$cnx = new PDO($cadena, $usuario, $pass, array(PDO::ATTR_PERSISTENT => "true", PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));


?>