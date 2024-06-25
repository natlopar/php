<?php
const API_URL = "https://jsonplaceholder.typicode.com/comments";
//Inicializar nueva sesión de cURL; ch = cURL handle
$ch = curl_init(API_URL);

//Indicar que queremos recibir el resultado de la petición en string y no mostrarla en pantalla
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

//Ejecutar la petición y guardamos el resultado.
$result = curl_exec($ch);
//aquí podría buscar el codigo http para ver los errores.

if ($result === false) {
    echo "cURL Error: " . curl_error($ch);
    curl_close($ch);
    exit;
}

// Obtener el código HTTP de la respuesta
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
if ($httpCode !== 200) {
    echo "HTTP Error: " . $httpCode;
    curl_close($ch);
    exit;
}

//otra alternativa de llamar a api es $result = file_get_contents(API_URL). solo para gets
$data = json_decode($result, true); //con esto lo guardamos en un array asociativo.

// var_dump($data);

curl_close($ch);
// Verificar si hubo errores al decodificar el JSON
if (json_last_error() !== JSON_ERROR_NONE) {
    echo "JSON Decode Error: " . json_last_error_msg();
    exit;
}
include('select_null.php');



foreach ($data as $comment) {
    $newComment = $comment['body'];
    $email = $comment['email'];
    $name = $comment['name'];
    $id = $comment['id'];

    $updateSql = "UPDATE comments SET name = :name, email = :email, comment = :comment WHERE id = :id";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bindParam(':name', $name);
    $updateStmt->bindParam(':email', $email);
    $updateStmt->bindParam(':comment', $newComment);
    $updateStmt->bindParam(':id', $id);

    if ($updateStmt->execute()) {
        echo "Comentario actualizado para el pedido ID: $id\n";
    } else {
        echo "Error al actualizar comentario para el pedido ID: $id\n";
    }
}

?>
