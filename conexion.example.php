<?php

$servidor = "localhost";
$usuario = "cruduser";
$password = "Crud2026!";
$base_datos = "sistema_registro";

$conn = new mysqli(
    $servidor,
    $usuario,
    $password,
    $base_datos
);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$conn->set_charset("utf8");

?>
