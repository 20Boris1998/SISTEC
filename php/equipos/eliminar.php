<?php
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];

    $stmt = $conn->prepare("DELETE FROM equipos WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: ../../views/equipos.php?mensaje=eliminacion_exitosa");
        exit();
    } else {
        echo "❌ Error al eliminar equipo: " . $stmt->error;
    }
}
?>
A<?php
include '../db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    $stmt = $conn->prepare("DELETE FROM equipos WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => '✅ Equipo eliminado correctamente.'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'error' => '❌ Error al eliminar el equipo: ' . $stmt->error
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'error' => '❌ Método no permitido.'
    ]);
}
?>
