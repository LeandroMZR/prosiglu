<?php
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado.']);
    exit();
}

require 'includes/db.php';

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos.']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['gtin'])) {
    echo json_encode(['success' => false, 'message' => 'Solicitud inválida.']);
    $conn->close();
    exit();
}

$userId = $_SESSION['user_id'];
$gtin = trim($_POST['gtin']);

$gtin = $conn->real_escape_string($gtin);

$conn->begin_transaction();

try {
    $cartId = null;
    $sql_find_cart = "SELECT id FROM carritos WHERE usuario_id = ?";
    $stmt_find_cart = $conn->prepare($sql_find_cart);
    $stmt_find_cart->bind_param("i", $userId);
    $stmt_find_cart->execute();
    $stmt_find_cart->bind_result($cartId);
    $stmt_find_cart->fetch();
    $stmt_find_cart->close();

    if ($cartId === null) {
        $sql_create_cart = "INSERT INTO carritos (usuario_id) VALUES (?)";
        $stmt_create_cart = $conn->prepare($sql_create_cart);
        $stmt_create_cart->bind_param("i", $userId);
        $stmt_create_cart->execute();
        $cartId = $conn->insert_id;
        $stmt_create_cart->close();
    }

    $itemExists = false;
    $sql_find_item = "SELECT id, cantidad FROM carrito_productos WHERE carrito_id = ? AND gtin = ?";
    $stmt_find_item = $conn->prepare($sql_find_item);
    $stmt_find_item->bind_param("is", $cartId, $gtin);
    $stmt_find_item->execute();
    $stmt_find_item->store_result();
    $stmt_find_item->bind_result($itemId, $currentQuantity);

    if ($stmt_find_item->num_rows > 0) {
        $itemExists = true;
        $stmt_find_item->fetch();
    }
    $stmt_find_item->close();

    if ($itemExists) {
        $sql_update_item = "UPDATE carrito_productos SET cantidad = cantidad + 1 WHERE id = ?";
        $stmt_update_item = $conn->prepare($sql_update_item);
        $stmt_update_item->bind_param("i", $itemId);
        $stmt_update_item->execute();
        $stmt_update_item->close();
    } else {
         $sql_check_product = "SELECT gtin FROM productos WHERE gtin = ?";
         $stmt_check_product = $conn->prepare($sql_check_product);
         $stmt_check_product->bind_param("s", $gtin);
         $stmt_check_product->execute();
         $stmt_check_product->store_result();
         if ($stmt_check_product->num_rows == 0) {
             $conn->rollback();
             $stmt_check_product->close();
             $conn->close();
             echo json_encode(['success' => false, 'message' => 'Producto no válido.']);
             exit();
         }
         $stmt_check_product->close();


        $sql_insert_item = "INSERT INTO carrito_productos (carrito_id, gtin, cantidad) VALUES (?, ?, 1)";
        $stmt_insert_item = $conn->prepare($sql_insert_item);
        $stmt_insert_item->bind_param("is", $cartId, $gtin);
        $stmt_insert_item->execute();
        $stmt_insert_item->close();
    }

    $conn->commit();
    echo json_encode(['success' => true, 'message' => 'Producto añadido al carrito.']);

} catch (Exception $e) {
    $conn->rollback();
    error_log("Error adding to cart: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error al añadir el producto al carrito.', 'error' => $e->getMessage()]);

} finally {
    if (isset($conn) && $conn instanceof mysqli && !$conn->connect_error) {
        $conn->close();
    }
}
?>