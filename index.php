<?php
session_start();

$nombre_usuario_display = '';
$is_logged_in = false;
if (isset($_SESSION['user_id']) && isset($_SESSION['usuario_nombre'])) {
    $nombre_usuario_display = htmlspecialchars($_SESSION['usuario_nombre']);
    $is_logged_in = true;
}

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
}

if (isset($_GET['registro']) && $_GET['registro'] === 'exito') {
    $registro_message = '<p class="success-message">¡Registro exitoso! Por favor, inicia sesión.</p>';
}
if (isset($_GET['registro_error'])) {
    $error_msg_code = $_GET['registro_error'];
    $error_msg = htmlspecialchars(urldecode($_GET['registro_error']));

    if ($error_msg_code === 'duplicate') {
         $registro_message = '<p class="error-message">Este correo electrónico o nombre de usuario ya está registrado.</p>';
    } else if ($error_msg_code === 'empty_required_fields') {
        $registro_message = '<p class="error-message">Correo y contraseña son campos obligatorios para el registro.</p>';
    } else if ($error_msg_code === 'invalid_email') {
        $registro_message = '<p class="error-message">Formato de correo electrónico inválido.</p>';
    } else if ($error_msg_code === 'password_short') {
        $registro_message = '<p class="error-message">La contraseña debe tener al menos 8 caracteres.</p>';
    }
    else {
        $registro_message = '<p class="error-message">Error en el registro: ' . $error_msg . '</p>';
    }
}

if (isset($_GET['logout']) && $_GET['logout'] === 'success') {
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

    #carrito li {
        border-bottom: 1px solid #eee;
        padding: 10px 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    #carrito li span {
        flex-grow: 1;
        margin-right: 10px;
    }
     #carrito li .item-details {
         flex-grow: 1;
         display: flex;
         align-items: center;
     }
     #carrito li .item-details img {
         width: 40px;
         height: 40px;
         object-fit: cover;
         margin-right: 10px;
         border-radius: 3px;
     }
     #carrito li .item-details span {
         flex-grow: 1;
     }
     #carrito li .item-quantity,
     #carrito li .item-price {
         flex-shrink: 0;
         width: 60px;
         text-align: right;
         margin-left: 10px;
     }


    .total-carrito {
        font-size: 1.2em;
        font-weight: bold;
        text-align: right;
        margin-top: 15px;
    }
  </style>
</head>

<body>
  <header>
    <img src="imagenes/logo.jpeg" alt="Logo Prosiglu" id="logo" />
    <h1>Prosiglu S.L.</h1>
    <p>Productos Sin Gluten - Gluten Free</p>
  </header>

  <div class="session-status">
    <?php if ($is_logged_in): ?>
      <p>Bienvenido/a, <?php echo $nombre_usuario_display; ?>! <a href="logout.php">Cerrar Sesión</a></p>
      <input type="hidden" id="is-logged-in" value="1">
      <input type="hidden" id="logged-in-username" value="<?php echo $nombre_usuario_display; ?>">
    <?php else: ?>
      <p>¿Ya tienes cuenta? <a href="#login-container">Iniciar Sesión</a> | ¿Eres nuevo? <a href="#registro-container">Regístrate</a></p>
      <input type="hidden" id="is-logged-in" value="0">
      <input type="hidden" id="logged-in-username" value="">
    <?php endif; ?>
  </div>

  <main>
    <?php if (!$is_logged_in): ?>
      <section id="login-container" class="user-auth-section">
        <h2>Iniciar Sesión</h2>
        <?php echo $login_message; ?>
        <?php if (isset($_GET['registro']) && $_GET['registro'] === 'exito') echo $registro_message; ?>
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
        <?php if (!isset($_GET['registro']) || $_GET['registro'] !== 'exito') echo $registro_message; ?>
        <form id="registro-form" action="registro.php" method="POST">
          <label for="correo">Correo Electrónico (será tu usuario):</label>
          <input type="email" id="correo" name="correo" placeholder="ejemplo@gmail.com" required />

          <label for="contrasena-reg">Contraseña (mínimo 8 caracteres):</label>
          <input type="password" id="contrasena-reg" name="contrasena" required />

          <label for="usuario_name">Nombre de usuario (Opcional, si la DB lo requiere):</label>
          <input type="text" id="usuario_name" name="usuario" placeholder="ejemplo_usuario" />


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
        if (!isset($conn) || !$conn instanceof mysqli || $conn->connect_error) {
            require 'includes/db.php';
        }

        if ($conn->connect_error) {
            die("Error de conexión a la base de datos: " . $conn->connect_error);
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
                    echo '<button class="add-to-cart-btn" data-gtin="' . htmlspecialchars($producto["gtin"]) . '">Agregar al carrito</button>';
                    echo '</div>';
                  }
                } else {
                  echo '<p>No hay productos en esta subcategoría.</p>';
                }
                echo '</div>';
                echo '</div>';
              }
            } else {
              echo '<p>No hay subcategorías en esta categoría.</p>';
            }
            echo '</div>';
          }
        } else {
          echo '<p>No se encontraron categorías.</p>';
        }

        if (isset($conn) && $conn instanceof mysqli && !$conn->connect_error) {
        }
        ?>
      </div>
    </section>

    <section id="carrito-section">
      <h2>Carrito de compras</h2>
      <div id="cart-message-area"></div>
      <ul id="carrito">
          <p id="loading-cart-message">Cargando carrito...</p>
      </ul>
      <p id="total" class="total-carrito">Total: €0.00</p>
      <?php if ($is_logged_in): ?>
        <button id="procesar-pedido-btn">Proceder al Pago</button>
      <?php else: ?>
        <p>Debes <a href="#login-container">iniciar sesión</a> o <a href="#registro-container">registrarte</a> para guardar tu carrito y proceder al pago.</p>
         <button id="procesar-pedido-btn" disabled>Proceder al Pago</button>
      <?php endif; ?>
    </section>

  </main>

  <footer>
    <p>© <?php echo date("Y"); ?> Catálogo Sin Gluten. Todos los derechos reservados.</p>
  </footer>

  <script src="script.js"></script>
  <script>
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

    const loginForm = document.getElementById('login-form');
    const registroForm = document.getElementById('registro-form');
    const cartMessageArea = document.getElementById('cart-message-area');

    function clearMessages(container) {
        if (container) {
            const messages = container.querySelectorAll('.error-message, .success-message');
            messages.forEach(el => el.style.display = 'none');
        }
    }

    if (loginForm) {
        loginForm.addEventListener('focusin', () => clearMessages(document.getElementById('login-container')));
    }
    if (registroForm) {
        registroForm.addEventListener('focusin', () => clearMessages(document.getElementById('registro-container')));
    }
    if (cartMessageArea) {
    }
  </script>
</body>
</html>