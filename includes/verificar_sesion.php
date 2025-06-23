<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: views/login.php");
    exit();
}

function verificar_rol($roles_permitidos) {
    if (!in_array($_SESSION['rol'], $roles_permitidos)) {
        echo "⚠️ Acceso denegado para tu rol.";
        exit();
    }
}
