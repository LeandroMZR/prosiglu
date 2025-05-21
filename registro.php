<?php
// Incluye tu archivo de conexión a la base de datos
include 'includes/db.php'; // Asegúrate de que este archivo establece $conn

// Verifica si el formulario fue enviado usando el método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Obtener y Sanitizar Datos de Entrada
    $correo_ingresado = trim($_POST['correo']);
    $contrasena_plana = $_POST['contrasena'];
    $telefono = trim($_POST['telefono']);

    // Usando el correo como nombre de usuario, según tu formulario/esquema actual
    // Si quieres un campo de 'usuario' separado, debes añadirlo al HTML y obtenerlo aquí.
    $usuario = $correo_ingresado;

    // 2. Validar Entrada (Validación Básica)
    if (empty($correo_ingresado) || empty($contrasena_plana)) {
        die("Error: Correo y contraseña son campos obligatorios.");
    }

    if (!filter_var($correo_ingresado, FILTER_VALIDATE_EMAIL)) {
        die("Error: Formato de correo electrónico inválido.");
    }

    if (strlen($contrasena_plana) < 8) {
        die("Error: La contraseña debe tener al menos 8 caracteres.");
    }

    // 3. Hash de la Contraseña
    $contrasena_hashed = password_hash($contrasena_plana, PASSWORD_BCRYPT);

    // Iniciar una transacción para asegurar la atomicidad de las operaciones
    $conn->begin_transaction();

    try {
        // 4. Preparar y Ejecutar la Consulta SQL INSERT para el Usuario
        // NOTA: Asegúrate de que los nombres de las columnas coincidan con tu tabla `usuarios`
        $sql_user = "INSERT INTO usuarios (usuario, email, telefono, password) VALUES (?, ?, ?, ?)";

        if ($stmt_user = $conn->prepare($sql_user)) {
            $stmt_user->bind_param("ssss", $usuario, $correo_ingresado, $telefono, $contrasena_hashed);

            if (!$stmt_user->execute()) {
                // Si la inserción del usuario falla, lanza una excepción
                if ($conn->errno == 1062) { // Código de error MySQL para entrada duplicada (clave UNIQUE)
                    throw new Exception("Error: Este correo electrónico o nombre de usuario ya está registrado.");
                } else {
                    throw new Exception("Error al registrar el usuario: " . $stmt_user->error);
                }
            }

            // Obtener el ID del usuario recién insertado
            $new_user_id = $conn->insert_id;
            $stmt_user->close();

            // 5. Preparar y Ejecutar la Consulta SQL INSERT para el Carrito
            // Usa NOW() para la marca de tiempo actual en `fecha_creacion`
            $sql_cart = "INSERT INTO carritos (usuario_id, fecha_creacion) VALUES (?, NOW())";

            if ($stmt_cart = $conn->prepare($sql_cart)) {
                $stmt_cart->bind_param("i", $new_user_id); // 'i' para entero (usuario_id)

                if (!$stmt_cart->execute()) {
                    throw new Exception("Error al crear el carrito del usuario: " . $stmt_cart->error);
                }
                $stmt_cart->close();
            } else {
                throw new Exception("Error al preparar la consulta del carrito: " . $conn->error);
            }

            // Si ambas operaciones son exitosas, confirma la transacción
            $conn->commit();

            echo "¡Registro de cliente y creación de carrito exitosos! Redireccionando...";
            header("Location: index.php?registro=exito");
            exit();

        } else {
            throw new Exception("Error al preparar la consulta de usuario: " . $conn->error);
        }

    } catch (Exception $e) {
        // Si ocurre algún error, se revierte la transacción
        $conn->rollback();
        echo $e->getMessage(); // Muestra el mensaje de error
    } finally {
        // Asegura que la conexión siempre se cierre
        if (isset($conn) && $conn instanceof mysqli) {
            $conn->close();
        }
    }

} else {
    // Si el formulario no fue enviado mediante POST, redirige al usuario a la página principal
    header("Location: index.php");
    exit();
}