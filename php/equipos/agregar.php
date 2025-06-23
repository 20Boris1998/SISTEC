<?php
require_once '../db.php';

if (!isset($_POST['numero_tarjeta_rfid'], $_POST['nombre'], $_POST['ubicacion'], $_POST['estado'])) {
    echo "❌ Faltan campos obligatorios.";
    exit();
}

$numero_tarjeta_rfid = trim($_POST['numero_tarjeta_rfid']);
$nombre = strtoupper(trim($_POST['nombre']));
$ubicacion = $_POST['ubicacion'];
$estado = $_POST['estado'];

// Verificar si ya existe un equipo con ese número de tarjeta RFID
$verificar = $conn->prepare("SELECT id FROM equipos WHERE numero_tarjeta_rfid = ?");
$verificar->bind_param("s", $numero_tarjeta_rfid);
$verificar->execute();
$verificar->store_result();

if ($verificar->num_rows > 0) {
    echo "⚠️ Ya existe un equipo con esa tarjeta RFID.";
    exit();
}

// Insertar nuevo equipo
$stmt = $conn->prepare("INSERT INTO equipos (numero_tarjeta_rfid, nombre, ubicacion, estado, fecha_registro) VALUES (?, ?, ?, ?, NOW())");
$stmt->bind_param("ssss", $numero_tarjeta_rfid, $nombre, $ubicacion, $estado);

if ($stmt->execute()) {
    echo "✅ Equipo agregado exitosamente.";
} else {
    echo "❌ Error al guardar el equipo: " . $stmt->error;
}
