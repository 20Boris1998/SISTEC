<?php
require_once '../db.php';
$sql = "SELECT id, usuario, rol FROM usuarios ORDER BY id DESC";
$result = $conn->query($sql);

$usuarios = [];
while ($row = $result->fetch_assoc()) {
    $usuarios[] = $row;
}

header('Content-Type: application/json');
echo json_encode($usuarios);
?>
