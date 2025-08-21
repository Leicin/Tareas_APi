<?php
require_once "configuracion/conexion.php";

$database = new Database();
$db = $database->ObtenerConexion();

if ($db) {
    echo " Conexión establecida correctamente.";
} else {
    echo " Error al conectar a la base de datos.";
}
2
?>