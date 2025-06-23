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

<div class="container">
    <h2>📟 Gestión de Equipos</h2>
    <a href="dashboard.php" class="btn-volver">← Volver</a>

    <div id="mensaje-alerta" style="display:none; padding:10px; border-radius:5px; margin-top:10px; position:relative;"></div>

    <!-- FORMULARIO -->
    <form id="formEquipo" method="POST" action="../php/equipos/agregar.php">
        <input type="hidden" name="id" id="equipo_id">

        <label>Número de Tarjeta RFID:</label>
        <input type="text" name="numero_tarjeta_rfid" id="numero_tarjeta_rfid" required pattern="[A-Za-z0-9]+" title="Solo letras y números">

        <label>Nombre del equipo (nombre y número):</label>
        <input type="text" name="nombre" id="nombre" required pattern="[A-Za-z0-9 ]+" title="Solo letras y números">

        <label>Laboratorio:</label>
        <select name="ubicacion" id="ubicacion" required>
            <option value="">Seleccione...</option>
            <?php
            $laboratorios = ['101','102','201','202','203','204','301','302','303','304','401','402','403','404','501','502','503','504','601','602','603','604','701','702','703','704','801','802','803','804'];
            foreach ($laboratorios as $lab) {
                echo "<option value='Laboratorio $lab'>Laboratorio $lab</option>";
            }
            ?>
        </select>

        <label>Estado:</label>
        <select name="estado" id="estado" required>
            <option value="Autorizado">Autorizado</option>
            <option value="No autorizado">No autorizado</option>
        </select>

        <button type="submit" id="btnGuardar">Guardar Equipo</button>
        <button type="button" onclick="cancelarEdicion()" style="display:none;" id="btnCancelar">Cancelar</button>
    </form>

    <!-- TABLA -->
    <h3>📋 Lista de Equipos</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Número de Tarjeta RFID</th>
                <th>Nombre</th>
                <th>Ubicación</th>
                <th>Estado</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $resultado = $conn->query("SELECT * FROM equipos ORDER BY id DESC");
            while ($fila = $resultado->fetch_assoc()) {
                echo "<tr>
                    <td>{$fila['id']}</td>
                    <td>{$fila['numero_tarjeta_rfid']}</td>
                    <td>{$fila['nombre']}</td>
                    <td>{$fila['ubicacion']}</td>
                    <td>{$fila['estado']}</td>
                    <td>{$fila['fecha_registro']}</td>
                    <td>
                        <button onclick='editarEquipo(" . json_encode($fila) . ")'>✏️</button>
                        <form method='POST' action='../php/equipos/eliminar.php' style='display:inline' onsubmit='return false;'>
                            <input type='hidden' name='id' value='{$fila['id']}'>
                            <button type='submit' onclick='confirmarEliminacion(this.closest(\"form\"))'>🗑️</button>
                        </form>
                    </td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<!-- MODAL -->
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

function editarEquipo(equipo) {
    document.getElementById('equipo_id').value = equipo.id;
    document.getElementById('numero_tarjeta_rfid').value = equipo.numero_tarjeta_rfid;
    document.getElementById('nombre').value = equipo.nombre;
    document.getElementById('ubicacion').value = equipo.ubicacion;
    document.getElementById('estado').value = equipo.estado;

    document.getElementById('formEquipo').action = "../php/equipos/actualizar.php";
    document.getElementById('btnGuardar').textContent = "Actualizar Equipo";
    document.getElementById('btnCancelar').style.display = "inline";
}

function cancelarEdicion() {
    document.getElementById('formEquipo').reset();
    document.getElementById('formEquipo').action = "../php/equipos/agregar.php";
    document.getElementById('btnGuardar').textContent = "Guardar Equipo";
    document.getElementById('btnCancelar').style.display = "none";
}

function closeModal() {
    document.getElementById('confirmModal').style.display = 'none';
    currentForm = null;
}

function showModal(message, form) {
    currentForm = form;
    document.getElementById('modalMessage').textContent = message;
    document.getElementById('confirmModal').style.display = 'block';
}

function confirmarEliminacion(form) {
    showModal('¿Estás seguro de que deseas eliminar este equipo?', form);
}

document.getElementById('confirmBtn').addEventListener('click', function () {
    if (currentForm) currentForm.submit();
});

document.getElementById('formEquipo').addEventListener('submit', function(e) {
    e.preventDefault();

    const mensaje = document.getElementById('mensaje-alerta');
    const formData = new FormData(this);
    const isUpdate = this.action.includes("actualizar.php");

    fetch(this.action, {
        method: 'POST',
        body: formData
    })
    .then(res => res.text())
    .then(res => {
        mensaje.style.display = 'block';
        if (res.includes("⚠️")) {
            mensaje.style.backgroundColor = '#f8d7da';
            mensaje.style.color = '#721c24';
            mensaje.innerHTML = `❌ ${res}` +
                '<button onclick="this.parentElement.style.display=\'none\'" style="position:absolute; top:5px; right:10px; background:none; border:none; font-size:18px;">&times;</button>';
        } else {
            mensaje.style.backgroundColor = '#d4edda';
            mensaje.style.color = '#155724';
            mensaje.innerHTML = `✅ ${isUpdate ? 'Equipo actualizado correctamente.' : 'Equipo agregado exitosamente.'}` +
                '<button onclick="this.parentElement.style.display=\'none\'" style="position:absolute; top:5px; right:10px; background:none; border:none; font-size:18px;">&times;</button>';
            setTimeout(() => location.reload(), 1500);
        }
    });
});
</script>

<?php include '../includes/footer.php'; ?>
