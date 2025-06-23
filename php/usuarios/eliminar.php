<?php
include '../db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => '✅ Usuario eliminado correctamente.']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error al eliminar: ' . $stmt->error]);
    }
}
?>
