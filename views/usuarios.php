<?php
require_once '../php/db.php';
require_once '../includes/verificar_sesion.php';
if ($_SESSION['rol'] !== 'admin' && $_SESSION['rol'] !== 'tecnico') {
    header('Location: ../index.php');
    exit;
}
require_once '../includes/roles.php';
include '../includes/header.php';
?>

<h2>👤 Gestión de Usuarios</h2>
<a href="dashboard.php" class="btn-volver">← Volver</a>

<div id="mensaje-alerta" style="display:none; padding:10px; border-radius:5px; margin-top:10px; position:relative;"></div>

<?php if ($_SESSION['rol'] === 'admin'): ?>
<form id="form-agregar-usuario" class="form-usuario">
    <input type="text" name="usuario" id="usuario" placeholder="Nombre de usuario" required>
    <input type="password" name="contrasena" id="contrasena" placeholder="Contraseña" required>
    <select name="rol" id="rol">
        <option value="admin">Administrador</option>
        <option value="tecnico">Técnico</option>
    </select>
    <button type="submit">Agregar Usuario</button>
</form>
<?php endif; ?>

<h3>📋 Lista de Usuarios</h3>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Usuario</th>
            <th>Rol</th>
            <?php if ($_SESSION['rol'] === 'admin'): ?>
            <th>Acciones</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php
        $sql = "SELECT id, usuario, rol FROM usuarios ORDER BY id DESC";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['usuario']) ?></td>
                <td><?= $row['rol'] === 'admin' ? 'Administrador' : 'Técnico' ?></td>
                <?php if ($_SESSION['rol'] === 'admin'): ?>
                <td>
                    <form method="POST" action="../php/usuarios/actualizar.php" style="display:inline" class="form-editar">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <input type="text" name="usuario" placeholder="Nuevo nombre (opcional)">
                        <input type="password" name="contrasena" placeholder="Nueva contraseña (opcional)">
                        <select name="rol">
                            <option value="admin" <?= $row['rol'] == 'admin' ? 'selected' : '' ?>>Administrador</option>
                            <option value="tecnico" <?= $row['rol'] == 'tecnico' ? 'selected' : '' ?>>Técnico</option>
                        </select>
                        <button type="submit">✏ Editar</button>
                    </form>

                    <form method="POST" action="../php/usuarios/eliminar.php" style="display:inline" onsubmit="return false;">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <button type="submit" onclick="confirmarEliminacion(this.closest('form'))">🗑 Eliminar</button>
                    </form>
                </td>
                <?php endif; ?>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<!-- Modal de confirmación -->
<div id="confirmModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color:rgba(0,0,0,0.4); z-index:999;">
  <div style="background:white; padding:30px; border-radius:10px; width:90%; max-width:400px; margin:100px auto; text-align:center;">
    <h3 id="modalTitle">¿Estás seguro?</h3>
    <p id="modalMessage">Esta acción no se puede deshacer.</p>
    <button id="confirmBtn" style="background-color:#28a745; color:white; border:none; padding:10px 20px; margin-right:10px; border-radius:5px;">Sí</button>
    <button onclick="closeModal()" style="background-color:#dc3545; color:white; border:none; padding:10px 20px; border-radius:5px;">Cancelar</button>
  </div>
</div>

<script>
let currentForm = null;
let eliminarUsuario = false;

function closeModal() {
    document.getElementById('confirmModal').style.display = 'none';
    currentForm = null;
    eliminarUsuario = false;
}

function showModal(message, form, isDelete = false) {
    currentForm = form;
    eliminarUsuario = isDelete;
    document.getElementById('modalMessage').textContent = message;
    document.getElementById('confirmModal').style.display = 'block';
}

function confirmarEliminacion(form) {
    showModal('¿Estás seguro de que deseas eliminar este usuario?', form, true);
}

document.querySelectorAll('.form-editar button[type="submit"]').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        showModal('¿Deseas guardar los cambios para este usuario?', this.closest('form'));
    });
});

document.getElementById('confirmBtn').addEventListener('click', function () {
    const mensaje = document.getElementById('mensaje-alerta');

    if (!currentForm) return;

    fetch(currentForm.action, {
        method: 'POST',
        body: new FormData(currentForm)
    })
    .then(res => res.json())
    .then(data => {
        mensaje.style.display = 'block';
        mensaje.style.backgroundColor = data.success ? '#d4edda' : '#f8d7da';
        mensaje.style.color = data.success ? '#155724' : '#721c24';
        mensaje.innerHTML = `${data.success ? '✅' : '❌'} ${data.message || data.error}` +
            '<button onclick="this.parentElement.style.display=\'none\'" style="position:absolute; top:5px; right:10px; background:none; border:none; font-size:18px;">&times;</button>';
        if (data.success) setTimeout(() => location.reload(), 1500);
    });

    closeModal();
});

// Agregar usuario con validación y mayúsculas
document.getElementById('form-agregar-usuario').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = this;
    const formData = new FormData(form);

    // Convertir a mayúsculas
    const usuario = formData.get('usuario').toUpperCase();
    formData.set('usuario', usuario);

    fetch('../php/usuarios/agregar.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        const mensaje = document.getElementById('mensaje-alerta');
        mensaje.style.display = 'block';
        mensaje.style.backgroundColor = data.success ? '#d4edda' : '#f8d7da';
        mensaje.style.color = data.success ? '#155724' : '#721c24';
        mensaje.innerHTML = `${data.success ? '✅' : '❌'} ${data.message || data.error}` +
            '<button onclick="this.parentElement.style.display=\'none\'" style="position:absolute; top:5px; right:10px; background:none; border:none; font-size:18px;">&times;</button>';
        if (data.success) {
            form.reset();
            setTimeout(() => location.reload(), 1500);
        }
    });
});
</script>

<?php include '../includes/footer.php'; ?>
