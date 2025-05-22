<?php
session_start();
include 'includes/db.php'; // Ensure this path is correct

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = trim($_POST['correo']);
    $contrasena_ingresada = $_POST['contrasena'];

    if (empty($correo) || empty($contrasena_ingresada)) {
        header("Location: index.php?login_error=empty");
        exit();
    }

    // Prepare statement to prevent SQL injection
    // Users can login with their email (which is also stored in the 'usuario' field currently)
    $sql = "SELECT id, usuario, email, password FROM usuarios WHERE email = ? OR usuario = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ss", $correo, $correo);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows == 1) {
            $usuario_db = $resultado->fetch_assoc();
            // Verify password
            if (password_verify($contrasena_ingresada, $usuario_db['password'])) {
                // Password is correct, start session
                $_SESSION['user_id'] = $usuario_db['id'];
                $_SESSION['usuario_nombre'] = $usuario_db['usuario']; // Or email, or a specific name field if you add one
                $_SESSION['usuario_email'] = $usuario_db['email'];

                // Regenerate session ID for security
                session_regenerate_id(true);

                header("Location: index.php?login=success");
                exit();
            } else {
                // Incorrect password
                header("Location: index.php?login_error=incorrect");
                exit();
            }
        } else {
            // User not found
            header("Location: index.php?login_error=notfound");
            exit();
        }
        $stmt->close();
    } else {
        // SQL error
        error_log("SQL prepare error in login.php: " . $conn->error);
        header("Location: index.php?login_error=dberror");
        exit();
    }
    $conn->close();
} else {
    header("Location: index.php");
    exit();
}
?>