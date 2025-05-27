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
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'El carrito no existe o está vacío.']);
        $conn->close();
        exit();
    }

    $sql_find_item = "SELECT id, cantidad FROM carrito_productos WHERE carrito_id = ? AND gtin = ?";
    $stmt_find_item = $conn->prepare($sql_find_item);
    $stmt_find_item->bind_param("is", $cartId, $gtin);
    $stmt_find_item->execute();
    $stmt_find_item->store_result();
    $stmt_find_item->bind_result($itemId, $currentQuantity);

    if ($stmt_find_item->num_rows === 0) {
        $conn->rollback();
        $stmt_find_item->close();
        echo json_encode(['success' => false, 'message' => 'El producto no se encuentra en el carrito.']);
        $conn->close();
        exit();
    }

    $stmt_find_item->fetch();
    $stmt_find_item->close();

    $sql_delete_item = "DELETE FROM carrito_productos WHERE id = ?";
    $stmt_delete_item = $conn->prepare($sql_delete_item);
    $stmt_delete_item->bind_param("i", $itemId);
    $stmt_delete_item->execute();
    $stmt_delete_item->close();


    $conn->commit();
    echo json_encode(['success' => true, 'message' => 'Producto eliminado del carrito.']);

} catch (Exception $e) {
    $conn->rollback();
    error_log("Error removing from cart: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error al eliminar el producto del carrito.', 'error' => $e->getMessage()]);

} finally {
    if (isset($conn) && $conn instanceof mysqli && !$conn->connect_error) {
        $conn->close();
    }
}
?>