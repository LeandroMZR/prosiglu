<?php
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => true, 'items' => []]);
    exit();
}

require 'includes/db.php';

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos.']);
    exit();
}

$userId = $_SESSION['user_id'];
$cartItems = [];
$total = 0;

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
        $sql_get_items = "SELECT cp.gtin, cp.cantidad, p.nombre, p.precio, p.imagen
                          FROM carrito_productos cp
                          JOIN productos p ON cp.gtin = p.gtin
                          WHERE cp.carrito_id = ?";

        $stmt_get_items = $conn->prepare($sql_get_items);
        $stmt_get_items->bind_param("i", $cartId);
        $stmt_get_items->execute();
        $result = $stmt_get_items->get_result();

        while ($row = $result->fetch_assoc()) {
            $cartItems[] = $row;
            $total += $row['precio'] * $row['cantidad'];
        }

        $stmt_get_items->close();
    }

    echo json_encode(['success' => true, 'items' => $cartItems, 'total' => number_format($total, 2)]);

} catch (Exception $e) {
     error_log("Error fetching cart: " . $e->getMessage());
     echo json_encode(['success' => false, 'message' => 'Error al cargar el carrito.', 'error' => $e->getMessage()]);

} finally {
    if (isset($conn) && $conn instanceof mysqli && !$conn->connect_error) {
        $conn->close();
    }
}
?>