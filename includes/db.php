<?php
$host = "localhost";
$db = "prosiglu_db";
$user = "root";
$pass = "";

// Crear conexión
$conn = new mysqli($host, $user, $pass, $db);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
