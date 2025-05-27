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

$userId = $_SESSION['user_id'];

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

    if ($cartId !== null) {
        $sql_clear_cart = "DELETE FROM carrito_productos WHERE carrito_id = ?";
        $stmt_clear_cart = $conn->prepare($sql_clear_cart);
        $stmt_clear_cart->bind_param("i", $cartId);
        $stmt_clear_cart->execute();
        $stmt_clear_cart->close();
    }

    $conn->commit();
    echo json_encode(['success' => true, 'message' => '¡Felicidades! Tu compra ha sido procesada con éxito.']);

} catch (Exception $e) {
    $conn->rollback();
    error_log("Error processing order and clearing cart: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error al procesar tu compra. Por favor, inténtalo de nuevo.', 'error' => $e->getMessage()]);

} finally {
    if (isset($conn) && $conn instanceof mysqli && !$conn->connect_error) {
        $conn->close();
    }
}
?>