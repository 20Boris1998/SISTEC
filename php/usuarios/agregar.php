<?php
include '../db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = strtoupper(trim($_POST['usuario']));
    $contrasena_raw = $_POST['contrasena'];
    $rol = $_POST['rol'];

    if (!preg_match("/^[A-ZÁÉÍÓÚÑ ]+$/", $usuario)) {
        echo json_encode(['success' => false, 'error' => 'El nombre solo debe contener letras y espacios.']);
        exit;
    }

    if (strlen($contrasena_raw) < 10) {
        echo json_encode(['success' => false, 'error' => 'La contraseña debe tener al menos 10 caracteres.']);
        exit;
    }

    $verificar = $conn->prepare("SELECT id FROM usuarios WHERE usuario = ?");
    $verificar->bind_param("s", $usuario);
    $verificar->execute();
    $verificar->store_result();

    if ($verificar->num_rows > 0) {
        echo json_encode(['success' => false, 'error' => 'El usuario ya existe.']);
        exit;
    }

    $hash = password_hash($contrasena_raw, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO usuarios (usuario, contrasena, rol) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $usuario, $hash, $rol);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => '✅ Usuario agregado correctamente.']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error al registrar: ' . $stmt->error]);
    }
}
?>
