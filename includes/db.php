<?php
$host = "localhost";
$db = "prosiglu_db";
$user = "root";
$pass = "";

// Crear conexión
$conn = new mysqli($host, $user, $pass, $db);

// Verificar conexión
if ($conn->connect_error) {
    // Log the error and die to prevent further execution with a bad connection
    error_log("Conexión fallida: " . $conn->connect_error);
    die("Error de conexión. Por favor, intente más tarde."); // User-friendly message
}

// No cierres la conexión aquí. Los scripts que incluyan este archivo lo harán.
// if (isset($conn) && $conn instanceof mysqli) {
//    $conn->close(); // REMOVE THIS LINE
// }   
?>