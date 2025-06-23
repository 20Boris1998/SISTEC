<?php
require_once '../php/db.php';
require_once '../includes/verificar_sesion.php';
require_once '../includes/roles.php';

// AJAX dinámico para equipos o alertas
if (isset($_GET['ajax'])) {
    $dia = isset($_GET['dia']) ? intval($_GET['dia']) : '';
    $mes = isset($_GET['mes']) ? intval($_GET['mes']) : '';
    $anio = isset($_GET['anio']) ? intval($_GET['anio']) : '';

    if ($_GET['ajax'] === 'equipos') {
        $sql = "SELECT * FROM equipos WHERE 1=1";
        if (!empty($dia)) $sql .= " AND DAY(fecha_registro) = $dia";
        if (!empty($mes)) $sql .= " AND MONTH(fecha_registro) = $mes";
        if (!empty($anio)) $sql .= " AND YEAR(fecha_registro) = $anio";

        $resultado = $conn->query($sql);
        if ($resultado && $resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                $laboratorio = isset($fila['ubicacion']) ? $fila['ubicacion'] : 'No especificado';
                echo "<tr>
                        <td>{$fila['nombre']}</td>
                        <td>{$fila['fecha_registro']}</td>
                        <td>$laboratorio</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No hay resultados.</td></tr>";
        }
        exit;
    }

    if ($_GET['ajax'] === 'alertas') {
        $sql = "SELECT * FROM alertas WHERE 1=1";
        if (!empty($dia)) $sql .= " AND DAY(fecha) = $dia";
        if (!empty($mes)) $sql .= " AND MONTH(fecha) = $mes";
        if (!empty($anio)) $sql .= " AND YEAR(fecha) = $anio";
        $sql .= " ORDER BY fecha DESC";

        $alertas = $conn->query($sql);
        if ($alertas && $alertas->num_rows > 0) {
            while ($a = $alertas->fetch_assoc()) {
                $laboratorio = isset($a['ubicacion']) ? $a['ubicacion'] : 'No especificado';
                echo "<tr>
                        <td>{$a['mensaje']}</td>
                        <td>{$a['nivel']}</td>
                        <td>{$a['fecha']}</td>
                        <td>$laboratorio</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No hay alertas registradas.</td></tr>";
        }
        exit;
    }
}

include '../includes/header.php';
?>

<div class="container">
    <h2>📊 Reportes del Sistema</h2>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <a href="dashboard.php" class="btn-volver">← Volver</a>
        <button onclick="descargarPDFCompleto()" class="btn-descargar">📄 Descargar PDF Completo</button>
    </div>

    <!-- Reporte de Equipos -->
    <section>
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h3>Equipos Registrados</h3>
            <button onclick="descargarEquiposPDF()">📥 Descargar PDF</button>
        </div>

        <form id="filtro-form" onsubmit="event.preventDefault(); actualizarTablas();">
            <label for="dia">Día:</label>
            <input type="number" id="dia" name="dia" min="1" max="31" style="width: 70px;">

            <label for="mes">Mes:</label>
            <input type="number" id="mes" name="mes" min="1" max="12" style="width: 70px;">

            <label for="anio">Año:</label>
            <input type="number" id="anio" name="anio" min="2000" max="2100" style="width: 90px;">

            <button type="submit">Filtrar</button>
        </form>

        <table id="tabla-equipos">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Fecha</th>
                    <th>Laboratorio</th>
                </tr>
            </thead>
            <tbody id="tabla-cuerpo-equipos">
                <!-- Se llenará automáticamente por JavaScript -->
            </tbody>
        </table>
    </section>

    <!-- Reporte de Alertas -->
    <section style="margin-top: 40px;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h3>Alertas Generadas</h3>
            <button onclick="descargarAlertasPDF()">📥 Descargar PDF</button>
        </div>

        <table id="tabla-alertas">
            <thead>
                <tr>
                    <th>Mensaje</th>
                    <th>Nivel</th>
                    <th>Fecha</th>
                    <th>Laboratorio</th>
                </tr>
            </thead>
            <tbody id="tabla-cuerpo-alertas">
                <!-- Se llenará automáticamente por JavaScript -->
            </tbody>
        </table>
    </section>
</div>

<!-- jsPDF y autoTable -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>

<script>
const { jsPDF } = window.jspdf;

function descargarEquiposPDF() {
    const doc = new jsPDF();
    doc.text("Reporte de Equipos Registrados", 14, 15);
    doc.autoTable({ html: '#tabla-equipos', startY: 20 });
    doc.save('reporte_equipos.pdf');
}

function descargarAlertasPDF() {
    const doc = new jsPDF();
    doc.text("Reporte de Alertas Generadas", 14, 15);
    doc.autoTable({ html: '#tabla-alertas', startY: 20 });
    doc.save('reporte_alertas.pdf');
}

function descargarPDFCompleto() {
    const doc = new jsPDF();
    doc.text("Reporte de Equipos", 14, 15);
    doc.autoTable({ html: '#tabla-equipos', startY: 20 });

    const y = doc.lastAutoTable.finalY + 10;
    doc.text("Reporte de Alertas", 14, y);
    doc.autoTable({ html: '#tabla-alertas', startY: y + 5 });

    doc.save('reporte_completo.pdf');
}

function actualizarTablas() {
    const dia = document.getElementById('dia').value;
    const mes = document.getElementById('mes').value;
    const anio = document.getElementById('anio').value;

    const params = new URLSearchParams({
        ajax: 'equipos',
        dia: dia,
        mes: mes,
        anio: anio
    });

    fetch(`?${params.toString()}`)
        .then(res => res.text())
        .then(html => document.getElementById('tabla-cuerpo-equipos').innerHTML = html);

    fetch(`?ajax=alertas&dia=${dia}&mes=${mes}&anio=${anio}`)
        .then(res => res.text())
        .then(html => document.getElementById('tabla-cuerpo-alertas').innerHTML = html);
}

// Recarga cada 5 segundos
setInterval(actualizarTablas, 5000);
['dia', 'mes', 'anio'].forEach(id =>
    document.getElementById(id).addEventListener('input', actualizarTablas)
);
window.addEventListener('load', actualizarTablas);
</script>

<?php include '../includes/footer.php'; ?>
