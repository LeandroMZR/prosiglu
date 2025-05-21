document.addEventListener("DOMContentLoaded", function () {
  const inputBusqueda = document.getElementById("input-busqueda");

  // --- FILTRO DE PRODUCTOS ---
  inputBusqueda.addEventListener("input", function () {
    const filtro = inputBusqueda.value.toLowerCase();
    const allProducts = document.querySelectorAll(".producto"); // Obtener todos los productos en cada evento

    // 1. Filtrar productos individuales y ajustar su visibilidad
    allProducts.forEach((producto) => {
      // Usar data-attributes para un filtrado robusto
      const nombre = producto.dataset.nombre ? producto.dataset.nombre.toLowerCase() : "";
      const categoria = producto.dataset.categoria ? producto.dataset.categoria.toLowerCase() : "";
      const subcategoria = producto.dataset.subcategoria ? producto.dataset.subcategoria.toLowerCase() : "";

      if (nombre.includes(filtro) || categoria.includes(filtro) || subcategoria.includes(filtro)) {
        producto.style.display = "flex"; // Usar flex para mantener el diseño de la cuadrícula
      } else {
        producto.style.display = "none";
      }
    });

    // 2. Ajustar visibilidad de los Grupos de Subcategorías (h3 y sus productos-grid)
    document.querySelectorAll(".subcategoria-group").forEach((subcategoriaGroup) => {
      const productosInSubcategory = subcategoriaGroup.querySelectorAll(".producto");
      let subcategoryHasVisibleProducts = false;

      productosInSubcategory.forEach((producto) => {
        if (producto.style.display !== "none") {
          subcategoryHasVisibleProducts = true;
        }
      });

      if (subcategoryHasVisibleProducts) {
        subcategoriaGroup.style.display = "block"; // Mostrar el grupo de subcategoría
      } else {
        subcategoriaGroup.style.display = "none"; // Ocultar el grupo de subcategoría
      }
    });


    // 3. Ajustar visibilidad de los Grupos de Categorías (h2 y sus grupos de subcategorías)
    document.querySelectorAll(".categoria-group").forEach((categoriaGroup) => {
      const subcategoriaGroupsInParent = categoriaGroup.querySelectorAll(".subcategoria-group");
      let categoryHasVisibleSubcategories = false;

      subcategoriaGroupsInParent.forEach((subcategoriaGroup) => {
        if (subcategoriaGroup.style.display !== "none") {
          categoryHasVisibleSubcategories = true;
        }
      });

      if (categoryHasVisibleSubcategories) {
        categoriaGroup.style.display = "block"; // Mostrar el grupo de categoría
      } else {
        categoriaGroup.style.display = "none"; // Ocultar el grupo de categoría
      }
    });
  });

  // --- CARRITO DE COMPRAS ---
  const carrito = []; // Array para almacenar los productos en el carrito
  const listaCarrito = document.getElementById("carrito"); // UL donde se listan los productos
  const totalTexto = document.getElementById("total"); // P donde se muestra el total

  // Añadir listener a todos los botones "Agregar al carrito"
  document.querySelectorAll(".producto button").forEach((boton) => {
    boton.addEventListener("click", () => {
      const producto = boton.closest(".producto"); // Encuentra el div .producto padre
      const nombre = producto.dataset.nombre; // Obtiene el nombre del data-attribute
      const precioTexto = producto.querySelector(".precio").textContent.replace("€", "");
      const precio = parseFloat(precioTexto);

      // Añadir el producto al array del carrito
      carrito.push({ nombre, precio });
      actualizarCarrito(); // Actualizar la vista del carrito
    });
  });

  // Función para actualizar la interfaz del carrito
  function actualizarCarrito() {
    listaCarrito.innerHTML = ""; // Limpiar la lista actual del carrito
    let total = 0; // Reiniciar el total

    // Recorrer el array del carrito para mostrar los productos
    carrito.forEach((item, index) => {
      const li = document.createElement("li");
      li.innerHTML = `
        ${item.nombre} - €${item.precio.toFixed(2)}
        <button onclick="eliminarDelCarrito(${index})">Eliminar</button>
      `;
      listaCarrito.appendChild(li); // Añadir el item a la lista
      total += item.precio; // Sumar al total
    });

    // Actualizar el texto del total
    totalTexto.textContent = `Total: €${total.toFixed(2)}`;
  }

  // Hacer la función global para que onclick pueda acceder a ella
  window.eliminarDelCarrito = function(index) {
    carrito.splice(index, 1); // Eliminar el producto del array
    actualizarCarrito(); // Actualizar la vista del carrito
  };
});