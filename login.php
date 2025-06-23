<?php
require_once 'php/db.php';
session_start();

// Validar que los datos fueron enviados
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    // Buscar usuario en la base de datos
    $sql = "SELECT * FROM usuarios WHERE usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuarioEncontrado = $resultado->fetch_assoc();

        // Aquí comparamos la contraseña (sin hash)
 if (password_verify($contrasena, $usuarioEncontrado['contrasena']))
 {
            // Crear sesión
            $_SESSION['usuario'] = $usuarioEncontrado['usuario'];
            $_SESSION['rol'] = $usuarioEncontrado['rol'];

            header("Location: index.php");
            exit;
        } else {
            // Contraseña incorrecta
            header("Location: views/login.php?error=Contraseña incorrecta.");
            exit;
        }
    } else {
        // Usuario no existe
        header("Location: views/login.php?error=El usuario no existe.");
        exit;
    }
} else {
    header("Location: views/login.php");
    exit;
}




