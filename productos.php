<?php
// Configuración de la base de datos (ajusta según tu XAMPP)
$host = 'localhost';
$db = 'prosiglu_db'; // cambia por el nombre real de tu base de datos
$user = 'root';
$pass = ''; // por defecto en XAMPP suele estar en blanco

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta JOIN para obtener productos con categoría
$sql = "
    SELECT p.gtin, p.nombre, p.descripcion, p.precio, p.imagen,
           s.nombre AS subcategoria, c.nombre AS categoria
    FROM productos p
    JOIN subcategorias s ON p.subcategoria_id = s.id
    JOIN categorias c ON s.categoria_id = c.id
";

$result = $conn->query($sql);

$productos = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $productos[] = [
            'gtin' => $row['gtin'],
            'nombre' => $row['nombre'],
            'descripcion' => $row['descripcion'],
            'precio' => $row['precio'],
            'imagen' => $row['imagen'],
            'subcategoria' => $row['subcategoria'],
            'categoria' => $row['categoria']
        ];
    }
}

// Devuelve los datos como JSON
header('Content-Type: application/json');
echo json_encode($productos);

$conn->close();
?>
