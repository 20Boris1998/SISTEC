<?php
require_once '../php/db.php';
require_once '../includes/verificar_sesion.php';
require_once '../includes/roles.php';
include '../includes/header.php';

$nombreUsuario = $_SESSION['usuario'];
$rolUsuario = $_SESSION['rol'];

// Redirige si no es admin ni técnico
if (!in_array($rolUsuario, ['admin', 'tecnico'])) {
    header('Location: ../index.php');
    exit;
}

// Función de permisos
function tienePermiso($rol, $permiso) {
    $permisos = [
        'admin' => ['usuarios', 'equipos', 'reportes', 'alertas'],
        'tecnico' => ['reportes', 'alertas']
    ];
    return in_array($permiso, $permisos[$rol]);
}

// Nombre visible del rol
$nombreRol = ($rolUsuario === 'admin') ? 'Administrador' : (($rolUsuario === 'tecnico') ? 'Técnico' : ucfirst($rolUsuario));
?>

<style>
    .dashboard-container {
        padding: 30px;
        max-width: 900px;
        margin: auto;
    }

    .dashboard-container h2 {
        text-align: center;
        margin-bottom: 10px;
    }

    .dashboard-container p {
        text-align: center;
        margin-bottom: 30px;
        font-size: 18px;
        color: #555;
    }

    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }

    .card {
        background-color: #f8f9fa;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        text-decoration: none;
        color: #333;
        font-weight: bold;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .card:hover {
        background-color: #e0f7ff;
        transform: translateY(-4px);
    }

    .logout-card {
        background-color: #ffe0e0;
    }

    .logout-card:hover {
        background-color: #ffc4c4;
    }
</style>

<div class="dashboard-container">
    <h2>👋 ¡Bienvenido, <?= htmlspecialchars($nombreUsuario) ?>!</h2>
    <p>Tu rol es: <strong><?= $nombreRol ?></strong></p>

    <div class="dashboard-grid">
        <?php if (tienePermiso($rolUsuario, 'usuarios')): ?>
            <a href="usuarios.php" class="card">👥 Gestión de Usuarios</a>
        <?php endif; ?>

        <?php if (tienePermiso($rolUsuario, 'equipos')): ?>
            <a href="equipos.php" class="card">💻 Gestión de Equipos</a>
        <?php endif; ?>

        <?php if (tienePermiso($rolUsuario, 'reportes')): ?>
            <a href="reportes.php" class="card">📊 Reportes</a>
        <?php endif; ?>

        <?php if (tienePermiso($rolUsuario, 'alertas')): ?>
            <a href="alertas.php" class="card">🚨 Alertas</a>
        <?php endif; ?>

        <a href="../logout.php" class="card logout-card">🔓 Cerrar Sesión</a>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
