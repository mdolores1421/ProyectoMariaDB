<?php
try {
    $host = "172.16.4.150";
    $user = "usuario";
    $password = "123456789";
    $db = "COCHES";

    $connection = new PDO("mysql:host=$host", $username, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Conexión exitosa a MariaDB.";
} catch (PDOException $e) {
    echo "Error al conectar: " . $e->getMessage();
}
