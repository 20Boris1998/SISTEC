<?php
include '../db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $usuario = strtoupper(trim($_POST['usuario'] ?? ''));
    $rol = $_POST['rol'];
    $contrasena = trim($_POST['contrasena'] ?? '');

    if (empty($usuario) && empty($contrasena)) {
        echo json_encode(['success' => false, 'error' => 'Debe ingresar al menos un nuevo dato para actualizar.']);
        exit;
    }

    if (!empty($usuario) && !preg_match("/^[A-ZÁÉÍÓÚÑ ]+$/", $usuario)) {
        echo json_encode(['success' => false, 'error' => 'El nombre solo debe contener letras y espacios.']);
        exit;
    }

    $campos = [];
    $params = [];
    $tipos = '';

    if (!empty($usuario)) {
        $campos[] = "usuario = ?";
        $params[] = $usuario;
        $tipos .= 's';
    }

    if (!empty($contrasena)) {
        if (strlen($contrasena) < 10) {
            echo json_encode(['success' => false, 'error' => 'La contraseña debe tener al menos 10 caracteres.']);
            exit;
        }
        $hash = password_hash($contrasena, PASSWORD_DEFAULT);
        $campos[] = "contrasena = ?";
        $params[] = $hash;
        $tipos .= 's';
    }

    $campos[] = "rol = ?";
    $params[] = $rol;
    $tipos .= 's';

    $params[] = $id;
    $tipos .= 'i';

    $sql = "UPDATE usuarios SET " . implode(', ', $campos) . " WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($tipos, ...$params);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => '✅ Usuario actualizado correctamente.']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error al actualizar: ' . $stmt->error]);
    }
}
?>
