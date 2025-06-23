<?php
require_once '../db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rfid = $_POST['numero_tarjeta_rfid'] ?? '';

    if (empty($rfid)) {
        http_response_code(400);
        echo json_encode(["success" => false, "error" => "❌ número de tarjeta RFID no proporcionado."]);
        exit;
    }

    $stmt = $conn->prepare("SELECT nombre, ubicacion FROM equipos WHERE numero_tarjeta_rfid = ?");
    $stmt->bind_param("s", $rfid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($equipo = $result->fetch_assoc()) {
        $nombre = $equipo['nombre'];
        $ubicacion = $equipo['ubicacion'];
        $mensaje = "📟 {$nombre} ha salido del laboratorio";
        $nivel = 'peligro';  // ← EQUIPO REGISTRADO

        $insert = $conn->prepare("INSERT INTO alertas (mensaje, nivel, ubicacion) VALUES (?, ?, ?)");
        $insert->bind_param("sss", $mensaje, $nivel, $ubicacion);
    } else {
        $mensaje = "⚠️ Tarjeta no registrada (UID: $rfid) detectada.";
        $nivel = 'cuidado';  // ← NO REGISTRADO
        $ubicacion = "Desconocido";

        $insert = $conn->prepare("INSERT INTO alertas (mensaje, nivel, ubicacion) VALUES (?, ?, ?)");
        $insert->bind_param("sss", $mensaje, $nivel, $ubicacion);
    }

    if ($insert->execute()) {
        echo json_encode(["success" => true, "message" => $mensaje]);
    } else {
        echo json_encode(["success" => false, "error" => "❌ Error al registrar la alerta."]);
    }
} else {
    http_response_code(405);
    echo json_encode(["success" => false, "error" => "❌ Método no permitido. Usa POST."]);
}
