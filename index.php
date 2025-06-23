<?php
require_once 'php/db.php';
require_once 'includes/verificar_sesion.php';
require_once 'includes/roles.php';

$nombreUsuario = $_SESSION['usuario'];
$rolUsuario = $_SESSION['rol'];
?>

<style>
    .dashboard-container {
        max-width: 900px;
        margin: auto;
        padding: 20px;
    }

    .welcome-banner {
        background: linear-gradient(to right, #003366, #005580);
        color: white;
        padding: 30px;
        border-radius: 15px;
        margin-bottom: 30px;
        text-align: center;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .welcome-banner h2 {
        margin: 0;
        font-size: 28px;
    }

    .welcome-banner p {
        margin-top: 10px;
        font-size: 18px;
    }

    .card-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }

    .card {
        background-color: #f5f5f5;
        padding: 25px;
        border-radius: 10px;
        text-align: center;
        font-size: 18px;
        font-weight: bold;
        text-decoration: none;
        color: #003366;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        background-color: #e6f0ff;
    }

    .card.logout {
        background-color: #ffe6e6;
        color: #cc0000;
    }

    .card.logout:hover {
        background-color: #ffcccc;
    }

    .footer-sistec {
    background-color: #003366;  /* Azul oscuro como en otras vistas */
    color: white;
    text-align: center;
    padding: 15px;
    margin-top: 40px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
</style>

<div class="dashboard-container">
    <div class="welcome-banner">
        <h2>👋 ¡Bienvenido, <?= htmlspecialchars($nombreUsuario) ?>!</h2>
<?php
$rolLegible = [
    'admin' => 'Administrador',
    'tecnico' => 'Técnico',
    'invitado' => 'Invitado'
];
?>
<p>Tu rol es: <strong><?= $rolLegible[$rolUsuario] ?? ucfirst($rolUsuario) ?></strong></p>
    </div>

    <div class="card-grid">
        <?php if ($rolUsuario === 'admin'): ?>
            <a href="views/dashboard.php" class="card">👤 Ingresar</a>

        <?php elseif ($rolUsuario === 'tecnico'): ?>
            <a href="views/dashboard.php" class="card">👤 Ingresar</a>
        <?php endif; ?>

        <a href="logout.php" class="card logout">🔓 Cerrar Sesión</a>
    </div>
</div>

<footer class="footer-sistec">
    <p>© 2025 SISSEG - Todos los derechos reservados.</p>
</footer>

