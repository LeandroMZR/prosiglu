document.addEventListener("DOMContentLoaded", function () {
    const inputBusqueda = document.getElementById("input-busqueda");
    const productos = document.querySelectorAll(".producto");
    const catalogo = document.getElementById("catalogo");
  
    inputBusqueda.addEventListener("input", function () {
      const filtro = inputBusqueda.value.toLowerCase();
  
      // Filtrar productos
      productos.forEach((producto) => {
        const nombre = producto.querySelector("h3").textContent.toLowerCase();
        const categoria = producto.dataset.categoria.toLowerCase();
  
        if (nombre.includes(filtro) || categoria.includes(filtro)) {
          producto.style.display = "block";
        } else {
          producto.style.display = "none";
        }
      });
  
      // Mostrar u ocultar div.lista-productos según los productos visibles
      const listas = catalogo.querySelectorAll(".lista-productos");
      listas.forEach((lista) => {
        const visibles = lista.querySelectorAll(".producto:not([style*='display: none'])");
        lista.style.display = visibles.length > 0 ? "flex" : "none";
      });
  
      // Mostrar u ocultar h3 en base a su lista-productos
      const encabezadosH3 = catalogo.querySelectorAll("h3");
      encabezadosH3.forEach((h3) => {
        const siguiente = h3.nextElementSibling;
        if (siguiente && siguiente.classList.contains("lista-productos")) {
          const visibles = siguiente.querySelectorAll(".producto:not([style*='display: none'])");
          h3.style.display = visibles.length > 0 ? "block" : "none";
        }
      });
  
      // Mostrar u ocultar h2 si ninguna de sus subsecciones contiene productos visibles
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
  });
  
