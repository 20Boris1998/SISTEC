<?php
require_once '../db.php';
header('Content-Type: application/json');

$response = [
    'success' => false,
    'data' => [],
    'error' => null
];

$sql = "SELECT id, mensaje, nivel, ubicacion, fecha FROM alertas ORDER BY fecha DESC";
$result = $conn->query($sql);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $response['data'][] = $row;
    }
    $response['success'] = true;
} else {
    $response['error'] = "Error al obtener alertas: " . $conn->error;
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);
