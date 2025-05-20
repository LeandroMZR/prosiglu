document.addEventListener("DOMContentLoaded", function () {
  const inputBusqueda = document.getElementById("input-busqueda");
  const productos = document.querySelectorAll(".producto");
  const catalogo = document.getElementById("catalogo");

  // --- FILTRO DE PRODUCTOS ---
  inputBusqueda.addEventListener("input", function () {
    const filtro = inputBusqueda.value.toLowerCase();

    productos.forEach((producto) => {
      const nombre = producto.querySelector("h3").textContent.toLowerCase();
      const categoria = producto.dataset.categoria.toLowerCase();

      if (nombre.includes(filtro) || categoria.includes(filtro)) {
        producto.style.display = "block";
      } else {
        producto.style.display = "none";
      }
    });

    const listas = catalogo.querySelectorAll(".lista-productos");
    listas.forEach((lista) => {
      const visibles = lista.querySelectorAll(".producto:not([style*='display: none'])");
      lista.style.display = visibles.length > 0 ? "flex" : "none";
    });

    const encabezadosH3 = catalogo.querySelectorAll("h3");
    encabezadosH3.forEach((h3) => {
      const siguiente = h3.nextElementSibling;
      if (siguiente && siguiente.classList.contains("lista-productos")) {
        const visibles = siguiente.querySelectorAll(".producto:not([style*='display: none'])");
        h3.style.display = visibles.length > 0 ? "block" : "none";
      }
    });

    const encabezadosH2 = catalogo.querySelectorAll("h2");
    encabezadosH2.forEach((h2) => {
      let seccion = h2.nextElementSibling;
      let tieneProductos = false;

      while (seccion && seccion.tagName !== "H2") {
        if (seccion.classList && seccion.classList.contains("lista-productos")) {
          const visibles = seccion.querySelectorAll(".producto:not([style*='display: none'])");
          if (visibles.length > 0) {
            tieneProductos = true;
            break;
          }
        }
        seccion = seccion.nextElementSibling;
      }

      h2.style.display = tieneProductos ? "block" : "none";
    });
  });

  // --- CARRITO DE COMPRAS ---
  const carrito = [];
  const listaCarrito = document.getElementById("carrito");
  const totalTexto = document.getElementById("total");

  document.querySelectorAll(".producto button").forEach((boton) => {
    boton.addEventListener("click", () => {
      const producto = boton.closest(".producto");
      const nombre = producto.querySelector("h3").textContent;
      const precioTexto = producto.querySelector(".precio").textContent.replace("€", "");
      const precio = parseFloat(precioTexto);

      carrito.push({ nombre, precio });
      actualizarCarrito();
    });
  });

  function actualizarCarrito() {
    listaCarrito.innerHTML = "";
    let total = 0;

    carrito.forEach((item, index) => {
      const li = document.createElement("li");
      li.innerHTML = `
        ${item.nombre} - €${item.precio.toFixed(2)}
        <button onclick="eliminarDelCarrito(${index})">Eliminar</button>
      `;
      listaCarrito.appendChild(li);
      total += item.precio;
    });

    totalTexto.textContent = `Total: €${total.toFixed(2)}`;
  }

  window.eliminarDelCarrito = function(index) {
    carrito.splice(index, 1);
    actualizarCarrito();
  };
});
