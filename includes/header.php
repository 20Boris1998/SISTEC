<?php
// Iniciar sesión y verificar usuario logueado
if (!isset($_SESSION)) session_start();

if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol'])) {
    header("Location: ../index.php");
    exit();
}

$rol = $_SESSION['rol'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>SISSEG</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <!-- Menú lateral -->
    <div id="sidebar" class="sidebar">
        <h2>SISSEG</h2>
        <ul>
            <li><a href="dashboard.php">🏠 Dashboard</a></li>

            <?php if ($rol === 'admin'): ?>
                <li><a href="usuarios.php">👤 Usuarios</a></li>
                <li><a href="equipos.php">📟 Equipos</a></li>
                <li><a href="alertas.php">🚨 Alertas</a></li>
                <li><a href="reportes.php">📊 Reportes</a></li>
            <?php elseif ($rol === 'tecnico'): ?>
                <li><a href="alertas.php">🚨 Alertas</a></li>
                <li><a href="reportes.php">📊 Reportes</a></li>
            <?php endif; ?>

            <li><a href="../logout.php">🚪 Cerrar Sesión</a></li>
        </ul>
    </div>

    <!-- Contenido principal -->
    <div class="main-content">
        <header>
            <h1>Sistema de Seguridad - SISSEG</h1>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">

        </header>
