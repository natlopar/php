<?php

function getCommentsApi($trackingId) {
    $url = "http://api.example.com/comments?tracking_id=" . urlencode($trackingId);

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);

    $comments = json_decode($response, true);
    return $comments;
}

try {

    include('select_null.php');

    if (is_array($orders) && !empty($orders)) {
        foreach ($orders as $order) {
            $orderId = $order['id'];
            $trackingId = $order['tracking_id'];
            try {           
                $comments = getCommentsApi($trackingId);
                foreach ($comments as $comment) {
                    $name = isset($comment['name']) ? $comment['name'] : null;
                    $email = isset($comment['email']) ? $comment['email'] : null;
                    $commentText = isset($comment['comment']) ? $comment['comment'] : null;

    
                    $sqlInsert = "INSERT INTO comments (order_id, name, email, comment) VALUES (:order_id, :name, :email, :comment)";
                    $stmtInsert = $conn->prepare($sqlInsert);
                    $stmtInsert->bindParam(':order_id', $orderId, PDO::PARAM_INT);
                    $stmtInsert->bindParam(':name', $name, PDO::PARAM_STR);
                    $stmtInsert->bindParam(':email', $email, PDO::PARAM_STR);
                    $stmtInsert->bindParam(':comment', $commentText, PDO::PARAM_STR);
                    $stmtInsert->execute();

                    echo "Comentario insertado para Pedido ID: " . $orderId . "\n";
                }
            } catch (Exception $e) {
                echo "Error al obtener comentarios para Pedido ID: " . $orderId . ": " . $e->getMessage() . "\n";
                error_log("Error al obtener comentarios para Pedido ID: " . $orderId . ": " . $e->getMessage());
            }
        }
    } else {
        echo "No hay pedidos con comentarios vacíos.\n";
    }
} catch (PDOException $e) {
    echo "Error al obtener pedidos con comentarios vacíos: " . $e->getMessage() . "\n";
    error_log("Error al obtener pedidos con comentarios vacíos: " . $e->getMessage());
}
?>