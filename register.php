<?php
include 'php/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT); // Hash seguro
    $rol = $_POST['rol'];

    $sql = "INSERT INTO usuarios (usuario, contrasena, rol) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $usuario, $contrasena, $rol);

    if ($stmt->execute()) {
        echo "Usuario registrado correctamente.";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!-- Formulario simple para registrar -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Usuario</title>
</head>
<body>
    <h2>Registrar Usuario</h2>
    <form method="POST" action="register.php">
        <input type="text" name="usuario" placeholder="Usuario" required>
        <input type="password" name="contrasena" placeholder="Contraseña" required>
        <select name="rol">
            <option value="admin">Administrador</option>
            <option value="tecnico">Técnico</option>
            <option value="invitado">Invitado</option>
        </select>
        <button type="submit">Registrar</button>
    </form>
</body>
</html>
