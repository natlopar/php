<?php
$servername = "db";  
$username = "root";  
$dbpassword = "root24";  
$database = "comentarios";  

try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Conexión exitosa<br>";
} catch(PDOException $e) {
    echo "Conexión fallida: " . $e->getMessage();
}
?>