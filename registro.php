<?php
session_start(); // Start session
// Incluye tu archivo de conexión a la base de datos
include 'includes/db.php'; // Asegúrate de que este archivo establece $conn

// Verifica si el formulario fue enviado usando el método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Obtener y Sanitizar Datos de Entrada
    $correo_ingresado = trim($_POST['correo']);
    $contrasena_plana = $_POST['contrasena'];
    $telefono = trim($_POST['telefono']);
    // Nuevos campos
    $ciudad = trim($_POST['ciudad']);
    $cp = trim($_POST['cp']);
    $direccion = trim($_POST['direccion']);

    // Usando el correo como nombre de usuario, según tu formulario/esquema actual
    $usuario = $correo_ingresado; // Consider if 'usuario' should be a separate field from 'email'

    // 2. Validar Entrada (Validación Básica)
    if (empty($correo_ingresado) || empty($contrasena_plana)) {
        // Redirect back to index with an error message for registration
        header("Location: index.php?registro_error=empty_required_fields#registro-container");
        exit();
    }

    if (!filter_var($correo_ingresado, FILTER_VALIDATE_EMAIL)) {
        header("Location: index.php?registro_error=invalid_email#registro-container");
        exit();
    }

    if (strlen($contrasena_plana) < 8) {
        header("Location: index.php?registro_error=password_short#registro-container");
        exit();
    }
    // Add more validation for new fields if necessary (e.g., CP format)

    // 3. Hash de la Contraseña
    $contrasena_hashed = password_hash($contrasena_plana, PASSWORD_BCRYPT);

    // Iniciar una transacción para asegurar la atomicidad de las operaciones
    $conn->begin_transaction();

    try {
        // 4. Preparar y Ejecutar la Consulta SQL INSERT para el Usuario
        // NOTA: Asegúrate de que los nombres de las columnas coincidan con tu tabla `usuarios`
        $sql_user = "INSERT INTO usuarios (usuario, email, telefono, password, ciudad, cp, direccion) VALUES (?, ?, ?, ?, ?, ?, ?)";

        if ($stmt_user = $conn->prepare($sql_user)) {
            // s = string. Adjust if any type is different.
            $stmt_user->bind_param("sssssss", $usuario, $correo_ingresado, $telefono, $contrasena_hashed, $ciudad, $cp, $direccion);

            if (!$stmt_user->execute()) {
                if ($conn->errno == 1062) { // Código de error MySQL para entrada duplicada (clave UNIQUE)
                    throw new Exception("Error: Este correo electrónico o nombre de usuario ya está registrado. Intenta iniciar sesión o usa otro correo.");
                } else {
                    throw new Exception("Error al registrar el usuario: " . $stmt_user->error);
                }
            }

            $new_user_id = $conn->insert_id;
            $stmt_user->close();

            // 5. Preparar y Ejecutar la Consulta SQL INSERT para el Carrito
            $sql_cart = "INSERT INTO carritos (usuario_id, fecha_creacion) VALUES (?, NOW())";

            if ($stmt_cart = $conn->prepare($sql_cart)) {
                $stmt_cart->bind_param("i", $new_user_id);

                if (!$stmt_cart->execute()) {
                    throw new Exception("Error al crear el carrito del usuario: " . $stmt_cart->error);
                }
                $stmt_cart->close();
            } else {
                throw new Exception("Error al preparar la consulta del carrito: " . $conn->error);
            }

            $conn->commit();

            // Redirect to index with a success message, prompting login
            header("Location: index.php?registro=exito#login-container");
            exit();

        } else {
            throw new Exception("Error al preparar la consulta de usuario: " . $conn->error);
        }

    } catch (Exception $e) {
        $conn->rollback();
        // Redirect back to index with a generic error or the specific one
        // For security, you might not want to expose detailed SQL errors to the user directly on the page.
        // Log the error and show a generic message.
        error_log("Registration error: " . $e->getMessage());
        header("Location: index.php?registro_error=" . urlencode($e->getMessage()) . "#registro-container");
        exit();
    } finally {
        if (isset($conn) && $conn instanceof mysqli) {
            $conn->close();
        }
    }

} else {
    header("Location: index.php");
    exit();
}
?>