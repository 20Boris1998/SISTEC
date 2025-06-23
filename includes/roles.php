<?php
function solo_admin() {
    if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
        header("Location: dashboard.php");
        exit();
    }
}

function solo_tecnico_o_admin() {
    if (!isset($_SESSION['rol']) || !in_array($_SESSION['rol'], ['admin', 'tecnico'])) {
        header("Location: dashboard.php");
        exit();
    }
}