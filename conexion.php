<?php
$host = "localhost";
$usuario = "root"; // usuario por defecto en XAMPP
$contrasena = "";  // contraseña por defecto es vacía
$basedatos = "prosiglu_db"; // usa el nombre que venga en el archivo .sql

$conn = new mysqli($host, $usuario, $contrasena, $basedatos);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
