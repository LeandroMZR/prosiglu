document.addEventListener('DOMContentLoaded', () => {

  const cartList = document.getElementById('carrito');
  const totalElement = document.getElementById('total');
  const cartMessageArea = document.getElementById('cart-message-area');
  const isLoggedIn = document.getElementById('is-logged-in').value === '1';
  const loadingCartMessage = document.getElementById('loading-cart-message');
  const loggedInUsername = document.getElementById('logged-in-username').value;
  const procesarPedidoBtn = document.getElementById('procesar-pedido-btn');


  function displayCart(items) {
      cartList.innerHTML = '';
      let currentTotal = 0;

      if (loadingCartMessage) {
           loadingCartMessage.style.display = 'none';
      }

      if (items.length === 0) {
          cartList.innerHTML = '<p>El carrito está vacío.</p>';
          totalElement.textContent = 'Total: €0.00';
           if (procesarPedidoBtn) {
              procesarPedidoBtn.disabled = true;
          }
          return;
      }

      items.forEach(item => {
          const listItem = document.createElement('li');
          listItem.innerHTML = `
              <div class="item-details">
                  <img src="imagenes/${item.imagen}" alt="${item.nombre}" width="40" height="40">
                  <span>${item.nombre}</span>
              </div>
              <span class="item-quantity">x${item.cantidad}</span>
              <span class="item-price">€${parseFloat(item.precio).toFixed(2)}</span>
              <button class="remove-from-cart-btn" data-gtin="${item.gtin}">Eliminar</button>
          `;
          cartList.appendChild(listItem);
          currentTotal += parseFloat(item.precio) * parseInt(item.cantidad);
      });

      totalElement.textContent = `Total: €${currentTotal.toFixed(2)}`;

      if (procesarPedidoBtn) {
           procesarPedidoBtn.disabled = false;
      }

      document.querySelectorAll('.remove-from-cart-btn').forEach(button => {
          button.addEventListener('click', handleRemoveFromCart);
      });
  }

  function showCartMessage(message, type = 'success', timeout = 5000) {
      if (!cartMessageArea) return;
      if (type !== 'checkout-success') {
           cartMessageArea.innerHTML = '';
      } else {
          cartMessageArea.innerHTML = '';
      }

      const messageElement = document.createElement('p');
      messageElement.classList.add(type + '-message');
      messageElement.innerHTML = message;
      cartMessageArea.appendChild(messageElement);

      if (timeout !== false && timeout > 0) {
          setTimeout(() => {
               if (cartMessageArea.contains(messageElement)) {
                   cartMessageArea.removeChild(messageElement);
               }
          }, timeout);
      }
  }


  async function loadCart() {
      if (!isLoggedIn) {
          displayCart([]);
           if (loadingCartMessage) loadingCartMessage.style.display = 'none';
           if (procesarPedidoBtn) procesarPedidoBtn.disabled = true;
          return;
      }

      if (loadingCartMessage) loadingCartMessage.style.display = 'block';

      try {
          const response = await fetch('get_carrito.php');
          const data = await response.json();

          if (data.success) {
              displayCart(data.items);
          } else {
              showCartMessage('Error al cargar el carrito: ' + (data.message || 'Error desconocido'), 'error');
              displayCart([]);
          }
      } catch (error) {
          console.error('Error fetching cart:', error);
          showCartMessage('Error de red al cargar el carrito.', 'error');
          displayCart([]);
      } finally {
           if (loadingCartMessage) loadingCartMessage.style.display = 'none';
      }
  }

  async function addToCartDB(gtin) {
      if (!isLoggedIn) {
          showCartMessage('Por favor, <a href="#login-container">inicia sesión</a> o <a href="#registro-container">regístrate</a> para añadir productos al carrito.', 'error');
          return;
      }

      showCartMessage('Añadiendo producto...');

      const formData = new FormData();
      formData.append('gtin', gtin);

      try {
          const response = await fetch('añadir_al_carrito.php', {
              method: 'POST',
              body: formData
          });
          const data = await response.json();

          if (data.success) {
              loadCart();
               showCartMessage('Producto añadido con éxito.');
          } else {
              showCartMessage('Error al añadir el producto: ' + (data.message || 'Error desconocido'), 'error');
          }
      } catch (error) {
          console.error('Error adding to cart:', error);
          showCartMessage('Error de red al añadir el producto.', 'error');
      }
  }

   async function removeFromCartDB(gtin) {
       if (!isLoggedIn) {
           showCartMessage('Debes iniciar sesión para modificar tu carrito.', 'error');
           return;
       }

       showCartMessage('Eliminando producto...');

       const formData = new FormData();
       formData.append('gtin', gtin);

       try {
           const response = await fetch('eliminar_carrito.php', {
               method: 'POST',
               body: formData
           });
           const data = await response.json();

           if (data.success) {
               loadCart();
               showCartMessage('Producto eliminado.');
           } else {
               showCartMessage('Error al eliminar el producto: ' + (data.message || 'Error desconocido'), 'error');
           }
       } catch (error) {
           console.error('Error removing from cart:', error);
           showCartMessage('Error de red al eliminar el producto.', 'error');
       }
   }

  async function processOrder() {
      if (!isLoggedIn) {
           showCartMessage('Debes iniciar sesión para proceder al pago.', 'error');
           return;
      }

      if (cartList.children.length <= 1 && cartList.querySelector('p#loading-cart-message') === null ) {
           showCartMessage('El carrito está vacío.', 'error');
           if (procesarPedidoBtn) procesarPedidoBtn.disabled = true;
           return;
      }

      showCartMessage('Procesando tu compra...');
      if (procesarPedidoBtn) procesarPedidoBtn.disabled = true;

      try {
          const response = await fetch('procesar_pedido.php', {
              method: 'POST'
          });
          const data = await response.json();

          if (data.success) {
              showCartMessage(data.message, 'checkout-success', false);
              loadCart();
          } else {
              showCartMessage('Error en el pago: ' + (data.message || 'Error desconocido'), 'error');
               if (procesarPedidoBtn) procesarPedidoBtn.disabled = false;
          }
      } catch (error) {
          console.error('Error processing order:', error);
          showCartMessage('Error de red al procesar la compra.', 'error');
           if (procesarPedidoBtn) procesarPedidoBtn.disabled = false;
      }
  }


  function handleAddToCartClick(event) {
      const button = event.target;
      const gtin = button.dataset.gtin;

      if (gtin) {
          addToCartDB(gtin);
      }
  }

   function handleRemoveFromCart(event) {
      const button = event.target;
      const gtin = button.dataset.gtin;

      if (gtin && confirm('¿Estás seguro de que quieres eliminar este producto del carrito?')) {
          removeFromCartDB(gtin);
      }
   }


  document.querySelectorAll('.add-to-cart-btn').forEach(button => {
      button.addEventListener('click', handleAddToCartClick);
  });

  if (procesarPedidoBtn) {
       if (!isLoggedIn) {
            procesarPedidoBtn.disabled = true;
       }
       procesarPedidoBtn.addEventListener('click', processOrder);
  }


  loadCart();

  const searchInput = document.getElementById('input-busqueda');
  if (searchInput) {
      searchInput.addEventListener('input', filterProducts);
  }

  function filterProducts() {
      const filter = searchInput.value.toLowerCase();
      const products = document.querySelectorAll('#catalogo .producto');

      products.forEach(product => {
          const name = product.dataset.nombre.toLowerCase();
          const category = product.dataset.categoria.toLowerCase();
          const subcategory = product.dataset.subcategoria.toLowerCase();

          if (name.includes(filter) || category.includes(filter) || subcategory.includes(filter)) {
              product.style.display = '';
          } else {
              product.style.display = 'none';
          }
      });
  }

});