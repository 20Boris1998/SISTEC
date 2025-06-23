<?php
// Mostrar mensaje de error si viene desde login.php
$error = isset($_GET['error']) ? $_GET['error'] : '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - SISTEC</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #003366, #005580);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        form {
            background-color: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            width: 300px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            color: #003366;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #003366;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background-color: #005580;
        }

        .error {
            background-color: #ffdddd;
            color: #cc0000;
            border: 1px solid #cc0000;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 8px;
        }

        .volver {
            margin-top: 10px;
            display: block;
            text-decoration: none;
            color: #003366;
            font-weight: bold;
        }

        .volver:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <form action="../login.php" method="POST">
        <h2>🔐 Iniciar sesión</h2>

        <?php if (!empty($error)): ?>
            <div class="error">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <input type="text" name="usuario" placeholder="Usuario" required>
        <input type="password" name="contrasena" placeholder="Contraseña" required>
        <button type="submit">Ingresar</button>
    </form>

</body>
</html>
