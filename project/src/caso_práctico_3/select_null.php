<?php
include('connection.php');

try {
    $sql = "SELECT id, tracking_id FROM orders WHERE id IN (SELECT order_id FROM comments WHERE comment IS NULL)";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($orders as $order) {
        echo "Pedido ID: " . $order['id'] . " - Tracking ID: " . $order['tracking_id'] . " tiene comentarios vacíos.\n";
    }

} catch (PDOException $e) {
    echo "Error al obtener pedidos con comentarios vacíos: " . $e->getMessage() . "\n";
    error_log("Error al obtener pedidos con comentarios vacíos: " . $e->getMessage());
}
?>
