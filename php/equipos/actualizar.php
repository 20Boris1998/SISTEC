<?php
include '../db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nombre = strtoupper(trim($_POST['nombre']));
    $ubicacion = $_POST['ubicacion'];
    $estado = $_POST['estado'];
    $numeroTarjeta = strtoupper(trim($_POST['numero_tarjeta_rfid']));

    if (!preg_match('/^[A-Z0-9\s]+$/', $nombre)) {
        echo json_encode([
            'success' => false,
            'error' => '❌ El nombre solo puede contener letras, números y espacios.'
        ]);
        exit();
    }

    if (!preg_match('/^[A-Z0-9]+$/', $numeroTarjeta)) {
        echo json_encode([
            'success' => false,
            'error' => '❌ El número de tarjeta RFID solo puede contener letras y números.'
        ]);
        exit();
    }

    // Verificar duplicado excluyendo el mismo ID
    $verificar = $conn->prepare("SELECT id FROM equipos WHERE numero_tarjeta_rfid = ? AND id != ?");
    $verificar->bind_param("si", $numeroTarjeta, $id);
    $verificar->execute();
    $verificar->store_result();

    if ($verificar->num_rows > 0) {
        echo json_encode([
            'success' => false,
            'error' => '⚠️ Otro equipo ya tiene esa tarjeta RFID.'
        ]);
        exit();
    }

    $stmt = $conn->prepare("UPDATE equipos SET nombre = ?, ubicacion = ?, estado = ?, numero_tarjeta_rfid = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $nombre, $ubicacion, $estado, $numeroTarjeta, $id);

    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => '✅ Equipo actualizado correctamente.'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'error' => '❌ Error al actualizar el equipo: ' . $stmt->error
        ]);
    }
}
?>
