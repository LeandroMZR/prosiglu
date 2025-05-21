<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Prosiglu - Tienda sin gluten</title>
  <link rel="stylesheet" href="estilos.css" />
</head>

<body>
  <header>
    <img src="imagenes/logo.jpeg" alt="Logo Prosiglu" id="logo" />
    <h1>Prosiglu S.L.</h1>
    <p>Productos Sin Gluten - Gluten Free</p>
  </header>

  <main>
    <section id="buscador">
      <input type="text" id="input-busqueda" placeholder="Buscar productos por nombre o categoría..." />
    </section>

    <section id="catalogo">
    <div class="productos-por-categoria">
        <?php
        // Asegúrate de que db.php conecte correctamente a $conn
        include 'includes/db.php';

        // 1. Obtener todas las Categorías
        $sql_categorias = "SELECT id, nombre FROM categorias ORDER BY nombre ASC";
        $resultado_categorias = $conn->query($sql_categorias);

        if ($resultado_categorias->num_rows > 0) {
            while ($categoria = $resultado_categorias->fetch_assoc()) {
                echo '<div class="categoria-group">';
                echo '<h2>' . htmlspecialchars($categoria["nombre"]) . '</h2>'; // Título de la Categoría

                // 2. Obtener Subcategorías para la Categoría actual
                $sql_subcategorias = "SELECT id, nombre FROM subcategorias WHERE categoria_id = " . $categoria["id"] . " ORDER BY nombre ASC";
                $resultado_subcategorias = $conn->query($sql_subcategorias);

                if ($resultado_subcategorias->num_rows > 0) {
                    while ($subcategoria = $resultado_subcategorias->fetch_assoc()) {
                        echo '<div class="subcategoria-group">';
                        echo '<h3>' . htmlspecialchars($subcategoria["nombre"]) . '</h3>'; // Título de la Subcategoría
                        echo '<div class="productos-grid">'; // Contenedor para productos en esta subcategoría

                        // 3. Obtener Productos para la Subcategoría actual
                        $sql_productos = "SELECT gtin, nombre, precio, imagen FROM productos WHERE subcategoria_id = " . $subcategoria["id"] . " ORDER BY nombre ASC";
                        $resultado_productos = $conn->query($sql_productos);

                        if ($resultado_productos->num_rows > 0) {
                            while ($producto = $resultado_productos->fetch_assoc()) {
                                // IMPORTANTE: Se añadieron atributos data-categoria, data-subcategoria, data-nombre para la funcionalidad de búsqueda
                                echo '<div class="producto" data-categoria="' . htmlspecialchars($categoria["nombre"]) . '" data-subcategoria="' . htmlspecialchars($subcategoria["nombre"]) . '" data-nombre="' . htmlspecialchars($producto["nombre"]) . '">';
                                echo '<img src="imagenes/' . htmlspecialchars($producto["imagen"]) . '" alt="' . htmlspecialchars($producto["nombre"]) . '" />';
                                echo '<h4>' . htmlspecialchars($producto["nombre"]) . '</h4>';
                                echo '<p class="precio">€' . number_format($producto["precio"], 2) . '</p>';
                                echo '<button>Agregar al carrito</button>';
                                echo '</div>';
                            }
                        } else {
                            echo '<p>No hay productos en esta subcategoría.</p>';
                        }
                        echo '</div>'; // Cierre de productos-grid
                        echo '</div>'; // Cierre de subcategoria-group
                    }
                } else {
                    echo '<p>No hay subcategorías en esta categoría.</p>';
                }
                echo '</div>'; // Cierre de categoria-group
            }
        } else {
            echo '<p>No se encontraron categorías.</p>';
        }

        // Es una buena práctica cerrar la conexión de la base de datos al finalizar todas las consultas.
        if (isset($conn) && $conn instanceof mysqli) {
            $conn->close();
        }
        ?>
    </div>
  </section>

    <section id="carrito-section">
      <h2>Carrito de compras</h2>
      <ul id="carrito"></ul>
      <p id="total" class="total-carrito">Total: €0.00</p>
    </section>

  <section id="registro-container">
    <h2>Registro de Cliente</h2>
    <form id="registro-form" action="registro.php" method="POST">
      <label for="correo">Correo Gmail:</label>
      <input type="email" id="correo" name="correo" placeholder="ejemplo@gmail.com" pattern=".+@gmail\.com" required />

      <label for="contrasena-reg">Contraseña:</label>
      <input type="password" id="contrasena-reg" name="contrasena" required />

      <label for="telefono">Número de teléfono:</label>
      <input type="tel" id="telefono" name="telefono" placeholder="600123456" pattern="[0-9]{9}" />

      <div class="registro-botones">
        <button type="submit">Registrar Cliente</button>
      </div>
    </form>
  </section>
  </main>

  <footer>
    <p>&copy; 2025 Catálogo Sin Gluten. Todos los derechos reservados.</p>
  </footer>

  <script src="script.js"></script>
</body>
</html>