<?php
include '../db.php';
header('Content-Type: application/json');

$sql = "SELECT id, numero_tarjeta_rfid, nombre, ubicacion, estado, fecha_registro FROM equipos ORDER BY id DESC";
$resultado = $conn->query($sql);

$equipos = [];

while ($fila = $resultado->fetch_assoc()) {
    $equipos[] = $fila;
}

echo json_encode($equipos);
?>
