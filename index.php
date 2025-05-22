<?php
session_start(); // Start the session at the very beginning

$nombre_usuario_display = '';
if (isset($_SESSION['user_id']) && isset($_SESSION['usuario_nombre'])) {
    $nombre_usuario_display = htmlspecialchars($_SESSION['usuario_nombre']);
}

// Handle messages from login/registration attempts
$login_message = '';
$registro_message = '';

if (isset($_GET['login_error'])) {
    $error_type = $_GET['login_error'];
    if ($error_type === 'empty') $login_message = '<p class="error-message">Correo y contraseña son obligatorios.</p>';
    elseif ($error_type === 'incorrect') $login_message = '<p class="error-message">Correo o contraseña incorrectos.</p>';
    elseif ($error_type === 'notfound') $login_message = '<p class="error-message">Usuario no encontrado.</p>';
    elseif ($error_type === 'dberror') $login_message = '<p class="error-message">Error de base de datos. Intente más tarde.</p>';
}
if (isset($_GET['login']) && $_GET['login'] === 'success') {
    // Optional: could display a "Login successful" message briefly, but usually just showing logged in state is enough.
}

if (isset($_GET['registro']) && $_GET['registro'] === 'exito') {
    $registro_message = '<p class="success-message">¡Registro exitoso! Por favor, inicia sesión.</p>';
}
if (isset($_GET['registro_error'])) {
    $error_msg = htmlspecialchars(urldecode($_GET['registro_error']));
    if (strpos($error_msg, "Este correo electrónico o nombre de usuario ya está registrado") !== false) {
         $registro_message = '<p class="error-message">' . $error_msg . '</p>';
    } else if ($_GET['registro_error'] === 'empty_required_fields') {
        $registro_message = '<p class="error-message">Correo y contraseña son campos obligatorios para el registro.</p>';
    } else if ($_GET['registro_error'] === 'invalid_email') {
        $registro_message = '<p class="error-message">Formato de correo electrónico inválido.</p>';
    } else if ($_GET['registro_error'] === 'password_short') {
        $registro_message = '<p class="error-message">La contraseña debe tener al menos 8 caracteres.</p>';
    }
    else {
        $registro_message = '<p class="error-message">Error en el registro: ' . $error_msg . '</p>';
    }
}
if (isset($_GET['logout']) && $_GET['logout'] === 'success') {
    // Optional: could display a "Logout successful" message.
    $login_message = '<p class="success-message">Has cerrado sesión exitosamente.</p>';
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Prosiglu - Tienda sin gluten</title>
  <link rel="stylesheet" href="estilos.css" />
  <style>
    /* Basic styling for messages and forms, add to your estilos.css */
    .user-auth-section { margin-bottom: 20px; padding: 15px; border: 1px solid #ddd; border-radius: 5px; background-color: #f9f9f9;}
    .user-auth-section h2 { margin-top: 0; }
    .user-auth-section label { display: block; margin-bottom: 5px; }
    .user-auth-section input[type="email"],
    .user-auth-section input[type="password"],
    .user-auth-section input[type="text"],
    .user-auth-section input[type="tel"] { width: calc(100% - 22px); padding: 10px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 3px; }
    .user-auth-section button { padding: 10px 15px; background-color: #5cb85c; color: white; border: none; border-radius: 3px; cursor: pointer; }
    .user-auth-section button:hover { background-color: #4cae4c; }
    .session-status { text-align: right; padding: 10px; background-color: #f0f0f0; margin-bottom:15px;}
    .session-status p { margin: 0; }
    .session-status a { text-decoration: none; color: #337ab7; font-weight: bold; }
    .error-message { color: red; font-weight: bold; }
    .success-message { color: green; font-weight: bold; }
  </style>
</head>

<body>
  <header>
    <img src="imagenes/logo.jpeg" alt="Logo Prosiglu" id="logo" />
    <h1>Prosiglu S.L.</h1>
    <p>Productos Sin Gluten - Gluten Free</p>
  </header>

  <div class="session-status">
    <?php if (isset($_SESSION['user_id'])): ?>
      <p>Bienvenido/a, <?php echo $nombre_usuario_display; ?>! <a href="logout.php">Cerrar Sesión</a></p>
    <?php else: ?>
      <p>¿Ya tienes cuenta? <a href="#login-container">Iniciar Sesión</a> | ¿Eres nuevo? <a href="#registro-container">Regístrate</a></p>
    <?php endif; ?>
  </div>

  <main>
    <?php if (!isset($_SESSION['user_id'])): ?>
      <section id="login-container" class="user-auth-section">
        <h2>Iniciar Sesión</h2>
        <?php echo $login_message; // Display login messages here ?>
        <?php if (isset($_GET['registro']) && $_GET['registro'] === 'exito') echo $registro_message; // Display registration success message above login ?>
        <form id="login-form" action="login.php" method="POST">
          <label for="login-correo">Correo Electrónico:</label>
          <input type="email" id="login-correo" name="correo" placeholder="ejemplo@gmail.com" required />

          <label for="login-contrasena">Contraseña:</label>
          <input type="password" id="login-contrasena" name="contrasena" required />

          <button type="submit">Iniciar Sesión</button>
        </form>
      </section>

      <section id="registro-container" class="user-auth-section">
        <h2>Registro de Cliente</h2>
        <?php if (!isset($_GET['registro']) || $_GET['registro'] !== 'exito') echo $registro_message; // Display registration errors here ?>
        <form id="registro-form" action="registro.php" method="POST">
          <label for="correo">Correo Electrónico (será tu usuario):</label>
          <input type="email" id="correo" name="correo" placeholder="ejemplo@gmail.com" required />

          <label for="contrasena-reg">Contraseña (mínimo 8 caracteres):</label>
          <input type="password" id="contrasena-reg" name="contrasena" required />

          <label for="telefono">Número de teléfono:</label>
          <input type="tel" id="telefono" name="telefono" placeholder="600123456" pattern="[0-9]{9}" />
          
          <label for="direccion">Dirección:</label>
          <input type="text" id="direccion" name="direccion" placeholder="Calle Falsa 123, Piso 4B" />

          <label for="ciudad">Ciudad:</label>
          <input type="text" id="ciudad" name="ciudad" placeholder="Ciudad Ejemplo" />

          <label for="cp">Código Postal:</label>
          <input type="text" id="cp" name="cp" placeholder="28001" pattern="[0-9]{5}" />

          <div class="registro-botones">
            <button type="submit">Registrar Cliente</button>
          </div>
        </form>
      </section>
    <?php endif; ?>

    <section id="buscador">
      <input type="text" id="input-busqueda" placeholder="Buscar productos por nombre o categoría..." />
    </section>

    <section id="catalogo">
      <div class="productos-por-categoria">
        <?php
        // Ensure db.php is included if not already by session logic or other means
        // For product display, connection needs to be open if not already handled
        if (!isset($conn) || !$conn instanceof mysqli || $conn->connect_error) {
            include 'includes/db.php'; // Re-include if connection was closed or not established
        }


        $sql_categorias = "SELECT id, nombre FROM categorias ORDER BY nombre ASC";
        $resultado_categorias = $conn->query($sql_categorias);

        if ($resultado_categorias && $resultado_categorias->num_rows > 0) {
          while ($categoria = $resultado_categorias->fetch_assoc()) {
            echo '<div class="categoria-group">';
            echo '<h2>' . htmlspecialchars($categoria["nombre"]) . '</h2>';

            $sql_subcategorias = "SELECT id, nombre FROM subcategorias WHERE categoria_id = " . $categoria["id"] . " ORDER BY nombre ASC";
            $resultado_subcategorias = $conn->query($sql_subcategorias);

            if ($resultado_subcategorias && $resultado_subcategorias->num_rows > 0) {
              while ($subcategoria = $resultado_subcategorias->fetch_assoc()) {
                echo '<div class="subcategoria-group">';
                echo '<h3>' . htmlspecialchars($subcategoria["nombre"]) . '</h3>';
                echo '<div class="productos-grid">';

                $sql_productos = "SELECT gtin, nombre, precio, imagen FROM productos WHERE subcategoria_id = " . $subcategoria["id"] . " ORDER BY nombre ASC";
                $resultado_productos = $conn->query($sql_productos);

                if ($resultado_productos && $resultado_productos->num_rows > 0) {
                  while ($producto = $resultado_productos->fetch_assoc()) {
                    echo '<div class="producto" data-categoria="' . htmlspecialchars($categoria["nombre"]) . '" data-subcategoria="' . htmlspecialchars($subcategoria["nombre"]) . '" data-nombre="' . htmlspecialchars($producto["nombre"]) . '">';
                    echo '<img src="imagenes/' . htmlspecialchars($producto["imagen"]) . '" alt="' . htmlspecialchars($producto["nombre"]) . '" />';
                    echo '<h4>' . htmlspecialchars($producto["nombre"]) . '</h4>';
                    echo '<p class="precio">€' . number_format($producto["precio"], 2) . '</p>';
                    echo '<button>Agregar al carrito</button>'; // This button's functionality might need to consider user session for cart persistence
                    echo '</div>';
                  }
                } else {
                  echo '<p>No hay productos en esta subcategoría.</p>';
                }
                echo '</div>'; // .productos-grid
                echo '</div>'; // .subcategoria-group
              }
            } else {
              echo '<p>No hay subcategorías en esta categoría.</p>';
            }
            echo '</div>'; // .categoria-group
          }
        } else {
          echo '<p>No se encontraron categorías.</p>';
        }

        // Close connection if it was opened here for product display
        // Be careful if $conn is used elsewhere after this point on the page.
        // The login/registration scripts close their own connections.
        if (isset($conn) && $conn instanceof mysqli && !isset($_SESSION['user_id'])) { // Only close if not part of a user session script that might need it later
             // $conn->close(); // Commented out: login.php and registro.php close their own. This one is for catalog.
                               // The original code closed it at the end of the PHP block.
        }
        ?>
      </div>
    </section>

    <section id="carrito-section">
      <h2>Carrito de compras</h2>
      <ul id="carrito"></ul>
      <p id="total" class="total-carrito">Total: €0.00</p>
      <?php if (isset($_SESSION['user_id'])): ?>
        <button id="procesar-pedido-btn">Proceder al Pago</button> 
        <!-- Add JavaScript to handle this button, e.g., redirect to a checkout page -->
      <?php else: ?>
        <p>Debes <a href="#login-container">iniciar sesión</a> o <a href="#registro-container">registrarte</a> para guardar tu carrito y proceder al pago.</p>
      <?php endif; ?>
    </section>
    
  </main>

  <footer>
    <p>&copy; <?php echo date("Y"); ?> Catálogo Sin Gluten. Todos los derechos reservados.</p>
  </footer>

  <script src="script.js"></script>
  <script>
    // Smooth scroll for anchor links if desired
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            if(targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });

    // Clear messages on input focus to make UX cleaner
    const loginForm = document.getElementById('login-form');
    const registroForm = document.getElementById('registro-form');

    function clearMessages(containerId) {
        const container = document.getElementById(containerId);
        if (container) {
            const errorMessages = container.querySelectorAll('.error-message');
            const successMessages = container.querySelectorAll('.success-message');
            errorMessages.forEach(el => el.style.display = 'none');
            successMessages.forEach(el => el.style.display = 'none');
        }
    }

    if (loginForm) {
        loginForm.addEventListener('focusin', () => clearMessages('login-container'));
    }
    if (registroForm) {
        registroForm.addEventListener('focusin', () => clearMessages('registro-container'));
    }

  </script>
</body>
</html>